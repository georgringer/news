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
use TYPO3\CMS\Core\Information\Typo3Version;
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
        foreach ($newsRecords as $news) {
            // cache tag for each news record
            $cacheTagsOfNews = ['tx_news_uid_' . $news->getUid()];
            if ($news->_getProperty('_localizedUid')) {
                $cacheTagsOfNews[] = 'tx_news_uid_' . $news->_getProperty('_localizedUid');
            }
            $event = new ModifyCacheTagsFromNewsEvent($cacheTagsOfNews, $news);
            GeneralUtility::makeInstance(EventDispatcher::class)->dispatch($event);
            foreach ($event->getCacheTags() as $cacheTag) {
                $cacheTags[$cacheTag] = $news->getCacheLifetime();
            }
        }
        self::addCacheTags($cacheTags);
    }

    /**
     * @param list<News> $newsRecords
     * Adds page cache tags by used storagePages.
     * This adds tags with the scheme tx_news_pid_[news:pid]
     */
    public static function addPageCacheTagsByDemandObject(NewsDemand $demand, array $newsRecords = []): void
    {
        $cacheTags = [];

        if ($demand->getStoragePage()) {
            // Add cache tags for each storage page
            foreach (GeneralUtility::intExplode(',', $demand->getStoragePage()) as $pageId) {
                $cacheLifetime = PHP_INT_MAX;
                foreach ($newsRecords as $newsRecord) {
                    if ($newsRecord->getPid() === $pageId) {
                        $cacheLifetime = min($cacheLifetime, $newsRecord->getCacheLifetime());
                    }
                }
                $cacheTags['tx_news_pid_' . $pageId] = $cacheLifetime;
            }
        } else {
            $cacheLifetime = PHP_INT_MAX;
            foreach ($newsRecords as $newsRecord) {
                $cacheLifetime = min($cacheLifetime, $newsRecord->getCacheLifetime());
            }
            $cacheTags['tx_news_domain_model_news'] = $cacheLifetime;
        }
        $event = new ModifyCacheTagsFromDemandEvent($cacheTags, $demand);
        GeneralUtility::makeInstance(EventDispatcher::class)->dispatch($event);
        self::addCacheTags($event->getCacheTags());
    }

    protected static function addCacheTags(array $cacheTags): void
    {
        if (!$cacheTags) {
            return;
        }

        if ((new Typo3Version())->getMajorVersion() >= 13) {
            $cacheDataCollector = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector');
            foreach ($cacheTags as $cacheTag => $cacheLifetime) {
                $cacheDataCollector->addCacheTags(new CacheTag($cacheTag, $cacheLifetime));
            }
        } else {
            $GLOBALS['TSFE']->addCacheTags(array_keys($cacheTags));
        }
    }
}
