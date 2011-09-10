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
 * @subpackage tx_news
 */
class Tx_News_Hooks_CmsLayout {

	/**
	 * Extension key
	 * @var string
	 */
	const extKey = 'news';

	/**
	 * Path to the locallang file
	 * @var string
	 */
	const llPath = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xml:';

	/**
	 * Table information
	 * @var array
	 */
	public $tableData = array();

	/**
	 * Selected action
	 *
	 * @var string
	 */
	public $selectedAction = NULL;

	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param array $params Parameters to the hook
	 * @param mixed $pObj A reference to calling object
	 * @return string Information about pi1 plugin
	 */
	public function getExtensionSummary(array $params, $pObj) {
		$result = '';

		if ($params['row']['list_type'] == self::extKey . '_pi1') {
			$data = t3lib_div::xml2array($params['row']['pi_flexform']);

				// if flexform data is found
			$actions = $this->getFieldFromFlexform($data, 'switchableControllerActions');
			if (!empty($actions)) {
				$actionList = t3lib_div::trimExplode(';', $actions);

					// translate the first action into its translation
				$actionTranslationKey = strtolower(str_replace('->', '_', $actionList[0]));
				$this->selectedAction = str_replace('news_', '', $actionTranslationKey);

				$actionTranslation = $GLOBALS['LANG']->sL(self::llPath . 'flexforms_general.mode.' . $actionTranslationKey);

				$result = '<strong>' .
							$GLOBALS['LANG']->sL(self::llPath . 'flexforms_general.mode', TRUE) .
						': </strong>' . htmlspecialchars($actionTranslation);

			} else {
				$result = $GLOBALS['LANG']->sL(self::llPath . 'flexforms_general.mode.not_configured');
			}

			if (is_array($data)) {
				$this->getDateMenuSettings($data);
				$this->getArchiveSettings($data);
				$this->getCategorySettings($data);
				$this->getStartingPoint($data['data']['sDEF']['lDEF']['settings.startingpoint']['vDEF']);
				$this->getOffsetLimitSettings($data);
				$this->getOverrideDemandSettings($data);
				$this->getTemplateLayoutSettings($data);

				$result .= $this->renderSettingsAsTable();
			}
		}

		return $result;
	}

	/**
	 * Render archive settings
	 *
	 * @param array $data
	 * @return void
	 */
	private function getArchiveSettings(array $data) {
		$archive = $this->getFieldFromFlexform($data, 'settings.archiveRestriction');

		if (!empty($archive)) {
			$this->tableData['sDEF.archiveRestriction'] = array(
							$GLOBALS['LANG']->sL(self::llPath . 'flexforms_general.archiveRestriction'),
							$GLOBALS['LANG']->sL(self::llPath . 'flexforms_general.archiveRestriction.' . $archive)
						);
		}
	}


	/**
	 * Render category settings
	 *
	 * @param array $data
	 * @return void
	 */
	private function getCategorySettings(array $data) {
		$categoryMode = '';
		$categoriesOut = array();

		$categories = t3lib_div::intExplode(',', $this->getFieldFromFlexform($data, 'settings.categories'), TRUE);
		if (count($categories) > 0) {

				// Category mode
			$categoryModeSelection = $this->getFieldFromFlexform($data, 'settings.categoryConjunction');

			if (empty($categoryModeSelection)) {
				$categoryMode = $GLOBALS['LANG']->sL(self::llPath . 'flexforms_general.categoryConjunction.all');
			} else {
				$categoryMode = $GLOBALS['LANG']->sL(self::llPath . 'flexforms_general.categoryConjunction.' . $categoryModeSelection);
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

			$this->tableData['sDEF.categories'] = array(
							$GLOBALS['LANG']->sL(self::llPath . 'flexforms_general.categories') .
								'<br /><span style="font-weight:normal;font-style:italic">(' . htmlspecialchars($categoryMode) . ')</span>',
							implode(', ', $categoriesOut)
						);
		}
	}

	/**
	 * Render offset & limit configuration
	 *
	 * @param array $data
	 * @return void
	 */
	private function getOffsetLimitSettings(array $data) {
		$offset = $this->getFieldFromFlexform($data, 'settings.offset', 'additional');
		$limit = $this->getFieldFromFlexform($data, 'settings.limit', 'additional');
		$hidePagionation = $this->getFieldFromFlexform($data, 'settings.hidePagination', 'additional');

		if ($offset) {
			$this->tableData['additional.offset'] = array($GLOBALS['LANG']->sL(self::llPath . 'flexforms_additional.offset'), $offset);
		}
		if ($limit) {
			$this->tableData['additional.limit'] = array($GLOBALS['LANG']->sL(self::llPath . 'flexforms_additional.limit'), $limit);
		}
		if ($hidePagionation) {
			$this->tableData['additional.hidePagination'] = array($GLOBALS['LANG']->sL(self::llPath . 'flexforms_additional.hidePagination'), NULL);
		}
	}

	/**
	 * Render datemenu configuration
	 *
	 * @param array $data flexform data
	 * @return void
	 */
	private function getDateMenuSettings(array $data) {
		$content = '';

		if ($this->selectedAction !== 'datemenu') {
			return $content;
		}

		$dateMenuField = $this->getFieldFromFlexform($data, 'settings.dateField');

		$this->tableData['sDEF.dateField'] = array(
						$GLOBALS['LANG']->sL(self::llPath . 'flexforms_general.dateField'),
						$GLOBALS['LANG']->sL(self::llPath . 'flexforms_general.dateField.' . $dateMenuField)
					);
	}

	/**
	 * Render template layout configuration
	 *
	 * @param array $data flexform data
	 * @return void
	 */
	private function getTemplateLayoutSettings(array $data) {
		$content = $title = '';

		$field = $this->getFieldFromFlexform($data, 'settings.templateLayout', 'template');
		if (!empty($field))
				// find correct title by looping over all options
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'] as $layouts) {
				if ($layouts[1] === $field) {
					$title = $layouts[0];
				}
			}

		if (!empty($title)) {
			$this->tableData['template.templateLayout'] = array(
							$GLOBALS['LANG']->sL(self::llPath . 'flexforms_template.templateLayout'),
							$GLOBALS['LANG']->sL($title)
						);
		}
	}

