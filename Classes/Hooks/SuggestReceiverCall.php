<?php

class tx_News_Hooks_SuggestReceiverCall {

	/**
	 *
	 * @param array $params
	 * @param TYPO3AJAX $ajaxObj
	 */
	public function createTag(array $params, TYPO3AJAX $ajaxObj) {
		$request = t3lib_div::_POST();
		$newUid = 0;

		try {
			if (!isset($request['item']) || empty($request['item'])) {
				throw new Exception('No tag submitted.');
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
				$newUid = $record['uid'];
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

				$newUid = $tce->substNEWwithIDs['NEW'];
			}


			if ($newUid == 0) {
				throw new Exception('No new tag created.');
			}

			$ajaxObj->setContentFormat('javascript');
			$ajaxObj->setContent('');
			$ajaxObj->setJavascriptCallbackWrap($newUid . '-' . $request['item']);
		} catch (Exception $e) {
			$ajaxObj->setError($e->getMessage());
		}
	}

}

?>
