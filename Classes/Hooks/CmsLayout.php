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
 */
class tx_News2_Hooks_CmsLayout {
	/**
	 * Extension key
	 * @var string
	 */
	protected $extKey = 'news2';

	/**
	 * Path to the locallang file
	 * @var string
	 */
	protected $llPath;

	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param array $params Parameters to the hook
	 * @param mixed $pObj A reference to calling object
	 * @return string Information about pi1 plugin
	 */
	public function getExtensionSummary(array $params, $pObj) {
		$result = '';

		$this->llPath = 'LLL:EXT:' . $this->extKey . '/Resources/Private/Language/locallang_be.xml';

		if ($params['row']['list_type'] == $this->extKey . '_pi1') {
			$data = t3lib_div::xml2array($params['row']['pi_flexform']);

				// if flexform data is found
			$actions = $this->getFieldFromFlexform($data, 'switchableControllerActions');
			if (!empty($actions)) {
				$actionList = t3lib_div::trimExplode(';', $actions);

					// translate the first action into its translation
				$actionTranslationKey = strtolower(str_replace('->', '_', $actionList[0]));
				$actionTranslation = $GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.mode.' . $actionTranslationKey);

				$result =
						'<strong>' .
							$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.mode', TRUE) .
						': </strong>' . htmlspecialchars($actionTranslation);

			} else {
				$result = $GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.mode.not_configured');
			}

			if (is_array($data)) {
				$result .= '<br /><br /><table>' .
							$this->getDateMenuSettings($data, $actionTranslationKey) .
							$this->getArchiveSettings($data) .
							$this->getCategorySettings($data) .
							$this->getStartingPoint($data['data']['sDEF']['lDEF']['settings.startingpoint']['vDEF']) .
							$this->getOffsetLimitSettings($data) .
							$this->getOverrideDemandSettings($data) .
						'</table>';
			}
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

		if (!is_array($data)) {
			return $content;
		}

		$archive = $this->getFieldFromFlexform($data, 'settings.archiveRestriction');

		if (!empty($archive)) {
			$content = $this->renderLine(
							$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.archiveRestriction'),
							$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.archiveRestriction.' . $archive)
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
		if (!is_array($data)) {
			return '';
		}

		$content = $categoryMode = '';
		$categoriesOut = array();

		$categories = t3lib_div::intExplode(',', $this->getFieldFromFlexform($data, 'settings.categories'), TRUE);
		if (count($categories) > 0) {

				// Category mode
			$categoryModeSelection = $this->getFieldFromFlexform($data, 'settings.categoryConjunction');

			if (empty($categoryModeSelection)) {
				$categoryMode = $GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.categoryConjunction.all');
			} else {
				$categoryMode = $GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.categoryConjunction.' . $categoryModeSelection);
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
							$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.categories') .
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

		if (!is_array($data)) {
			return $content;
		}

		$offset = $this->getFieldFromFlexform($data, 'settings.offset', 'additional');
		$limit = $this->getFieldFromFlexform($data, 'settings.limit', 'additional');

		if ($offset) {
			$content .= $this->renderLine($GLOBALS['LANG']->sL($this->llPath . ':flexforms_additional.offset'), $offset);
		}
		if ($limit) {
			$content .= $this->renderLine($GLOBALS['LANG']->sL($this->llPath . ':flexforms_additional.limit'), $limit);
		}

		return $content;
	}

	/**
	 * Render datemenu configuration
	 *
	 * @param array $data flexform data
	 * @param string $actionKey current action
	 * @return string
	 */
	private function getDateMenuSettings($data, $actionKey) {
		$content = '';

		if (!is_array($data) || $actionKey !== 'news_datemenu') {
			return $content;
		}

		$dateMenuField = $this->getFieldFromFlexform($data, 'settings.dateField');

		$content .= $this->renderLine(
						$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.dateField'),
						$GLOBALS['LANG']->sL($this->llPath . ':flexforms_general.dateField.' . $dateMenuField)
					);

		return $content;
	}

	/**
	 * Get information if override demand setting is disabled or not
	 *
	 * @param array $data flexform data
	 * @return string
	 */
	private function getOverrideDemandSettings($data) {
		$content = '';

		$field = $this->getFieldFromFlexform($data, 'settings.disableOverrideDemand', 'additional');

		if ($field == 1) {
			$content = $this->renderLine($GLOBALS['LANG']->sL(
					$this->llPath . ':flexforms_additional.disableOverrideDemand'), '');
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
		if (empty($startingpoint)) {
			return '';
		}

		$pagesOut = array();
		$rawPagesRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'title,uid',
			'pages',
			'deleted=0 AND uid IN(' . implode(',', t3lib_div::intExplode(',', $startingpoint, TRUE)) . ')'
		);

		foreach ($rawPagesRecords as $page) {
			$pagesOut[] = htmlspecialchars($page['title']) . '<small> (' . $page['uid'] . ')</small>';
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
	protected function renderLine($head, $content) {
		$content = '<tr>
						<td style="font-weight:bold;width:200px;">' . $head .	'</td>
						<td>' . $content . '</td>
					</tr>';

		return $content;
	}

	/**
	 * Get field value from flexform configuration,
	 * including checks if flexform configuration is available
	 *
	 * @param array $flexform flexform configuration
	 * @param string $key name of the key
	 * @param string $sheet name of the sheet
	 * @return NULL if nothing found, value if found
	 */
	protected function getFieldFromFlexform($flexform, $key, $sheet = 'sDEF') {
		$flexform = $flexform['data'];
		if (is_array($flexform) && is_array($flexform[$sheet]) && is_array($flexform[$sheet]['lDEF'])
				&& is_array($flexform[$sheet]['lDEF'][$key]) && isset($flexform[$sheet]['lDEF'][$key]['vDEF'])
		) {
			return $flexform[$sheet]['lDEF'][$key]['vDEF'];
		}

		return NULL;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Resources/Private/Backend/class.tx_news2_cms_layout.php']) {
	require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Resources/Private/Backend/class.tx_news2_cms_layout.php']);
}

?>