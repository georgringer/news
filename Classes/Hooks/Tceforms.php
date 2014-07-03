<?php
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

/**
 * Hook into tceforms
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Hooks_Tceforms {

	/**
	 * Preprocessing of fields
	 *
	 * @param string $table table name
	 * @param string $field field name
	 * @param array $row record row
	 * @return void
	 */
	public function getSingleField_preProcess($table, $field, array &$row) {
		if ($table !== 'tx_news_domain_model_news') {
			return;
		}

		// Set current time for new records
		if (substr($row['uid'], 0, 3) === 'NEW') {
			$row['datetime'] = $GLOBALS['EXEC_TIME'];
		}

		// Predefine archive date
		if (empty($row['archive']) && is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);
			if (is_array($pagesTsConfig['tx_news.']['predefine.'])
					&& is_array($pagesTsConfig['tx_news.']['predefine.'])
					&& isset($pagesTsConfig['tx_news.']['predefine.']['archive'])) {
				$calculatedTime = strtotime($pagesTsConfig['tx_news.']['predefine.']['archive']);

				if ($calculatedTime !== FALSE) {
					$row['archive'] = $calculatedTime;
				}
			}
		}
	}

}