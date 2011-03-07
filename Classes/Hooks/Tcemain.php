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
 * Hook into tcemain which is used to show preview of news item
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class tx_News2_Hooks_Tcemain{

	/**
	 * Generate a different preview link
	 *
	 * @param string $status status
	 * @param string $table tablename
	 * @param integer $id id of the record
	 * @param array $fieldArray fieldArray
	 * @param t3lib_TCEmain $parentObject parent Object
	 * @return void
	 */
	public function processDatamap_afterDatabaseOperations($status, $table, $id, array $fieldArray, t3lib_TCEmain $parentObject) {
		if ($table == 'tx_news2_domain_model_news') {

				// direct preview
			if (!is_numeric($id)) {
				$id = $parentObject->substNEWwithIDs[$id];
			}

			if (isset($GLOBALS['_POST']['_savedokview_x']) && !$fieldArray['type'] && !$GLOBALS['BE_USER']->workspace) {
					// if "savedokview" has been pressed and current article has "type" 0 (= normal news article)
					// and the beUser works in the LIVE workspace open current record in single view
				$pagesTsConfig = t3lib_BEfunc::getPagesTSconfig($GLOBALS['_POST']['popViewId']);
				if ($pagesTsConfig['tx_news2.']['singlePid']) {
					$GLOBALS['_POST']['popViewId_addParams'] = ($fieldArray['sys_language_uid'] > 0 ?
						'&L=' . $fieldArray['sys_language_uid'] : 0) . '&no_cache=1 ' .
						'&tx_news2_pi1[controller]=News&tx_news2_pi1[action]=detail&tx_news2_pi1[news]=' . $id;

					$GLOBALS['_POST']['popViewId'] = $pagesTsConfig['tx_news2.']['singlePid'];
				}
			}
		}
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Classes/Hooks/Tcemain.php']) {
	require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Classes/Hooks/Tcemain.php']);
}

?>