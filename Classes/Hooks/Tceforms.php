<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Georg Ringer <typo3@ringerge.org>
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
			// Predefine the archive date
		if ($table === 'tx_news_domain_model_news' && empty($row['archive']) && is_numeric($row['pid'])) {
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