<?php

declare(strict_types=1);

namespace GeorgRinger\News\Tests\Unit\Controller;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Controller\NewsController;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Extbase\Service\EnvironmentService;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Class NewsControllerTest
 */
class NewsControllerTest extends FunctionalTestCase
{
    use ProphecyTrait;

    /**
     * @var NewsController
     */
    protected $subject;

    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/news'
    ];

    /**
     * @var string[]
     */
    protected $coreExtensionsToLoad = [
        'extbase',
        'fluid'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importDataSet('PACKAGE:typo3/testing-framework/Resources/Core/Functional/Fixtures/pages.xml');
        $this->setUpFrontendRootPage(
            1,
            [
                __DIR__ . '/../Fixtures/TypoScript/setup.typoscript'
            ]
        );

        // This part is needed for TYPO3 10 compatibility
        /** @var EnvironmentService|ObjectProphecy $environmentServiceProphecy */
        $environmentServiceProphecy = $this->prophesize(EnvironmentService::class);
        $environmentServiceProphecy
            ->isEnvironmentInFrontendMode()
            ->willReturn(true);
        $environmentServiceProphecy
            ->isEnvironmentInBackendMode()
            ->willReturn(false);
        GeneralUtility::setSingletonInstance(EnvironmentService::class, $environmentServiceProphecy->reveal());

        $serverRequest = new ServerRequest();

        $applicationType = SystemEnvironmentBuilder::REQUESTTYPE_FE;
        $serverRequest = $serverRequest->withAttribute('applicationType', $applicationType);

        /** @var TypoScriptFrontendController|ObjectProphecy $typoScriptFrontendControllerProphecy */
        $typoScriptFrontendControllerProphecy = $this->prophesize(TypoScriptFrontendController::class);
        /** @var FrontendUserAuthentication|ObjectProphecy $frontendUserAuthenticationProphecy */
        $frontendUserAuthenticationProphecy = $this->prophesize(FrontendUserAuthentication::class);

        $GLOBALS['TYPO3_REQUEST'] = $serverRequest;
        $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageService::class);
        $GLOBALS['TSFE'] = $typoScriptFrontendControllerProphecy->reveal();
        $GLOBALS['TSFE']->fe_user = $frontendUserAuthenticationProphecy->reveal();
    }

    protected function tearDown(): void
    {
        unset(
            $this->subject
        );

        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionWillReturnNoNews(): void
    {
        $extbaseBootstrap = GeneralUtility::makeInstance(Bootstrap::class);
        $content = $extbaseBootstrap->run(
            '',
            [
                'extensionName' => 'News',
                'pluginName' => 'Pi1'
            ]
        );

        $this->assertStringContainsString(
            'No news available.',
            $content
        );
    }

    /**
     * @test
     */
    public function listActionWillShowNewsOfStoragePage1(): void
    {
        $date = new \DateTime('now');

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_news_domain_model_news');
        $connection->insert(
            'tx_news_domain_model_news',
            [
                'uid' => 1,
                'pid' => 1,
                'title' => 'My first news',
                'datetime' => (int)$date->format('U')
            ]
        );

        $extbaseBootstrap = GeneralUtility::makeInstance(Bootstrap::class);
        $content = $extbaseBootstrap->run(
            '',
            [
                'extensionName' => 'News',
                'pluginName' => 'Pi1'
            ]
        );

        $this->assertStringContainsString(
            '<span itemprop="headline">My first news</span>',
            $content
        );

        $this->assertStringContainsString(
            '<time itemprop="datePublished" datetime="' . $date->format('Y-m-d') . '">',
            $content
        );
    }
}
