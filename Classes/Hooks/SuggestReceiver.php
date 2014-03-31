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
 * Custom suggest receiver for tags
 *
 * @author	Georg Ringer <typo3@ringerge.org>
 * @package	TYPO3
 * @subpackage	tx_news
 */
class Tx_News_Hooks_SuggestReceiver extends \TYPO3\CMS\Backend\Form\Element\SuggestDefaultReceiver{

	/**
	 * Queries a table for records and completely processes them
	 *
	 * Returns a two-dimensional array of almost finished records;
	 * they only need to be put into a <li>-structure
	 *
	 * @param array $params
	 * @param integer $recursionCounter recursion counter
	 * @return mixed array of rows or FALSE if nothing found
	 */
	public function queryTable(&$params, $recursionCounter = 0) {
		$uid = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('uid');

		$records = parent::queryTable($params, $recursionCounter);

		if ($this->checkIfTagIsNotFound($records)) {
			$text = htmlspecialchars($params['value']);
$javaScriptCode = '
var value=\'' . $text . '\';

Ext.Ajax.request({
	url : \'ajax.php\' ,
	params : { ajaxID : \'News::createTag\', item:value,newsid:\'' . $uid . '\' },
	success: function ( result, request ) {
		var arr = result.responseText.split(\'-\');
		setFormValueFromBrowseWin(arr[5], arr[2] +  \'_\' + arr[0], arr[1]);
		TBE_EDITOR.fieldChanged(arr[3], arr[6], arr[4], arr[5]);
	},
	failure: function ( result, request) {
		Ext.MessageBox.alert(\'Failed\', result.responseText);
	}
});
';

$javaScriptCode = trim(str_replace('"', '\'', $javaScriptCode));
$link = implode(' ', explode(chr(10), $javaScriptCode));

			$records['tx_news_domain_model_tag_' . strlen($text)] = array (
				'text' => '<div onclick="' . $link . '">
							<span class="suggest-path">
								<a>' .
									sprintf($GLOBALS['LANG']->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xml:tag_suggest'), $text) .
								'</a>
							</span></div>',
				'table' => 'tx_news_domain_model_tag',
				'class' => 'suggest-noresults',
				'style' => 'background-color:#E9F1FE !important;background-image:url(' . $this->getDummyIconPath() . ');',
			);
		}

		return $records;
	}

	/**
	 * Check if current tag is found.
	 *
	 * @param array $tags returned tags
	 * @return boolean
	 */
	protected function checkIfTagIsNotFound(array $tags) {
		if (count($tags) === 0) {
			return TRUE;
		}

		foreach ($tags as $tag) {
			if ($tag['label'] === $this->params['value']) {
				return FALSE;
			}
		}

		return TRUE;
	}

	private function getDummyIconPath() {
		$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getIcon('tx_news_domain_model_tag');
		return \TYPO3\CMS\Backend\Utility\IconUtility::skinImg('', $icon, '', 1);
	}

}
