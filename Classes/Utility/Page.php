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
 * Page Utitlity class
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News_Utility_Page {

	/**
	 * Find all ids from given ids and level
	 *
	 * @param string $pidList comma seperated list of ids
	 * @param integer $recursive recursive levels
	 * @return string comma seperated list of ids
	 */
	public static function extendPidListByChildren($pidList = '', $recursive = 0) {
		if ($recursive <= 0) {
			return $pidList;
		}

        /** @var $cObj tslib_cObj */
		$cObj = t3lib_div::makeInstance('tslib_cObj');

		$recursive = Tx_News_Utility_Compatibility::forceIntegerInRange($recursive, 0);

		$pidList = array_unique(t3lib_div::trimExplode(',', $pidList, 1));

		$result = array();

		foreach ($pidList as $pid) {
			$pid = Tx_News_Utility_Compatibility::forceIntegerInRange($pid, 0);
			if ($pid) {
				$children = $cObj->getTreeList(-1 * $pid, $recursive);
				if ($children) {
					$result[] = $children;
				}
			}
		}

		return implode(',', $result);
	}

	/**
	 * Set properties of an object/array in cobj->LOAD_REGISTER which can then
	 * be used to be loaded via TS with register:name
	 *
	 * @param string $properties comma seperated list of properties
	 * @param mixed $object object or array to get the properties
	 * @param string $prefix optional prefix
	 * @return void
	 */
	public static function setRegisterProperties($properties, $object, $prefix = 'news') {
		if (!empty($properties) && !is_null(($object))) {
			$cObj = t3lib_div::makeInstance('tslib_cObj');
			$items = t3lib_div::trimExplode(',', $properties, TRUE);

			$register = array();
			foreach ($items as $item) {
				$key = $prefix . ucfirst($item);
				try {
					$register[$key] = Tx_Extbase_Reflection_ObjectAccess::getProperty($object, $item);
				} catch (Exception $e) {
					t3lib_div::devLog($e->getMessage(), 'news', t3lib_div::SYSLOG_SEVERITY_WARNING);
				}
			}
			$cObj->LOAD_REGISTER($register, '');
		}
	}
}
?>