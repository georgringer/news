<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Event\Listener;

use GeorgRinger\News\Event\Listener\ModifyPageTreeItems;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Backend\Controller\Event\AfterPageTreeItemsPreparedEvent;
use TYPO3\CMS\Backend\Dto\Tree\Status\StatusInformation;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ModifyPageTreeItemsTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/news'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tt_content_news_plugins.csv');
        $GLOBALS['LANG'] = $this->get(LanguageServiceFactory::class)->create('default');
    }

    #[Test]
    public function addsIconOfFirstVisibleNewsPluginPerPage(): void
    {
        self::assertSame(
            [
                0 => [],
                10 => ['ext-news-plugin-news-detail'],
                11 => ['ext-news-plugin-tag-list'],
                12 => [],
                13 => [],
                14 => [],
                15 => [],
                16 => ['ext-news-plugin-news-list'],
                17 => [],
                18 => [],
            ],
            $this->iconsByPage([0, 10, 11, 12, 13, 14, 15, 16, 17, 18, 10])
        );
    }

    #[Test]
    public function findsNewsPluginsBeyondFirstChunkOfPages(): void
    {
        $icons = $this->iconsByPage(range(1, 600));

        self::assertSame(['ext-news-plugin-news-date-menu'], $icons[600]);
        self::assertSame(['ext-news-plugin-news-detail'], $icons[10]);
    }

    /**
     * @param int[] $pageIds
     * @return array<int, string[]>
     */
    private function iconsByPage(array $pageIds): array
    {
        $items = array_map(static fn(int $pageId): array => ['_page' => ['uid' => $pageId]], $pageIds);
        $request = new ServerRequest('https://example.com/typo3/');
        $event = (new Typo3Version())->getMajorVersion() >= 14
            ? new AfterPageTreeItemsPreparedEvent($request, null, $items)
            : new AfterPageTreeItemsPreparedEvent($request, $items);

        (new ModifyPageTreeItems())($event);

        $icons = [];
        foreach ($event->getItems() as $item) {
            $icons[$item['_page']['uid']] = array_map(
                static fn(StatusInformation $statusInformation): string => $statusInformation->icon,
                $item['statusInformation'] ?? []
            );
        }
        return $icons;
    }
}
