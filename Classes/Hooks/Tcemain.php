<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * Hook into tcemain which is used to show preview of news item
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Hooks_Tcemain {

	/**
	 * Flushes the cache if a news record was edited.
	 *
	 * @param array $params
	 * @return void
	 */
	public function clearCachePostProc(array $params) {
		if (isset($params['table']) && $params['table'] === 'tx_news_domain_model_news' && isset($params['uid'])) {
			$cacheTag = $params['table'] . '_' . $params['uid'];

			/** @var $cacheManager \TYPO3\CMS\Core\Cache\CacheManager */
			$cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
			$cacheManager->getCache('cache_pages')->flushByTag($cacheTag);
			$cacheManager->getCache('cache_pagesection')->flushByTag($cacheTag);
			$cacheManager->getCache('cache_pages')->flushByTag('tx_news');
			$cacheManager->getCache('cache_pagesection')->flushByTag('tx_news');
		}
	}

	/**
	 * Generate a different preview link     *
	 * @param string $status status
	 * @param string $table table name
	 * @param integer $recordUid id of the record
	 * @param array $fields fieldArray
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject parent Object
	 * @return void
	 */
	public function processDatamap_afterDatabaseOperations($status, $table, $recordUid, array $fields, \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject) {
		// Clear category cache
		if ($table === 'sys_category') {
			/** @var \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface $cache */
			$cache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('cache_news_category');
			$cache->flush();
		}

		// Preview link
		if ($table === 'tx_news_domain_model_news') {

			// direct preview
			if (!is_numeric($recordUid)) {
				$recordUid = $parentObject->substNEWwithIDs[$recordUid];
			}

			if (isset($GLOBALS['_POST']['_savedokview_x']) && !$fields['type']) {
				// If "savedokview" has been pressed and current article has "type" 0 (= normal news article)
				$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($GLOBALS['_POST']['popViewId']);
				if ($pagesTsConfig['tx_news.']['singlePid']) {
					$record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_news_domain_model_news', $recordUid);

					$parameters = array(
						'no_cache' => 1,
						'tx_news_pi1[controller]' => 'News',
						'tx_news_pi1[action]' => 'detail',
						'tx_news_pi1[news_preview]' => $record['uid'],
					);
					if ($record['sys_language_uid'] > 0) {
						if ($record['l10n_parent'] > 0) {
							$parameters['tx_news_pi1[news_preview]'] = $record['l10n_parent'];
						}
						$parameters['L'] = $record['sys_language_uid'];
					}

					$GLOBALS['_POST']['popViewId_addParams'] = \TYPO3\CMS\Core\Utility\GeneralUtility::implodeArrayForUrl('', $parameters, '', FALSE, TRUE);
					$GLOBALS['_POST']['popViewId'] = $pagesTsConfig['tx_news.']['singlePid'];
				}
			}
		}
	}

}