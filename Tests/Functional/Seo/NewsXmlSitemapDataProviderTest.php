<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Seo;

use GeorgRinger\News\Seo\NewsXmlSitemapDataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Ensures the news sitemap honours the pagination parameter on both TYPO3 v13
 * (flat "page") and v14 (namespaced "tx_seo[page]", breaking change #104422).
 */
class NewsXmlSitemapDataProviderTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = ['seo'];
    protected array $testExtensionsToLoad = ['typo3conf/ext/news'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tx_news_sitemap.csv');
    }

    #[Test]
    public function firstPageReturnsFirstItem(): void
    {
        self::assertSame([1], $this->uidsForPage(['tx_seo' => ['page' => 0]]));
    }

    #[Test]
    public function paginationUsesTxSeoNamespaceParameter(): void
    {
        self::assertSame([2], $this->uidsForPage(['tx_seo' => ['page' => 1]]));
    }

    #[Test]
    public function paginationFallsBackToLegacyPageParameter(): void
    {
        self::assertSame([2], $this->uidsForPage(['page' => 1]));
    }

    /**
     * @param array<string, mixed> $queryParams
     * @return int[] uids of the news records on the requested sitemap page
     */
    private function uidsForPage(array $queryParams): array
    {
        $request = (new ServerRequest())->withQueryParams($queryParams);

        // One item per page so two fixture records span two sitemap pages.
        $provider = new class ($request, 'news', []) extends NewsXmlSitemapDataProvider {
            protected int $numberOfItemsPerPage = 1;

            public function getRawItems(): array
            {
                return $this->items;
            }
        };

        return array_map(
            static fn(array $item): int => (int)$item['data']['uid'],
            $provider->getRawItems()
        );
    }
}
