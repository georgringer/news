<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
	const KEY = 'news';

	/**
	 * Path to the locallang file
	 * @var string
	 */
	const LLPATH = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xml:';

	/**
	 * Table information
	 * @var array
	 */
	public $tableData = array();

	/**
	 * Flexform information
	 * @var array
	 */
	public $flexformData = array();

	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param array $params Parameters to the hook
	 * @param mixed $pObj A reference to calling object
	 * @return string Information about pi1 plugin
	 */
	public function getExtensionSummary(array $params, $pObj) {
		$result = $actionTranslationKey = '';

		if ($this->showExtensionTitle()) {
			$result .= '<strong>' . $GLOBALS['LANG']->sL(self::LLPATH . 'pi1_title', TRUE) . '</strong>';
		}

		if ($params['row']['list_type'] == self::KEY . '_pi1') {
			$this->flexformData = t3lib_div::xml2array($params['row']['pi_flexform']);

				// if flexform data is found
			$actions = $this->getFieldFromFlexform($this->flexformData, 'switchableControllerActions');
			if (!empty($actions)) {
				$actionList = t3lib_div::trimExplode(';', $actions);

					// translate the first action into its translation
				$actionTranslationKey = strtolower(str_replace('->', '_', $actionList[0]));
				$actionTranslation = $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.mode.' . $actionTranslationKey);

				$result .= '<h5>' . $actionTranslation . '</h5>';

			} else {
				$result = $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.mode.not_configured');
			}

			if (is_array($this->flexformData)) {

				switch ($actionTranslationKey) {
					case 'news_list':
						$this->getStartingPoint();
						$this->getTimeRestrictionSetting();
						$this->getTopNewsRestrictionSetting();
						$this->getOrderSettings();
						$this->getCategorySettings();
						$this->getArchiveSettings();
						$this->getOffsetLimitSettings();
						$this->getDetailPidSetting();
						$this->getListPidSetting();
						break;
					case 'news_detail':
						$this->getSingleNewsSettings();
						$this->getDetailPidSetting();
						break;
					case 'news_datemenu':
						$this->getStartingPoint();
						$this->getTimeRestrictionSetting();
						$this->getTopNewsRestrictionSetting();
						$this->getDateMenuSettings();
						$this->getCategorySettings();
						break;
					case 'category_list':
						$this->getCategorySettings(FALSE);
						break;
					case 'tag_list':
						$this->getStartingPoint(FALSE);
						$this->getListPidSetting();
						$this->getOrderSettings();
						break;
					default:
				}

					// for all views
				$this->getOverrideDemandSettings();
				$this->getTemplateLayoutSettings();

				$result .= $this->renderSettingsAsTable($params['row']);
			}
		}

		return $result;
	}

	/**
	 * Render archive settings
	 *
	 * @return void
	 */
	protected function getArchiveSettings() {
		$archive = $this->getFieldFromFlexform($this->flexformData, 'settings.archiveRestriction');

		if (!empty($archive)) {
			$this->tableData[] = array(
							$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.archiveRestriction'),
							$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.archiveRestriction.' . $archive)
						);
		}
	}

	/**
	 * Render single news settings
	 *
	 * @return void
	 */
	protected function getSingleNewsSettings() {
		$content = '';
		$singleNewsRecord = (int)$this->getFieldFromFlexform($this->flexformData, 'settings.singleNews');

		if ($singleNewsRecord > 0) {
			$newsRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tx_news_domain_model_news', 'deleted=0 AND uid=' . $singleNewsRecord);

			if (is_array($newsRecord)) {
				$pageRecord = t3lib_BEfunc::getRecord('pages', $newsRecord['pid']);

				if (is_array($pageRecord)) {
					$iconPage = t3lib_iconWorks::getSpriteIconForRecord('pages', $pageRecord, array('title' => 'Uid: ' . $pageRecord['uid']));
					$iconNews = t3lib_iconWorks::getSpriteIconForRecord('tx_news_domain_model_news', $newsRecord, array('title' => 'Uid: ' . $newsRecord['uid']));

					$onClickPage = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($iconPage, 'pages', $pageRecord['uid'], 1, '', '+info,edit,view', TRUE);
					$onClickNews = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($iconNews, 'tx_news_domain_model_news', $newsRecord['uid'], 1, '', '+info,edit', TRUE);

					$content = '<a href="#" onclick="' . htmlspecialchars($onClickPage) . '">' . $iconPage . '</a>' .
						htmlspecialchars(t3lib_BEfunc::getRecordTitle('pages', $pageRecord)) . ': ' .
						'<a href="#" onclick="' . htmlspecialchars($onClickNews) . '">' .
							$iconNews . htmlspecialchars(t3lib_BEfunc::getRecordTitle('tx_news_domain_model_news', $newsRecord)) .
						'</a>';
				} else {
					/** @var $message t3lib_FlashMessage */
					$text = sprintf($GLOBALS['LANG']->sL(self::LLPATH . 'pagemodule.pageNotAvailable', TRUE), $newsRecord['pid']);
					$message = t3lib_div::makeInstance('t3lib_FlashMessage', $text, '', t3lib_FlashMessage::WARNING);
					$content = $message->render();
				}
			} else {
				/** @var $message t3lib_FlashMessage */
				$text = sprintf($GLOBALS['LANG']->sL(self::LLPATH . 'pagemodule.newsNotAvailable', TRUE), $singleNewsRecord);
				$message = t3lib_div::makeInstance('t3lib_FlashMessage', $text, '', t3lib_FlashMessage::WARNING);
				$content = $message->render();
			}

			$this->tableData[] = array($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.singleNews'), $content);
		}
	}

	/**
	 * Render single news settings
	 *
	 * @return void
	 */
	protected function getDetailPidSetting() {
		$content = '';
		$detailPid = (int)$this->getFieldFromFlexform($this->flexformData, 'settings.detailPid', 'additional');

		if ($detailPid > 0) {
			$content = $this->getPageRecordData($detailPid);

			$this->tableData[] = array($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.detailPid'), $content);
		}
	}

	/**
	 * Render listPid news settings
	 *
	 * @return void
	 */
	protected function getListPidSetting() {
		$content = '';
		$listPid = (int)$this->getFieldFromFlexform($this->flexformData, 'settings.listPid', 'additional');

		if ($listPid > 0) {
			$content = $this->getPageRecordData($listPid);

			$this->tableData[] = array($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.listPid'), $content);
		}
	}

	/**
	 * Get the rendered page title including onclick menu
	 *
	 * @param $detailPid
	 * @return string
	 */
	protected function getPageRecordData($detailPid) {
		$content = '';
		$pageRecord = t3lib_BEfunc::getRecord('pages', $detailPid);

		if (is_array($pageRecord)) {
			$icon = t3lib_iconWorks::getSpriteIconForRecord('pages', $pageRecord, array('title' => 'Uid: ' . $pageRecord['uid']));
			$onClick = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($icon, 'pages', $pageRecord['uid'], 1, '', '+info,edit', TRUE);

			$content = '<a href="#" onclick="' . htmlspecialchars($onClick) . '">' . $icon . '</a>' .
				htmlspecialchars(t3lib_BEfunc::getRecordTitle('pages', $pageRecord));
		} else {
			/** @var $message t3lib_FlashMessage */
			$text = sprintf($GLOBALS['LANG']->sL(self::LLPATH . 'pagemodule.pageNotAvailable', TRUE), $detailPid);
			$message = t3lib_div::makeInstance('t3lib_FlashMessage', $text, '', t3lib_FlashMessage::WARNING);
			$content = $message->render();
		}

		return $content;
	}

	/**
	 * Get order settings
	 *
	 * @return void
	 */
	protected function getOrderSettings() {
		$orderField = $this->getFieldFromFlexform($this->flexformData, 'settings.orderBy');
		if (!empty($orderField)) {
			$text = $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.orderBy.' . $orderField);

				// Order direction (asc, desc)
			$orderDirection = $this->getFieldFromFlexform($this->flexformData, 'settings.orderDirection');
			if (!empty($orderDirection)) {
				$text .= ', ' . strtolower($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.orderDirection.' . $orderDirection));
			}

				// Top news first
			$topNewsSetting = (int)$this->getFieldFromFlexform($this->flexformData, 'settings.topNewsFirst', 'additional');
			if ($topNewsSetting === 1) {
				$text .= '<br />' . $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.topNewsFirst');
			}

			$this->tableData[] = array(
				$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.orderBy'),
				$text
			);
		}
	}


	/**
	 * Render category settings
	 *
	 * @param boolean $showCategoryMode show the category conjunction
	 * @return void
	 */
	protected function getCategorySettings($showCategoryMode = TRUE) {
		$categoryMode = '';
		$categoriesOut = array();

		$categories = t3lib_div::intExplode(',', $this->getFieldFromFlexform($this->flexformData, 'settings.categories'), TRUE);
		if (count($categories) > 0) {

				// Category mode
			$categoryModeSelection = $this->getFieldFromFlexform($this->flexformData, 'settings.categoryConjunction');

			if ($showCategoryMode) {
				if (empty($categoryModeSelection)) {
					$categoryMode = $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.categoryConjunction.all');
				} else {
					$categoryMode = $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.categoryConjunction.' . $categoryModeSelection);
				}

				$categoryMode = '<span style="font-weight:normal;font-style:italic">(' . htmlspecialchars($categoryMode) . ')</span>';
			}

				// Category records
			$rawCategoryRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'*',
				'tx_news_domain_model_category',
				'deleted=0 AND uid IN(' . implode(',', $categories) . ')'
			);

			foreach ($rawCategoryRecords as $record) {
				$categoriesOut[] = htmlspecialchars(t3lib_BEfunc::getRecordTitle('tx_news_domain_model_category', $record));
			}

			$includeSubcategories = $this->getFieldFromFlexform($this->flexformData, 'settings.includeSubCategories');
			if ($includeSubcategories) {
				$categoryMode .= '<br />+ ' . $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.includeSubCategories', TRUE);
			}

			$this->tableData[] = array(
							$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.categories') .
								'<br />' . $categoryMode,
							implode(', ', $categoriesOut)
						);
		}
	}

	/**
	 * Render offset & limit configuration
	 *
	 * @return void
	 */
	protected function getOffsetLimitSettings() {
		$offset = $this->getFieldFromFlexform($this->flexformData, 'settings.offset', 'additional');
		$limit = $this->getFieldFromFlexform($this->flexformData, 'settings.limit', 'additional');
		$hidePagionation = $this->getFieldFromFlexform($this->flexformData, 'settings.hidePagination', 'additional');

		if ($offset) {
			$this->tableData[] = array($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.offset'), $offset);
		}
		if ($limit) {
			$this->tableData[] = array($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.limit'), $limit);
		}
		if ($hidePagionation) {
			$this->tableData[] = array($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.hidePagination'), NULL);
		}
	}

	/**
	 * Render date menu configuration
	 *
	 * @return void
	 */
	protected function getDateMenuSettings() {
		$dateMenuField = $this->getFieldFromFlexform($this->flexformData, 'settings.dateField');

		$this->tableData[] = array(
						$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.dateField'),
						$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.dateField.' . $dateMenuField)
					);
	}

	/**
	 * Render time restriction configuration
	 *
	 * @return void
	 */
	protected function getTimeRestrictionSetting() {
		$timeRestriction = $this->getFieldFromFlexform($this->flexformData, 'settings.timeRestriction');

		if (!empty($timeRestriction)) {
			$this->tableData[] = array(
							$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.timeRestriction'),
							htmlspecialchars($timeRestriction)
						);
		}

		$timeRestrictionHigh = $this->getFieldFromFlexform($this->flexformData, 'settings.timeRestrictionHigh');
		if (!empty($timeRestrictionHigh)) {
			$this->tableData[] = array(
							$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.timeRestrictionHigh'),
							htmlspecialchars($timeRestrictionHigh)
						);
		}
	}

	/**
	 * Render top news restriction configuration
	 *
	 * @return void
	 */
	protected function getTopNewsRestrictionSetting() {
		$topNewsRestriction = (int)$this->getFieldFromFlexform($this->flexformData, 'settings.topNewsRestriction');
		if ($topNewsRestriction > 0) {
			$this->tableData[] = array(
							$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.topNewsRestriction'),
							$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.topNewsRestriction.' . $topNewsRestriction)
						);
		}
	}

	/**
	 * Render template layout configuration
	 *
	 * @return void
	 */
	protected function getTemplateLayoutSettings() {
		$title = '';

		$field = $this->getFieldFromFlexform($this->flexformData, 'settings.templateLayout', 'template');
		if (!empty($field) && is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts']))
				// Find correct title by looping over all options
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'] as $layouts) {
				if ($layouts[1] === $field) {
					$title = $layouts[0];
				}
			}

		if (!empty($title)) {
			$this->tableData[] = array(
							$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_template.templateLayout'),
							$GLOBALS['LANG']->sL($title)
						);
		}
	}

	/**
	 * Get information if override demand setting is disabled or not
	 *
	 * @return void
	 */
	protected function getOverrideDemandSettings() {
		$field = $this->getFieldFromFlexform($this->flexformData, 'settings.disableOverrideDemand', 'additional');

		if ($field == 1) {
			$this->tableData[] = array($GLOBALS['LANG']->sL(
					self::LLPATH . 'flexforms_additional.disableOverrideDemand'), '');
		}
	}

	/**
	 * Get the startingpoint
	 *
	 * @return void
	 */
	protected function getStartingPoint() {
		$value = $this->getFieldFromFlexform($this->flexformData, 'settings.startingpoint');

		if (!empty($value)) {
			$pagesOut = array();
			$rawPagesRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'*',
				'pages',
				'deleted=0 AND uid IN(' . implode(',', t3lib_div::intExplode(',', $value, TRUE)) . ')'
			);

			foreach ($rawPagesRecords as $page) {
				$pagesOut[] = htmlspecialchars(t3lib_BEfunc::getRecordTitle('pages', $page)) . '<small> (' . $page['uid'] . ')</small>';
			}

			$recursiveLevel = (int)$this->getFieldFromFlexform($this->flexformData, 'settings.recursive');
			$recursiveLevelText = '';
			if ($recursiveLevel === 250) {
				$recursiveLevelText = $GLOBALS['LANG']->sL('LLL:EXT:cms/locallang_ttc.xml:recursive.I.5');
			} elseif ($recursiveLevel > 0) {
				$recursiveLevelText = $GLOBALS['LANG']->sL('LLL:EXT:cms/locallang_ttc.xml:recursive.I.' . $recursiveLevel);
			}

			if (!empty($recursiveLevelText)) {
				$recursiveLevelText =  '<br /><small>' .
							$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_general.xml:LGL.recursive', TRUE) .
							': ' . $recursiveLevelText . '</small>';
			}

			$this->tableData[] = array(
							$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_general.php:LGL.startingpoint'),
							implode(', ', $pagesOut) . $recursiveLevelText
						);
		}
	}

	/**
	 * Render the settings as table
	 *
	 * @param array $record content element record
	 * @return string
	 */
	protected function renderSettingsAsTable(array $record) {
		$content = '';
		if (count($this->tableData) == 0) {
			return $content;
		}

		$visible = ($record['hidden'] == 0);
		$i = 0;
		foreach ($this->tableData as $line) {
				// Check if the setting is in the list of disabled ones
			$class = ($i++ % 2 === 0) ? 'bgColor4' : 'bgColor3';
			$renderedLine = '';

			if (!empty($line[1])) {
				$renderedLine = '<td style="' . ($visible ? 'font-weight:bold;' : '') . 'width:40%;">' . $line[0] .	'</td>
								<td>' . $line[1] . '</td>';
			} else {
				$renderedLine = '<td style="' . ($visible ? 'font-weight:bold;' : '') . '" colspan="2">' . $line[0] .	'</td>';
			}
			$content .= '<tr class="' . ($visible ? $class : '') . '">' . $renderedLine . '</tr>';
		}

		if (!empty($content)) {
			$styles = 'width:100%;';
			$styles .= ($visible) ? '' : 'opacity:0.7;';

			$content = '<table style="' . $styles . '" class="t3-table">' . $content . '</table>';
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
		if (is_array($flexform) && isset($flexform['data'])) {
			$flexform = $flexform['data'];
			if (is_array($flexform) && is_array($flexform[$sheet]) && is_array($flexform[$sheet]['lDEF'])
					&& is_array($flexform[$sheet]['lDEF'][$key]) && isset($flexform[$sheet]['lDEF'][$key]['vDEF'])
			) {
				return $flexform[$sheet]['lDEF'][$key]['vDEF'];
			}
		}

		return NULL;
	}

	/**
	 * Because of changes since TYPO3 CMS version 6.0,
	 * the extension name needs to be shown additionally
	 *
	 * @return boolean
	 */
	protected function showExtensionTitle() {
		$majorVersion = intval(substr(TYPO3_branch, 0, 1));

		return ($majorVersion >= 6);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news/Classes/Hooks/CmsLayout.php']) {
	require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news/Classes/Hooks/CmsLayout.php']);
}
