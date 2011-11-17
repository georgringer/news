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
 * Service for category related stuff
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Service_CategoryService {

	/**
	 * Get child categories by calling recursive function
	 * and using the caching framework to save some queries
	 *
	 * @param string $idList list of category ids to start
	 * @param integer $counter
	 * @param string $additionalWhere additional where clause
	 * @return string comma seperated list of category ids
	 */
	public static function getChildrenCategories($idList, $counter = 0, $additionalWhere = '') {
		$cache = t3lib_div::makeInstance('Tx_News_Service_CacheService', 'news_categorycache');
		$cacheIdentifier = sha1($idList);

		$entry = $cache->get($cacheIdentifier);
		if(!$entry) {
			$entry = self::getChildrenCategoriesRecursive($idList, $counter, $additionalWhere);
			$cache->set($cacheIdentifier, $entry);
		}
		return $entry;
	}

	/**
	 * Get child categories
	 *
	 * @param string $idList list of category ids to start
	 * @param integer $counter
	 * @param string $additionalWhere additional where clause
	 * @return string comma seperated list of category ids
	 */
	private static function getChildrenCategoriesRecursive($idList, $counter, $additionalWhere) {
		$result = array();

			// add idlist to the output too
		if ($counter === 0) {
			$result[] = $GLOBALS['TYPO3_DB']->cleanIntList($idList);
		}

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'tx_news_domain_model_category',
			'tx_news_domain_model_category.parentcategory IN (' . $GLOBALS['TYPO3_DB']->cleanIntList($idList) . ') AND deleted=0 ' . $additionalWhere);

		while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
			$counter++;
			if ($counter > 10000) {
				$GLOBALS['TT']->setTSlogMessage('EXT:news: one or more recursive categories where found');
				return implode(',', $result);
			}
			$subcategories = self::getChildrenCategoriesRecursive($row['uid'], $counter, $additionalWhere);
			$result[] = $row['uid'] . ($subcategories ? ',' . $subcategories : '');
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		$result = implode(',', $result);
		return $result;
	}

}

?>