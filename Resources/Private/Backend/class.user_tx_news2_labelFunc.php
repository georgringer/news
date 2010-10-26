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
 * Userfunc to get alternative label
 *
 * @author	Georg Ringer <typo3@ringerge.org>
 * @package	TYPO3
 * @subpackage	tx_news2
 */
class user_tx_news2_labelFunc {

	public function getUserLabelNews(array $params) {
		$categoryTitles = $this->getCategories($params['row']['uid'], $params['row']['category']);

		$params['title'] = $params['row']['title'] . ', cat: ' . $categoryTitles;
	}


	/**
	 * Generate additional label for category records including the title of the parent category
	 *
	 * @param array $params
	 */
	public function getUserLabelCategory(array $params) {
//		$parentCategory = '';
//
//		if ($params['row']['parent_id'] > 0) {
//			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
//				'title',
//				'tx_news_domain_model_category',
//				'deleted=0 AND uid=' . (int)$params['row']['parent_id']
//			);
//
//			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
//			$parentCategory = ', ' . $row['title'];
//			$GLOBALS['TYPO3_DB']->sql_free_result($res);
//		}
//
//		$params['title'] = $params['row']['title'] . $parentCategory;

			// new version shows translation of language set in user settings
		$overlayLanguage = (int)$GLOBALS['BE_USER']->uc['news2overlay'];

			// no overlay if language of category is not base or no language yet selected
		if ($overlayLanguage == 0 && $params['row']['sys_language_uid'] > 0) {
			$params['title'] = $params['row']['title'];
		} else {
			$overlayRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'*',
				'tx_news2_domain_model_category',
				'deleted=0 AND sys_language_uid=' . $overlayLanguage . ' AND l10n_parent=' . $params['row']['uid']
			);
			if (isset($overlayRecord[0]['title'])) {
				$params['title'] = $overlayRecord[0]['title'] . ' (' . $params['row']['title'] . ')';
			} else {
				$params['title'] = $params['row']['title'];
			}
		}
	}

	/**
	 * Render different label for media elements
	 * 
	 * @param array $params configuration
	 */
	public function getUserLabelMedia(array $params) {

			// Type
		$newTitle = $GLOBALS['LANG']->sL('LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media.type.I.' . $params['row']['type']);
		$newTitle = ' (' . $newTitle . ')';

			// Preview
		if ($params['row']['showinpreview']) {
			$newTitle .= ' ' . $GLOBALS['LANG']->sL('LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media.show');
		}
		

		$params['title'] = $params['row']['title'] . $newTitle;
	}


	private function getCategories($newsUid, $catMM) {
		if ($catMM == 0) {
			return 'no cat';
		}

		$catTitles = array();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
			'tx_news_domain_model_category.title as title',
			'tx_news_domain_model_news',
			'tx_news_domain_model_news_category_mm',
			'tx_news_domain_model_category',
			' AND tx_news_domain_model_news.uid=' . (int)$newsUid
		);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$catTitles[] = $row['title'];
		}

		return implode(', ', $catTitles);

//		$records =

		return 'asiasp';
	}

}

?>