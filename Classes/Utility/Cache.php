<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Utility;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Event\ModifyCacheTagsFromDemandEvent;
use GeorgRinger\News\Event\ModifyCacheTagsFromNewsEvent;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Cache Utility class
 */
class Cache
{
    /**
     * Stack for processed cObjs which has added news relevant cache tags.
     * @var array
     */
    protected static $processedContentRecords = [];

    /**
     * Marks as cObj as processed.
     *
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
     */
    public function markContentRecordAsProcessed(ContentObjectRenderer $cObj): void
    {
        $key = 'tt_content_' . $cObj->data['uid'];
        self::$processedContentRecords[$key] = true;
    }

    /**
     * Checks if a cObj has already added cache tags.
     *
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
     * @return bool
     */
    public function isContentRecordAlreadyProcessed(ContentObjectRenderer $cObj): bool
    {
        $key = 'tt_content_' . $cObj->data['uid'];
        return array_key_exists($key, self::$processedContentRecords);
    }

    /**
     * Adds cache tags to page cache by news-records.
     *
     * Following cache tags will be added to tsfe:
     * "tx_news_uid_[news:uid]"
     *
     * @param News[]|\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $newsRecords with news records
     */
    public static function addCacheTagsByNewsRecords($newsRecords): void
    {
        $cacheTags = [];
        foreach ($newsRecords as $news) {
            // cache tag for each news record
            $cacheTagsOfNews = ['tx_news_uid_' . $news->getUid()];
            if ($news->_getProperty('_localizedUid')) {
                $cacheTagsOfNews[] = 'tx_news_uid_' . $news->_getProperty('_localizedUid');
            }
            $event = new ModifyCacheTagsFromNewsEvent($cacheTagsOfNews, $news);
            GeneralUtility::makeInstance(EventDispatcher::class)->dispatch($event);
            foreach ($event->getCacheTags() as $cacheTag) {
                $cacheTags[] = $cacheTag;
            }
        }
        if (count($cacheTags) > 0) {
            $GLOBALS['TSFE']->addCacheTags($cacheTags);
        }
    }

    /**
     * Adds page cache tags by used storagePages.
     * This adds tags with the scheme tx_news_pid_[news:pid]
     *
     * @param \GeorgRinger\News\Domain\Model\Dto\NewsDemand $demand
     */
    public static function addPageCacheTagsByDemandObject(NewsDemand $demand): void
    {
        $cacheTags = [];
        if ($demand->getStoragePage()) {
            // Add cache tags for each storage page
            foreach (GeneralUtility::trimExplode(',', $demand->getStoragePage()) as $pageId) {
                $cacheTags[] = 'tx_news_pid_' . $pageId;
            }
        } else {
            $cacheTags[] = 'tx_news_domain_model_news';
        }
        $event = new ModifyCacheTagsFromDemandEvent($cacheTags, $demand);
        GeneralUtility::makeInstance(EventDispatcher::class)->dispatch($event);
        $cacheTags = $event->getCacheTags();
        if (count($cacheTags) > 0) {
            $GLOBALS['TSFE']->addCacheTags($cacheTags);
        }
    }
}
