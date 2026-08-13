<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Frontend;

use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;

/**
 * Covers https://github.com/georgringer/news/issues/2811: in a translated
 * language the detail link built by n:link has to use the path_segment of the
 * translation, not the one of the default language.
 */
class NewsTranslatedSlugTest extends AbstractFrontendTestCase
{
    private const LANGUAGE_ID = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Frontend/translations.csv');
    }

    #[IgnoreDeprecations]
    #[Test]
    public function listActionLinksTranslatedNewsWithTranslatedPathSegment(): void
    {
        $this->setUpFrontend();

        $html = $this->assertRendersWithoutError($this->renderPage(2, [], self::LANGUAGE_ID));

        self::assertStringContainsString('Translated Test News', $html);
        self::assertStringContainsString('/de/detail/translated-test-news', $html);
        self::assertStringNotContainsString('/de/detail/rendering-test-news-one', $html);
    }

    #[IgnoreDeprecations]
    #[Test]
    public function listActionLinksDefaultLanguageNewsWithDefaultPathSegment(): void
    {
        $this->setUpFrontend();

        $html = $this->assertRendersWithoutError($this->renderPage(2));

        self::assertStringContainsString('/detail/rendering-test-news-one', $html);
        self::assertStringNotContainsString('translated-test-news', $html);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getSiteConfiguration(): array
    {
        $configuration = parent::getSiteConfiguration();

        $configuration['languages'][] = [
            'title' => 'German',
            'enabled' => true,
            'languageId' => self::LANGUAGE_ID,
            'base' => '/de/',
            'locale' => 'de_DE.UTF-8',
            'navigationTitle' => 'German',
            'flag' => 'de',
            // "strict" matters here: with "fallback" TYPO3 renders the
            // tt_content of the default language, Extbase never gets a
            // translated context and the news records stay unoverlaid.
            'fallbackType' => 'strict',
        ];

        // Without the enhancer the news uid ends up as a query parameter and
        // the path_segment never shows up in the URL at all.
        $configuration['routeEnhancers'] = [
            'NewsDetail' => [
                'type' => 'Extbase',
                'limitToPages' => [3],
                'extension' => 'News',
                'plugin' => 'Pi1',
                'routes' => [
                    [
                        'routePath' => '/{news-title}',
                        '_controller' => 'News::detail',
                        '_arguments' => ['news-title' => 'news'],
                    ],
                ],
                'aspects' => [
                    'news-title' => ['type' => 'NewsTitle'],
                ],
            ],
        ];

        return $configuration;
    }
}
