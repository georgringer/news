<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
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
 * Find recursive categories
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Service_RecursiveCategoryListService {
	
	/**
	 * Find all ids from given ids and level
	 * 
	 * @param string $pidlist comma seperated list of ids
	 * @param integer $recursive recursive levels
	 * @return string comma seperated list of ids
	 */
	public function find($parentId, $level = 99, array $out = array()) {
		$out[$parentId] = $parentId;
		
		$out = self::recursiveCall($parentId, $level, $out);
		
		return implode(',', $out);
	}
	
	public function recursiveCall($parentId, $level, $out) {
		if ($level == 0) {
			return $out;
		}
		// @todo hardcoded sys_language_uid is not a good idea
		// @todo enableFields? 
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid,title,parentcategory',
			'tx_news2_domain_model_category',
			'sys_language_uid = 0 AND deleted=0 AND hidden=0 AND parentcategory=' . $parentId
		);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$uid = $row['uid'];

			$out[$uid] = $uid;
			$out = self::recursiveCall($uid, $level--, $out);	
		}

		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $out;		
	}
	
}
?>