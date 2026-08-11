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
     * fluid_styled_content wird gebraucht, weil Extbase das TypoScript fuer
     * tt_content.news_* ueber "defaultContentRendering" registriert. Das wird
     * nur eingebunden, wenn FE/contentRenderingTemplates gesetzt ist - und das
     * macht fluid_styled_content.
     */
    protected array $coreExtensionsToLoad = ['fluid', 'fluid_styled_content'];

    /**
     * tx_news_pi1[news] und tx_news_pi1[overwriteDemand][...] sind nicht von der
     * cHash-Pruefung ausgenommen. Ohne diese Abschaltung muesste jeder Test einen
     * gueltigen cHash berechnen.
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

        $this->writeSiteConfiguration();
    }

    /**
     * Richtet das Root-Page-TypoScript ein. Ein leerer $stylePath bedeutet
     * Default-Templates.
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
    protected function renderPage(int $pageUid, array $query = []): ResponseInterface
    {
        return $this->executeFrontendSubRequest(
            (new InternalRequest())->withPageId($pageUid)->withQueryParameters($query)
        );
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

    private function writeSiteConfiguration(): void
    {
        $path = $this->instancePath . '/typo3conf/sites/testing';
        GeneralUtility::mkdir_deep($path);

        file_put_contents($path . '/config.yaml', Yaml::dump([
            'rootPageId' => self::ROOT_PAGE_ID,
            'base' => 'http://localhost/',
            'languages' => [
                [
                    'title' => 'English',
                    'enabled' => true,
                    'languageId' => 0,
                    'base' => '/',
                    'locale' => 'en_US.UTF-8',
                    'navigationTitle' => 'English',
                    'flag' => 'us',
                ],
            ],
        ], 99, 2));
    }
}
