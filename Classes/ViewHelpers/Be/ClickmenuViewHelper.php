<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2011 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * ViewHelper to create a clickmenu
 *
 * # Example: Basic example
 * <code>
 * <n:be.clickmenu table="tx_news_domain_model_news" uid="{newsItem.uid}">
 *	<n:be.buttons.iconForRecord table="tx_news_domain_model_news" uid="{newsItem.uid}" title="" />
 * </n:be.clickmenu>
 * </code>
 * <output>
 * Linked icon (<n:be.button.iconForRecord /> with a click menu for the given record (table + uid)
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Be_ClickmenuViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render the clickmenu
	 *
	 * @param string $table Name of the table
	 * @param integer $uid uid of the record
	 * @return string
	 */
	public function render($table, $uid) {
		return $this->wrapClickMenuOnIcon($this->renderChildren(), $table, $uid);
	}

	/**
	 * @param $str
	 * @param $table
	 * @param string $uid
	 * @return string
	 */
	private function wrapClickMenuOnIcon($str, $table, $uid = '') {
		$listFr = 1;
		$addParams = '';
		$enDisItems = '';
		$returnOnClick = FALSE;

		$backPath = rawurlencode($GLOBALS['BACK_PATH']) . '|' . \TYPO3\CMS\Core\Utility\GeneralUtility::shortMD5($GLOBALS['BACK_PATH'] . '|' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']);
		$onClick = 'showClickmenu("' . $table . '","' . $uid . '","' . $listFr . '","' . str_replace('+', '%2B', $enDisItems) . '","' . str_replace('&', '&amp;', addcslashes($backPath, '"')) . '","' . str_replace('&', '&amp;', addcslashes($addParams, '"')) . '");return false;';
		return $returnOnClick ? $onClick : '<a href="#" onclick="' . htmlspecialchars($onClick) . '" oncontextmenu="' . htmlspecialchars($onClick) . '">' . $str . '</a>';
	}
}
