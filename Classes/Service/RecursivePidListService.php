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
 * Find recursive pidlist
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Service_RecursivePidListService {

	/**
	 * Find all ids from given ids and level
	 *
	 * @param string $pidlist comma seperated list of ids
	 * @param integer $recursive recursive levels
	 * @return string comma seperated list of ids
	 */
	public function find($pidlist = '', $recursive = 0) {
		if ($recursive <= 0) {
			return $pidlist;
		}

		$local_cObj = t3lib_div::makeInstance('tslib_cObj');

		$recursive = t3lib_div::intInRange($recursive, 0);

		$pid_list_arr = array_unique(t3lib_div::trimExplode(',', $pidlist, 1));
		$pid_list     = array();

		foreach ($pid_list_arr as $val) {
			$val = t3lib_div::intInRange($val, 0);
			if ($val) {
				$_list = $local_cObj->getTreeList(-1 * $val, $recursive);
				if ($_list) {
					$pid_list[] = $_list;
				}
			}
		}

		$extendedPidList = implode(',', $pid_list);
		return $extendedPidList;
	}

}
?>