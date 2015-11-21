<?php

namespace GeorgRinger\News\Utility;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Cache Utility class
 *
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
     * @return void
     */
    public function markContentRecordAsProcessed(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj)
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
    public function isContentRecordAlreadyProcessed(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj)
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
     * @param array $newsRecords array with news records
     * @return void
     */
    public static function addCacheTagsByNewsRecords(array $newsRecords)
    {
        $cacheTags = [];
        foreach ($newsRecords as $news) {
            // cache tag for each news record
            $cacheTags[] = 'tx_news_uid_' . $news->getUid();
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
     * @return void
     */
    public static function addPageCacheTagsByDemandObject(\GeorgRinger\News\Domain\Model\Dto\NewsDemand $demand)
    {
        $cacheTags = [];
        if ($demand->getStoragePage()) {
            // Add cache tags for each storage page
            foreach (GeneralUtility::trimExplode(',', $demand->getStoragePage()) as $pageId) {
                $cacheTags[] = 'tx_news_pid_' . $pageId;
            }
        }
        if (count($cacheTags) > 0) {
            $GLOBALS['TSFE']->addCacheTags($cacheTags);
        }
    }
}
