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
 * Hook to display verbose information about pi1 plugin in Web>Page module
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class tx_news2_cms_layout {
	/**
	 * Extension key
	 * @var string
	 */
	var $extKey = 'news2';

	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param	array		$params	Parameters to the hook
	 * @param	object		$pObj	A reference to calling object
	 * @return	string		Information about pi1 plugin
	 */
	function getExtensionSummary($params, &$pObj) {
		$result = '';

		$this->llPath = 'LLL:EXT:' . $this->extKey . '/Resources/Private/Language/locallang_be.xml';

		if ($params['row']['list_type'] == $this->extKey . '_pi1') {
			$data = t3lib_div::xml2array($params['row']['pi_flexform']);

				// if flexform data is found
			if (is_array($data) && !empty($data['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'])) {
				$actionList = t3lib_div::trimExplode(';', $data['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF']);
				$ll = '';

					// render a preview
				switch($actionList[0]) {
					case 'News->list':
						$ll = 'news_list';
						break;
					case 'News->latest':
						$ll = 'news_latest';
						break;
					case 'News->detail':
						$ll = 'news_detail';
						break;
					case 'News->search':
						$ll = 'news_search';
						break;
					case 'News->searchResult':
						$ll = 'news_searchResult';
						break;
					case 'Category->list':
						$ll = 'category_list';
						break;
					default:
						$ll = 'no-mode';
				}

				$result =
						'<strong>' .
							$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.mode') .
						': </strong>' .
						$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.mode.' . $ll);

			} else {
				$result = $GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.mode.not_configured');
			}

			$result .= '<br /><br /><table>' .
						$this->getArchiveSettings($data) .
						$this->getCategorySettings($data) .
						$this->getStartingPoint($data['data']['sDEF']['lDEF']['settings.startingpoint']['vDEF']) .
					 '</table>';

		}

		return $result;
	}

	/**
	 * Render archive settings
	 *
	 * @param array $data
	 * @return string
	 */
	private function getArchiveSettings($data) {
		if (!is_array($data))
			return '';

		$content = '';
		$archive = $data['data']['sDEF']['lDEF']['settings.archive']['vDEF'];

		if ($archive != '') {
			$content = '<tr>
							<td style="font-weight:bold">'.
								$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.archive') .
							'</td><td>' .
								$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.archive.' . $archive) .
							'</td>
						</tr>';
		}
		return $content;
	}


	/**
	 * Render category settings
	 *
	 * @param array $data
	 * @return string
	 */
	private function getCategorySettings($data) {
		if (!is_array($data))
			return '';

		$content = $categoryMode = '';
		$categoriesOut = array();

		$categories = t3lib_div::intExplode(',', $data['data']['sDEF']['lDEF']['settings.category']['vDEF'], TRUE);
		if (count($categories) > 0) {

				// Category mode
			$categoryModeSelection = $data['data']['sDEF']['lDEF']['settings.categoryMode']['vDEF'];

			if (empty($categoryModeSelection)) {
				$categoryMode = $GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.categoryMode.all');
			} else {
				$categoryMode = $GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.categoryMode.' . $categoryModeSelection);
			}

				// Category records
			$rawCategoryRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'title',
				'tx_news_domain_model_category',
				'deleted=0 AND uid IN(' . implode(',', $categories) . ')'
			);

			foreach ($rawCategoryRecords as $record) {
				$categoriesOut[] = htmlspecialchars($record['title']);
			}

			$content = '<tr>
							<td style="font-weight:bold;width:200px;">'.
								$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.category') .
								'<br /><span style="font-weight:normal;font-style:italic">(' . htmlspecialchars($categoryMode) . ')</span>
							</td><td>' .
								implode(', ', $categoriesOut) .
							'</td>
						</tr>';
		}

		return $content;
	}

	/**
	 * Get the startingpoint
	 *
	 * @param string $startingpoint comma seperated list of pages
	 * @return string
	 */
	public function getStartingPoint($startingpoint) {
			// @todo: possible check through TS
		if (empty($startingpoint)) {
			return '';
		}

		$pageIds = t3lib_div::trimExplode(',', $startingpoint, TRUE);

		$pagesOut = array();
		$rawPagesRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'title,uid',
			'pages',
			'deleted=0 AND uid IN(' . implode(',', $pageIds) . ')'
		);

		foreach($rawPagesRecords as $page) {
			$pagesOut[] = htmlspecialchars($page['title']) . '<small> (' . $page['uid'] . ')</small>' ;
		}

		$content = '<tr>
						<td style="font-weight:bold">' .
							$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_general.php:LGL.startingpoint') .
						'</td><td>' .
							implode(', ', $pagesOut) .
						'</td>
					</tr>';

		return $content;
	}


}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Resources/Private/Backend/class.tx_news2_cms_layout.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Resources/Private/Backend/class.tx_news2_cms_layout.php']);
}

?>