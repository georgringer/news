<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event\Listener;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Backend\Controller\Event\AfterPageTreeItemsPreparedEvent;
use TYPO3\CMS\Backend\Dto\Tree\Status\StatusInformation;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;

#[AsEventListener(
    identifier: 'ext-news/backend/modify-page-tree-items',
)]
final readonly class ModifyPageTreeItems
{
    private EmConfiguration $emConfiguration;
    private const NEWS_TYPES = [
        'news_pi1' => 'ext-news-plugin-news-list',
        'news_newsliststicky' => 'ext-news-plugin-news-list-sticky',
        'news_newsdetail' => 'ext-news-plugin-news-detail',
        'news_newsselectedlist' => 'ext-news-plugin-news-selected-list',
        'news_newsdatemenu' => 'ext-news-plugin-news-date-menu',
        'news_categorylist' => 'ext-news-plugin-category-list',
        'news_newssearchform' => 'ext-news-plugin-news-search-form',
        'news_newssearchresult' => 'ext-news-plugin-news-search-result',
        'news_taglist' => 'ext-news-plugin-tag-list',
    ];

    private const PAGES_PER_QUERY = 500;

    public function __construct()
    {
        $this->emConfiguration = GeneralUtility::makeInstance(EmConfiguration::class);
    }

    public function __invoke(AfterPageTreeItemsPreparedEvent $event): void
    {
        if (!$this->emConfiguration->getPageTreePluginPreview()) {
            return;
        }
        $items = $event->getItems();
        $pageIds = array_map(static fn(array $item): int => (int)$item['_page']['uid'], $items);
        $newsTypes = $this->getFirstFoundNewsTypes($pageIds);
        foreach ($items as $index => &$item) {
            $ctype = $newsTypes[$pageIds[$index]] ?? null;
            if ($ctype === null) {
                continue;
            }
            $pluginNameForLabel = str_replace(['ext-news-plugin-', '-'], ['', '_'], self::NEWS_TYPES[$ctype]);
            $label = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:plugin.' . $pluginNameForLabel . '.title';
            $item['statusInformation'][] = new StatusInformation(
                label: $this->getLanguageService()->sL($label),
                severity: ContextualFeedbackSeverity::INFO,
                priority: 0,
                icon: self::NEWS_TYPES[$ctype],
                overlayIcon: '',
            );
        }

        $event->setItems($items);
    }

    /**
     * @param int[] $pageIds
     * @return array<int, string> CType of the first news plugin, by page id
     */
    private function getFirstFoundNewsTypes(array $pageIds): array
    {
        $newsTypes = [];
        foreach (array_chunk(array_unique($pageIds), self::PAGES_PER_QUERY) as $chunk) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
            $rows = $queryBuilder
                ->select('pid', 'CType')
                ->from('tt_content')
                ->where(
                    $queryBuilder->expr()->in('pid', $queryBuilder->createNamedParameter($chunk, Connection::PARAM_INT_ARRAY)),
                    $queryBuilder->expr()->in('CType', $queryBuilder->createNamedParameter(array_keys(self::NEWS_TYPES), Connection::PARAM_STR_ARRAY)),
                )
                ->orderBy('colPos', 'desc')
                ->addOrderBy('sorting', 'asc')
                ->executeQuery()
                ->fetchAllAssociative();
            foreach ($rows as $row) {
                $newsTypes[(int)$row['pid']] ??= $row['CType'];
            }
        }

        return $newsTypes;
    }

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
