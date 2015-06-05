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
use GeorgRinger\News\Utility\EmConfiguration;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Ajax response for the custom suggest receiver
 *
 * @author	Georg Ringer <typo3@ringerge.org>
 * @package	TYPO3
 * @subpackage	tx_news
 */
class SuggestReceiverCall {

	const TAG = 'tx_news_domain_model_tag';
	const NEWS = 'tx_news_domain_model_news';
	const LLPATH = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:tag_suggest_';

	/**
	 * Create a tag
	 *
	 * @param array $params
	 * @param \TYPO3\CMS\Core\Http\AjaxRequestHandler $ajaxObj
	 * @return void
	 * @throws \Exception
	 */
	public function createTag(array $params, \TYPO3\CMS\Core\Http\AjaxRequestHandler $ajaxObj) {
		$request = GeneralUtility::_POST();

		try {
				// Check if a tag is submitted
			if (!isset($request['item']) || empty($request['item'])) {
				throw new \Exception('error_no-tag');
			}

			$newsUid = $request['newsid'];
			if ((int)$newsUid === 0 && (strlen($newsUid) == 16 && !GeneralUtility::isFirstPartOfStr($newsUid, 'NEW'))) {
				throw new \Exception('error_no-newsid');
			}

				// Get tag uid
			$newTagId = $this->getTagUid($request);

			$ajaxObj->setContentFormat('javascript');
			$ajaxObj->setContent('');
			$response = array(
				$newTagId,
				$request['item'],
				self::TAG,
				self::NEWS,
				'tags',
				'data[tx_news_domain_model_news][' . $newsUid . '][tags]',
				$newsUid
			);
			$ajaxObj->setJavascriptCallbackWrap(implode('-', $response));
		} catch (\Exception $e) {
			$errorMsg = $GLOBALS['LANG']->sL(self::LLPATH . $e->getMessage());
			$ajaxObj->setError($errorMsg);
		}
	}

	/**
	 * Get the uid of the tag, either bei inserting as new or get existing
	 *
	 * @param array $request ajax request
	 * @return integer
	 * @throws \Exception
	 */
	protected function getTagUid(array $request) {
			// Get configuration from EM
		$configuration = EmConfiguration::getSettings();

		$pid = $configuration->getTagPid();
		if ($pid === 0) {
			$pid = $this->getTagPidFromTsConfig($request['newsid']);
		}

		if ($pid === 0) {
			throw new \Exception('error_no-pid-defined');
		}

		$record = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
					'*',
					self::TAG,
					'deleted=0 AND pid=' . $pid .
						' AND title=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($request['item'], self::TAG)
					);
		if (isset($record['uid'])) {
			$tagUid = $record['uid'];
		} else {
			$tcemainData = array(
				self::TAG => array(
					'NEW' => array(
						'pid' => $pid,
						'title' => $request['item']
					)
				)
			);

			/** @var \TYPO3\CMS\Core\DataHandling\DataHandler $tce */
			$tce = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\DataHandling\\DataHandler');
			$tce->start($tcemainData, array());
			$tce->process_datamap();

			$tagUid = $tce->substNEWwithIDs['NEW'];
		}

		if ($tagUid == 0) {
			throw new \Exception('error_no-tag-created');
		}

		return $tagUid;
	}

	/**
	 * Get pid for tags from TsConfig
	 *
	 * @param integer $newsUid uid of current news record
	 * @return int
	 */
	protected function getTagPidFromTsConfig($newsUid) {
		$pid = 0;

		$newsRecord = BackendUtilityCore::getRecord('tx_news_domain_model_news', (int)$newsUid);

		$pagesTsConfig = BackendUtilityCore::getPagesTSconfig($newsRecord['pid']);
		if (isset($pagesTsConfig['tx_news.']) && isset($pagesTsConfig['tx_news.']['tagPid'])) {
			$pid = (int)$pagesTsConfig['tx_news.']['tagPid'];
		}

		return $pid;
	}

}