	/**
	 * Get information if override demand setting is disabled or not
	 *
	 * @param array $data flexform data
	 * @return void
	 */
	private function getOverrideDemandSettings($data) {
		$field = $this->getFieldFromFlexform($data, 'settings.disableOverrideDemand', 'additional');

		if ($field == 1) {
			$this->tableData['additional.disableOverrideDemand'] = array($GLOBALS['LANG']->sL(
					self::llPath . 'flexforms_additional.disableOverrideDemand'), '');
		}
	}

	/**
	 * Get the startingpoint
	 *
	 * @param string $startingpoint comma seperated list of pages
	 * @return void
	 */
	private function getStartingPoint($startingpoint) {
		if (empty($startingpoint)) {
			return;
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

		$this->tableData['sDEF.startingpoint'] = array(
						$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_general.php:LGL.startingpoint'),
						implode(', ', $pagesOut)
					);
	}

	/**
	 * Render the settings as table
	 * @return string
	 */
	protected function renderSettingsAsTable() {
		$content = '';
		$disallowedSettings = array();
		if (count($this->tableData) == 0) {
			return $content;
		}

			// Tx_News_Hooks_T3libBefunc saves which settings are not needed for which view
		$t3libBefunc = t3lib_div::makeInstance('Tx_News_Hooks_T3libBefunc');
		switch ($this->selectedAction) {
			case 'list':
				$disallowedSettings = $t3libBefunc->removedFieldsInListView;
				break;
			case 'detail':
				$disallowedSettings = $t3libBefunc->removedFieldsInDetailView;
				break;
			case 'datemenu':
				$disallowedSettings = $t3libBefunc->removedFieldsInDateMenuView;
				break;
		}

			// The fields got some tabs which need to be filtered
		foreach($disallowedSettings as $key => $value) {
			$disallowedSettings[$key] = str_replace(array(CRLF, CR, LF, TAB), '', $value);
		}

		$i = 0;
		foreach($this->tableData as $key => $line) {
			$allowedToBeRendered = TRUE;

				// Check if the setting is in the list of diabled ones
			$split = explode('.', $key);
			if (isset($disallowedSettings[$split[0]]) && t3lib_div::inList($disallowedSettings[$split[0]], $split[1])) {
				$allowedToBeRendered = FALSE;
			}

			if ($allowedToBeRendered) {
				$class = ($i++ % 2 == 0) ? 'bgColor4' : 'bgColor3';
				$renderedLine = '';

				if (!empty($line[1])) {
					$renderedLine = '<td style="font-weight:bold;width:40%;">' . $line[0] .	'</td>
									<td>' . $line[1] . $key. '</td>';
				} else {
					$renderedLine = '<td style="font-weight:bold;" colspan="2">' . $line[0] .	'</td>';
				}
				$content .= '<tr class="' . $class . '">' . $renderedLine . '</tr>';
			}
		}

		if (!empty($content)) {
			$content = '<br /><table class="typo3-dblist">' . $content . '</table>';
		}

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


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news/Classes/Hooks/CmsLayout.php']) {
	require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news/Classes/Hooks/CmsLayout.php']);
}

?>