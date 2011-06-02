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
 * Ajax response for the custom suggest receiver
 *
 * @author	Georg Ringer <typo3@ringerge.org>
 * @package	TYPO3
 * @subpackage	tx_news
 */
class Tx_News_Hooks_SuggestReceiverCall {

	/**
	 * Create a tag
	 *
	 * @param array $params
	 * @param TYPO3AJAX $ajaxObj
	 * @return void
	 */
	public function createTag(array $params, TYPO3AJAX $ajaxObj) {
		$request = t3lib_div::_POST();
		$newTagId = 0;

		try {
				// check if a tag is submitted
			if (!isset($request['item']) || empty($request['item'])) {
				throw new Exception('No tag submitted.');
			}

			$newsUid = $request['newsid'];
			if ((int)$newsUid === 0 && (strlen($request['newsid']) == 16 && !t3lib_div::isFirstPartOfStr($request['newsid'], 'NEW'))) {
				throw new Exception('No news id given');
			}

				// get pid
			$configurationArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news']);
			if (!is_array($configurationArray) || !isset($configurationArray['tagPid'])) {
				throw new Exception('No pid for the tag record could be find by reading settings of Extension Manager!');
			}

			$record = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
						'*',
						'tx_news_domain_model_tag',
						'deleted=0 AND pid=' . (int)$configurationArray['tagPid'] . ' AND title=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($request['item'], 'tx_news_domain_model_tag')
						);
			if(isset($record['uid'])) {
				$newTagId = $record['uid'];
			} else {
				$tcemainData = array(
					'tx_news_domain_model_tag' => array(
						'NEW' => array(
							'pid' => (int)$configurationArray['tagPid'],
							'title' => $request['item']
						)
					)
				);
				$tce = t3lib_div::makeInstance('t3lib_TCEmain');
				$tce->start($tcemainData, array());
				$tce->process_datamap();

				$newTagId = $tce->substNEWwithIDs['NEW'];
			}


			if ($newTagId == 0) {
				throw new Exception('No new tag created.');
			}

			$ajaxObj->setContentFormat('javascript');
			$ajaxObj->setContent('');
			$response = array(
				$newTagId,
				$request['item'],
				'tx_news_domain_model_tag',
				'tx_news_domain_model_news',
				'tags',
				'data[tx_news_domain_model_news][' . $newsUid . '][tags]',
				$newsUid
			);
			$ajaxObj->setJavascriptCallbackWrap(implode('-', $response));
		} catch (Exception $e) {
			$ajaxObj->setError($e->getMessage());
		}
	}

}

?>