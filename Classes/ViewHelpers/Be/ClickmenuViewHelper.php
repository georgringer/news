<?php

namespace GeorgRinger\News\ViewHelpers\Be;

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
class ClickmenuViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

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
		$onClick = 'Clickmenu.show("' . $table . '","' . $uid . '","' . $listFr . '","' . str_replace('+', '%2B', $enDisItems) . '","' . str_replace('&', '&amp;', addcslashes($backPath, '"')) . '","' . str_replace('&', '&amp;', addcslashes($addParams, '"')) . '");return false;';
		return $returnOnClick ? $onClick : '<a href="#" onclick="' . htmlspecialchars($onClick) . '" oncontextmenu="' . htmlspecialchars($onClick) . '">' . $str . '</a>';
	}
}
