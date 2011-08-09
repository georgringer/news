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
 * @subpackage	tx_news
 */
class Tx_News_Hooks_Labels {

	/**
	 * Labels of a news record
	 *
	 * @param array $params
	 * @return void
	 */
	public function getUserLabelNews(array $params) {
		$params['title'] = $params['row']['title'];
	}


	/**
	 * Generate additional label for category records
	 * including the title of the parent category
	 *
	 * @param array $params
	 * @return void
	 */
	public function getUserLabelCategory(array $params) {
			// new version shows translation of language set in user settings
		$overlayLanguage = (int)$GLOBALS['BE_USER']->uc['newsoverlay'];

			// in list view: show normal label
		$listView = t3lib_div::isFirstPartOfStr(t3lib_div::getIndpEnv('REQUEST_URI'), '/typo3/sysext/list/mod1/db_list.php');

			// no overlay if language of category is not base or no language yet selected
		if ((int)$params['row']['uid'] == 0 || $listView || ($overlayLanguage == 0 && $params['row']['sys_language_uid'] > 0)) {
			$params['title'] = $params['row']['title'];
		} else {
			$overlayRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'*',
				'tx_news_domain_model_category',
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
	 * @return void
	 */
	public function getUserLabelMedia(array $params) {
		$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xml:';
		$title = $typeInfo = '';

		$type = $GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.type.I.' . $params['row']['type']);

			// Add additional info based on type
		switch ($params['row']['type']) {
				// Audio & Video
			case 1:
				$typeInfo .= $params['row']['multimedia'];
				break;
				// HTML
			case 2:
					// don't show html value as this could get a XSS
				$typeInfo .= $params['row']['caption'];
				break;
				// DAM
			case 3:
				$config = $GLOBALS['TCA'][$params['table']]['columns']['dam']['config'];
				$damItems = tx_dam_db::getReferencedFiles($params['table'], $params['row']['uid'], $config['MM_match_fields'], $config['MM'], 'tx_dam.*');
				if (is_array($damItems['rows']) {
					$item = array_shift($damItems['rows']);
					$typeInfo = (!empty($item['title']) ? $item['title'] : $item['file_name']);
				}
				break;
			default:
				$typeInfo .= $params['row']['caption'];
		}

		$title = (!empty($typeInfo)) ? $type . ': ' . $typeInfo : $type;

			// Preview
		if ($params['row']['showinpreview']) {
			$label = htmlspecialchars($GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.show'));
			$icon = '../' . t3lib_extMgm::siteRelPath('news') . 'Resources/Public/Icons/preview.gif';
			$title .= ' <img title="' . $label . '" src="' . $icon . '" />';
		}

			// Show the [No title] if empty
		if (empty($title)) {
			$title =  t3lib_befunc::getNoRecordTitle(TRUE);
		}

		$params['title'] = $title;
	}


	/**
	 * Get news categories based on the news id
	 *
	 * @param integer $newsUid
	 * @param integer $catMm
	 * @return string list of categories
	 */
	protected function getCategories($newsUid, $catMm) {
		if ($catMm == 0) {
			return '';
		}

		$catTitles = array();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
			'tx_news_domain_model_category.title as title',
			'tx_news_domain_model_news',
			'tx_news_domain_model_news_category_mm',
			'tx_news_domain_model_category',
			' AND tx_news_domain_model_news.uid=' . (int)$newsUid
		);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$catTitles[] = $row['title'];
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return implode(', ', $catTitles);
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news/Classes/Hooks/Labels.php']) {
	require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news/Classes/Hooks/Labels.php']);
}

?>