<?php

namespace GeorgRinger\News\Utility;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
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
     *
     * @return void
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
     * @param array|\TYPO3\CMS\Extbase\Persistence\QueryResult $newsRecords with news records
     *
     * @return void
     */
    public static function addCacheTagsByNewsRecords($newsRecords): void
    {
        $cacheTags = [];
        foreach ($newsRecords as $news) {
            // cache tag for each news record
            $cacheTags[] = 'tx_news_uid_' . $news->getUid();

            if ($news->_getProperty('_localizedUid')) {
                $cacheTags[] = 'tx_news_uid_' . $news->_getProperty('_localizedUid');
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
     *
     * @return void
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
        if (count($cacheTags) > 0) {
            $GLOBALS['TSFE']->addCacheTags($cacheTags);
        }
    }
}
