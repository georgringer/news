<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Cache Utility class
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Lars Hess <larshess@gmail.com>
 */
class Tx_News_Utility_Cache {

	/**
	 * Stack for processed cObjs which has added news relevant cache tags.
	 * @var array
	 */
	protected static $processedContentRecords = array();

	/**
	 * Marks as cObj as processed.
	 *
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
	 * @return void
	 */
	public function markContentRecordAsProcessed(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj) {
		$key = 'tt_content_' . $cObj->data['uid'];
		self::$processedContentRecords[$key] = TRUE;
	}

	/**
	 * Checks if a cObj has already added cache tags.
	 *
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
	 * @return boolean
	 */
	public function isContentRecordAlreadyProcessed(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj) {
		$key = 'tt_content_' . $cObj->data['uid'];
		return array_key_exists($key, self::$processedContentRecords);
	}

	/**
	 * Adds cache tags to page cache by news-records.
	 *
	 * Following cache tags will be added to tsfe:
	 * "tx_news_domain_model_news_[news:uid]"
	 * "pageId_[news:pid]"
	 *
	 * @param array $newsRecords array with news records
	 * @param boolean $includePageIdTag
	 * @return void
	 */
	public static function addCacheTagsByNewsRecords(array $newsRecords, $includePageIdTag = FALSE) {
		$cacheTags = array();
		$addedPageIdTags = array();
		foreach ($newsRecords as $news) {
			// cache tag for each news record
			$cacheTags[] = 'tx_news_domain_model_news_' . $news->getUid();

			if ($includePageIdTag && !in_array($news->getPid(), $addedPageIdTags)) {
				// cache tag for each parent page record
				$cacheTags[] = 'pageId_' . $news->getPid();
				$addedPageIdTags[] = $news->getPid();
			}
		}
//var_dump($cacheTags);
		if (count($cacheTags) > 0) {
			$GLOBALS['TSFE']->addCacheTags($cacheTags);
		}
	}

	/**
	 * Adds page cache tags by used storagePages.
	 *
	 * Returns true if at least one tag was added.
	 *
	 * @param Tx_News_Domain_Model_Dto_NewsDemand $demand
	 * @return boolean
	 */
	public static function addPageCacheTagsByDemandObject(Tx_News_Domain_Model_Dto_NewsDemand $demand) {
		$cacheTags = array();

		// Add cache tags for each storage page
		if ($demand->getStoragePage()) {
			foreach (\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $demand->getStoragePage()) as $pageId) {
				$cacheTags[] = 'pageId_' . $pageId;
			}
			$GLOBALS['TSFE']->addCacheTags($cacheTags);
		}

		return count($cacheTags) > 0;
	}
}
