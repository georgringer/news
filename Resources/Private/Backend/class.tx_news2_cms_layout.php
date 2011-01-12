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
	 * @param	mixed		$pObj	A reference to calling object
	 * @return	string		Information about pi1 plugin
	 */
	function getExtensionSummary(array $params, &$pObj) {
		$result = '';

		$this->llPath = 'LLL:EXT:' . $this->extKey . '/Resources/Private/Language/locallang_be.xml';

		if ($params['row']['list_type'] == $this->extKey . '_pi1') {
			$data = t3lib_div::xml2array($params['row']['pi_flexform']);

				// if flexform data is found
			if (is_array($data) && !empty($data['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'])) {
				$actionList = t3lib_div::trimExplode(';', $data['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF']);
				
					// translate the first action into its translation
				$actionTranslationKey = strtolower(str_replace('->', '_', $actionList[0]));
				$actionTranslation = $GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.mode.' . $actionTranslationKey);
				
				$result =
						'<strong>' .
							$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.mode', TRUE) . 
						': </strong>' . htmlspecialchars($actionTranslation) ;
						
			} else {
				$result = $GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.mode.not_configured');
			}

			$result .= '<br /><br /><table>' .
						$this->getArchiveSettings($data) .
						$this->getCategorySettings($data) .
						$this->getStartingPoint($data['data']['sDEF']['lDEF']['settings.startingpoint']['vDEF']) .
						$this->getOffsetLimitSettings($data) .
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
		$content = '';
		
		if (!is_array($data))
			return $content;

		$archive = $data['data']['sDEF']['lDEF']['settings.archive']['vDEF'];

		if (!empty($archive)) {
			$content = $this->renderLine(
							$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.archive'),
							$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.archive.' . $archive)
						);
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
				'tx_news2_domain_model_category',
				'deleted=0 AND uid IN(' . implode(',', $categories) . ')'
			);

			foreach ($rawCategoryRecords as $record) {
				$categoriesOut[] = htmlspecialchars($record['title']);
			}

			$content = $this->renderLine(
							$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.category') .
								'<br /><span style="font-weight:normal;font-style:italic">(' . htmlspecialchars($categoryMode) . ')</span>',
							implode(', ', $categoriesOut)
						);
		}

		return $content;
	}
	
	/**
	 * Render offset & limit configuration
	 * 
	 * @param array $data
	 * @return string 
	 */
	private function getOffsetLimitSettings($data) {
		$content = '';
		
		if (!is_array($data))
			return $content;

		$offset = $data['data']['additional']['lDEF']['settings.offset']['vDEF'];
		$limit = $data['data']['additional']['lDEF']['settings.limit']['vDEF'];
		
		if ($offset) {
			$content .= $this->renderLine($GLOBALS['LANG']->sL($this->llPath . ':flexforms_additional.offset'), $offset);			
		}
		if ($limit) {
			$content .= $this->renderLine($GLOBALS['LANG']->sL($this->llPath . ':flexforms_additional.limit'), $limit);
		}
		
		return $content;
	}

	/**
	 * Get the startingpoint
	 *
	 * @param string $startingpoint comma seperated list of pages
	 * @return string
	 */
	private function getStartingPoint($startingpoint) {
			// @todo: possible check through TS
		if (empty($startingpoint)) {
			return '';
		}

		$pageIds = t3lib_div::intExplode(',', $startingpoint, TRUE);

		$pagesOut = array();
		$rawPagesRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'title,uid',
			'pages',
			'deleted=0 AND uid IN(' . implode(',', $pageIds) . ')'
		);

		foreach($rawPagesRecords as $page) {
			$pagesOut[] = htmlspecialchars($page['title']) . '<small> (' . $page['uid'] . ')</small>' ;
		}

		$content = $this->renderLine(
						$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_general.php:LGL.startingpoint'), 
						implode(', ', $pagesOut)
					);
		
		return $content;
	}
	
	/**
	 * Render a configuration line with a tr/td
	 * 
	 * @param string $head
	 * @param string $content
	 * @return string rendered configuration
	 */
	private function renderLine($head, $content) {
		$content = '<tr>
						<td style="font-weight:bold;width:200px;">' . $head .	'</td>
						<td>' . $content . '</td>
					</tr>';
		
		return $content;
	}


}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Resources/Private/Backend/class.tx_news2_cms_layout.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Resources/Private/Backend/class.tx_news2_cms_layout.php']);
}

?>