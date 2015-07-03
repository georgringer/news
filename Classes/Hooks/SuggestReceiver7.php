<?php

namespace GeorgRinger\News\Hooks;

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
use TYPO3\CMS\Backend\Form\Wizard\SuggestWizardDefaultReceiver;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;


/**
 * Custom suggest receiver for tags
 *
 * @author	Georg Ringer <typo3@ringerge.org>
 * @package	TYPO3
 * @subpackage	tx_news
 */
class SuggestReceiver7 extends SuggestWizardDefaultReceiver {

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
		$uid = (int)GeneralUtility::_GP('uid');
		$records = parent::queryTable($params, $recursionCounter);
		if ($this->checkIfTagIsNotFound($records)) {
			$text = GeneralUtility::quoteJSvalue($params['value']);
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
									sprintf($GLOBALS['LANG']->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:tag_suggest'), $text) .
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
		$icon = IconUtility::getIcon('tx_news_domain_model_tag');
		return IconUtility::skinImg('', $icon, '', 1);
	}

}