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
 * Add news extension to the wizard in page module
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class news_pi1_wizicon {

	const KEY = 'news';

	/**
	 * Processing the wizard items array
	 *
	 * @param array $wizardItems The wizard items
	 * @return Modified array with wizard items
	 */
	public function proc($wizardItems) {
		$locallang = $this->includeLocalLang();

		$wizardItems['plugins_tx_' . self::KEY] = array(
			'icon'			=> t3lib_extMgm::extRelPath(self::KEY) . 'Resources/Public/Icons/ce_wiz.gif',
			'title'			=> $GLOBALS['LANG']->getLLL('pi1_title', $locallang),
			'description'	=> $GLOBALS['LANG']->getLLL('pi1_plus_wiz_description', $locallang),
			'params'		=> '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=' . self::KEY . '_pi1'
		);

		return $wizardItems;
	}

	/**
	 * Reads the [extDir]/locallang_be.xml and returns
	 * the $LOCAL_LANG array found in that file.
	 *
	 * @return	The array with language labels
	 */
	protected function includeLocalLang() {
		$file = t3lib_extMgm::extPath(self::KEY) . 'Resources/Private/Language/locallang_be.xml';

		return t3lib_div::readLLXMLfile($file, $GLOBALS['LANG']->lang);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news/Resources/Private/Php/class.news_wizicon.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news/Resources/Private/Php/class.news_wizicon.php']);
}

?>