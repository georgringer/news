<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Frontend;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

abstract class AbstractFrontendTestCase extends FunctionalTestCase
{
    protected const ROOT_PAGE_ID = 1;

    protected array $testExtensionsToLoad = ['typo3conf/ext/news'];

    /**
     * fluid_styled_content is required because Extbase registers the TypoScript
     * for tt_content.news_* via "defaultContentRendering". That is only included
     * when FE/contentRenderingTemplates is set, which fluid_styled_content does.
     */
    protected array $coreExtensionsToLoad = ['fluid', 'fluid_styled_content'];

    /**
     * The image from media.csv has to exist physically, otherwise
     * LocalDriver::hash() fails while processing it in f:image.
     */
    protected array $pathsToProvideInTestInstance = [
        'typo3conf/ext/news/Tests/Functional/Fixtures/Frontend/fileadmin' => 'fileadmin',
    ];

    /**
     * tx_news_pi1[news] and tx_news_pi1[overwriteDemand][...] are not excluded
     * from the cHash check. Without disabling it, every test would have to
     * calculate a valid cHash.
     */
    protected array $configurationToUseInTestInstance = [
        'FE' => [
            'cacheHash' => [
                'enforceValidation' => false,
            ],
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Frontend/pages.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Frontend/tt_content.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Frontend/news.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Frontend/media.csv');

        $this->writeSiteConfiguration();
    }

    /**
     * Sets up the root page TypoScript. An empty $stylePath means the default
     * templates.
     */
    protected function setUpFrontend(string $stylePath = ''): void
    {
        $this->setUpFrontendRootPage(self::ROOT_PAGE_ID, [
            'constants' => [
                'EXT:fluid_styled_content/Configuration/TypoScript/constants.typoscript',
                'EXT:news/Configuration/TypoScript/constants.typoscript',
            ],
            'setup' => [
                'EXT:fluid_styled_content/Configuration/TypoScript/setup.typoscript',
                'EXT:news/Configuration/TypoScript/setup.typoscript',
                'EXT:news/Tests/Functional/Fixtures/TypoScript/frontend.typoscript',
            ],
        ]);

        if ($stylePath !== '') {
            $this->addTypoScriptToTemplateRecord(self::ROOT_PAGE_ID, sprintf(
                'plugin.tx_news.view {
                    templateRootPaths.10 = %1$sTemplates/
                    partialRootPaths.10 = %1$sPartials/
                    layoutRootPaths.10 = %1$sLayouts/
                }',
                $stylePath
            ));
        }
    }

    /**
     * @param array<string, int|string> $query
     */
    protected function renderPage(int $pageUid, array $query = [], int $languageId = 0): ResponseInterface
    {
        $request = (new InternalRequest())->withPageId($pageUid)->withQueryParameters($query);
        if ($languageId > 0) {
            $request = $request->withLanguageId($languageId);
        }

        return $this->executeFrontendSubRequest($request);
    }

    protected function assertRendersWithoutError(ResponseInterface $response): string
    {
        self::assertSame(200, $response->getStatusCode());

        $html = (string)$response->getBody();
        self::assertStringNotContainsString('Oops, an error occurred', $html);
        self::assertStringNotContainsString('Whoops, looks like something went wrong', $html);
        self::assertNotSame('', trim($html));

        return $html;
    }

    /**
     * @return array<string, array{string}>
     */
    public static function templateStyleProvider(): array
    {
        return [
            'default' => [''],
            'twb5' => ['EXT:news/Resources/Private/Templates/Styles/Twb5/'],
        ];
    }

    protected function writeSiteConfiguration(): void
    {
        $path = $this->instancePath . '/typo3conf/sites/testing';
        GeneralUtility::mkdir_deep($path);

        file_put_contents($path . '/config.yaml', Yaml::dump($this->getSiteConfiguration(), 99, 2));
    }

    /**
     * @return array<string, mixed>
     */
    protected function getSiteConfiguration(): array
    {
        return [
            'rootPageId' => self::ROOT_PAGE_ID,
            'base' => 'http://localhost/',
            'languages' => [
                $this->getDefaultSiteLanguage(),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultSiteLanguage(): array
    {
        return [
            'title' => 'English',
            'enabled' => true,
            'languageId' => 0,
            'base' => '/',
            'locale' => 'en_US.UTF-8',
            'navigationTitle' => 'English',
            'flag' => 'us',
        ];
    }
}
