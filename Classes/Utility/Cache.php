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
use TYPO3\CMS\Core\Cache\CacheTag;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

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
     */
    public function markContentRecordAsProcessed(ContentObjectRenderer $cObj): void
    {
        $key = 'tt_content_' . $cObj->data['uid'];
        self::$processedContentRecords[$key] = true;
    }

    /**
     * Checks if a cObj has already added cache tags.
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
     * @param News[]|QueryResult $newsRecords with news records
     */
    public static function addCacheTagsByNewsRecords($newsRecords): void
    {
        $cacheTags = [];
        $cacheDataCollector = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector');
        foreach ($newsRecords as $news) {
            // cache tag for each news record
            $cacheTagsOfNews = ['tx_news_uid_' . $news->getUid()];
            if ($news->_getProperty('_localizedUid')) {
                $cacheTagsOfNews[] = 'tx_news_uid_' . $news->_getProperty('_localizedUid');
            }
            $event = new ModifyCacheTagsFromNewsEvent($cacheTagsOfNews, $news);
            GeneralUtility::makeInstance(EventDispatcher::class)->dispatch($event);
            foreach ($event->getCacheTags() as $cacheTag) {
                $cacheDataCollector->addCacheTags(new CacheTag($cacheTag));
            }
        }
    }

    /**
     * Adds page cache tags by used storagePages.
     * This adds tags with the scheme tx_news_pid_[news:pid]
     */
    public static function addPageCacheTagsByDemandObject(NewsDemand $demand): void
    {
        $cacheTags = [];
        $cacheDataCollector = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector');
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
        foreach ($event->getCacheTags() as $cacheTag) {
            $cacheDataCollector->addCacheTags(new CacheTag($cacheTag));
        }
    }
}
