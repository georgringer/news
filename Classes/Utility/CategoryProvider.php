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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Provides the allowed categories of a user
 * by checking the access rules of the user and the user group records
 */
class Tx_News_Utility_CategoryProvider {

	/**
	 * Get a comma separated list of allowed categories
	 *
	 * @return string
	 */
	public static function getUserMounts() {
		$categoryMounts = '';

		// Mounts of the groups
		if (is_array($GLOBALS['BE_USER']->userGroups)) {
			foreach ($GLOBALS['BE_USER']->userGroups as $group) {
				if ($group['tx_news_categorymounts']) {
					$categoryMounts .=  ',' . $group['tx_news_categorymounts'];
				}
			}
		}

		// Mounts of the user record
		if ($GLOBALS['BE_USER']->user['tx_news_categorymounts']) {
			$categoryMounts .= ',' . $GLOBALS['BE_USER']->user['tx_news_categorymounts'];
		}

		// Make the ids unique
		$categoryMounts = explode(',', trim($categoryMounts, ','));
		$categoryMounts = array_keys(array_count_values($categoryMounts));
		$categoryMounts = implode(',', $categoryMounts);

		return $categoryMounts;
	}



}

?>