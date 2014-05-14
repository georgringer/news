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
	 *
	 * @var string
	 */
	const KEY = 'news';

	/**
	 * Path to the locallang file
	 *
	 * @var string
	 */
	const LLPATH = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xml:';

	/**
	 * Table information
	 *
	 * @var array
	 */
	public $tableData = array();

	/**
	 * Flexform information
	 *
	 * @var array
	 */
	public $flexformData = array();

	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param array $params Parameters to the hook
	 * @return string Information about pi1 plugin
	 */
	public function getExtensionSummary(array $params) {
		$result = $actionTranslationKey = '';

		if ($this->showExtensionTitle()) {
			$result .= '<strong>' . $GLOBALS['LANG']->sL(self::LLPATH . 'pi1_title', TRUE) . '</strong>';
		}

		if ($params['row']['list_type'] == self::KEY . '_pi1') {
			$this->flexformData = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($params['row']['pi_flexform']);

			// if flexform data is found
			$actions = $this->getFieldFromFlexform('switchableControllerActions');
			if (!empty($actions)) {
				$actionList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(';', $actions);

				// translate the first action into its translation
				$actionTranslationKey = strtolower(str_replace('->', '_', $actionList[0]));
				$actionTranslation = $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.mode.' . $actionTranslationKey);

				$result .= '<pre>' . $actionTranslation . '</pre>';

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
						$this->getTagRestrictionSetting();
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
						$this->getStartingPoint();
						$this->getListPidSetting();
						$this->getOrderSettings();
						break;
					default:
				}

				// for all views
				$this->getOverrideDemandSettings();
				$this->getTemplateLayoutSettings($params['row']['pid']);

				$result .= $this->renderSettingsAsTable();
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
		$archive = $this->getFieldFromFlexform('settings.archiveRestriction');

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
		$singleNewsRecord = (int)$this->getFieldFromFlexform('settings.singleNews');

		if ($singleNewsRecord > 0) {
			$newsRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tx_news_domain_model_news', 'deleted=0 AND uid=' . $singleNewsRecord);

			if (is_array($newsRecord)) {
				$pageRecord = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $newsRecord['pid']);

				if (is_array($pageRecord)) {
					$iconPage = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForRecord('pages', $pageRecord, array('title' => 'Uid: ' . $pageRecord['uid']));
					$iconNews = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForRecord('tx_news_domain_model_news', $newsRecord, array('title' => 'Uid: ' . $newsRecord['uid']));

					$onClickPage = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($iconPage, 'pages', $pageRecord['uid'], 1, '', '+info,edit,view', TRUE);
					$onClickNews = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($iconNews, 'tx_news_domain_model_news', $newsRecord['uid'], 1, '', '+info,edit', TRUE);

					$content = '<a href="#" onclick="' . htmlspecialchars($onClickPage) . '">' . $iconPage . '</a>' .
						htmlspecialchars(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('pages', $pageRecord)) . ': ' .
						'<a href="#" onclick="' . htmlspecialchars($onClickNews) . '">' .
						$iconNews . htmlspecialchars(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('tx_news_domain_model_news', $newsRecord)) .
						'</a>';
				} else {
					/** @var $message \TYPO3\CMS\Core\Messaging\FlashMessage */
					$text = sprintf($GLOBALS['LANG']->sL(self::LLPATH . 'pagemodule.pageNotAvailable', TRUE), $newsRecord['pid']);
					$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $text, '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
					$content = $message->render();
				}
			} else {
				/** @var $message \TYPO3\CMS\Core\Messaging\FlashMessage */
				$text = sprintf($GLOBALS['LANG']->sL(self::LLPATH . 'pagemodule.newsNotAvailable', TRUE), $singleNewsRecord);
				$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $text, '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
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
		$detailPid = (int)$this->getFieldFromFlexform('settings.detailPid', 'additional');

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
		$listPid = (int)$this->getFieldFromFlexform('settings.listPid', 'additional');

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
		$pageRecord = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $detailPid);

		if (is_array($pageRecord)) {
			$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForRecord('pages', $pageRecord, array('title' => 'Uid: ' . $pageRecord['uid']));
			$onClick = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($icon, 'pages', $pageRecord['uid'], 1, '', '+info,edit', TRUE);

			$content = '<a href="#" onclick="' . htmlspecialchars($onClick) . '">' . $icon . '</a>' .
				htmlspecialchars(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('pages', $pageRecord));
		} else {
			/** @var $message \TYPO3\CMS\Core\Messaging\FlashMessage */
			$text = sprintf($GLOBALS['LANG']->sL(self::LLPATH . 'pagemodule.pageNotAvailable', TRUE), $detailPid);
			$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $text, '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
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
		$orderField = $this->getFieldFromFlexform('settings.orderBy');
		if (!empty($orderField)) {
			$text = $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.orderBy.' . $orderField);

			// Order direction (asc, desc)
			$orderDirection = $this->getOrderDirectionSetting();
			if ($orderDirection) {
				$text .= ', ' . strtolower($orderDirection);
			}

			// Top news first
			$topNews = $this->getTopNewsFirstSetting();
			if ($topNews) {
				$text .= '<br />' . $topNews;
			}

			$this->tableData[] = array(
				$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.orderBy'),
				$text
			);
		}
	}

	/**
	 * Get order direction
	 *
	 * @return string
	 */
	protected function getOrderDirectionSetting() {
		$text = '';

		$orderDirection = $this->getFieldFromFlexform('settings.orderDirection');
		if (!empty($orderDirection)) {
			$text = $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.orderDirection.' . $orderDirection);
		}

		return $text;
	}

	/**
	 * Get topNewsFirst setting
	 *
	 * @return string
	 */
	protected function getTopNewsFirstSetting() {
		$text = '';
		$topNewsSetting = (int)$this->getFieldFromFlexform('settings.topNewsFirst', 'additional');
		if ($topNewsSetting === 1) {
			$text = $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.topNewsFirst');
		}

		return $text;
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

		$categories = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $this->getFieldFromFlexform('settings.categories'), TRUE);
		if (count($categories) > 0) {

			// Category mode
			$categoryModeSelection = $this->getFieldFromFlexform('settings.categoryConjunction');

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
				'sys_category',
				'deleted=0 AND uid IN(' . implode(',', $categories) . ')'
			);

			foreach ($rawCategoryRecords as $record) {
				$categoriesOut[] = htmlspecialchars(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('sys_category', $record));
			}

			$includeSubcategories = $this->getFieldFromFlexform('settings.includeSubCategories');
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
	 * Get the restriction for tags
	 *
	 * @return void
	 */
	protected function getTagRestrictionSetting() {
		$tags = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $this->getFieldFromFlexform('settings.tags', 'additional'), TRUE);
		if (count($tags) === 0) {
			return;
		}

		$categoryTitles = array();
		$rawTagRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'tx_news_domain_model_tag',
			'deleted=0 AND uid IN(' . implode(',', $tags) . ')'
		);
		foreach ($rawTagRecords as $record) {
			$categoryTitles[] = htmlspecialchars(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('tx_news_domain_model_tag', $record));
		}

		$this->tableData[] = array(
			$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.tags'),
			implode(', ', $categoryTitles)
		);
	}

	/**
	 * Render offset & limit configuration
	 *
	 * @return void
	 */
	protected function getOffsetLimitSettings() {
		$offset = $this->getFieldFromFlexform('settings.offset', 'additional');
		$limit = $this->getFieldFromFlexform('settings.limit', 'additional');
		$hidePagination = $this->getFieldFromFlexform('settings.hidePagination', 'additional');

		if ($offset) {
			$this->tableData[] = array($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.offset'), $offset);
		}
		if ($limit) {
			$this->tableData[] = array($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.limit'), $limit);
		}
		if ($hidePagination) {
			$this->tableData[] = array($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_additional.hidePagination'), NULL);
		}
	}

	/**
	 * Render date menu configuration
	 *
	 * @return void
	 */
	protected function getDateMenuSettings() {
		$dateMenuField = $this->getFieldFromFlexform('settings.dateField');

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
		$timeRestriction = $this->getFieldFromFlexform('settings.timeRestriction');

		if (!empty($timeRestriction)) {
			$this->tableData[] = array(
				$GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.timeRestriction'),
				htmlspecialchars($timeRestriction)
			);
		}

		$timeRestrictionHigh = $this->getFieldFromFlexform('settings.timeRestrictionHigh');
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
		$topNewsRestriction = (int)$this->getFieldFromFlexform('settings.topNewsRestriction');
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
	 * @param int $pageUid
	 * @return void
	 */
	protected function getTemplateLayoutSettings($pageUid) {
		$title = '';
		$field = $this->getFieldFromFlexform('settings.templateLayout', 'template');

		// Find correct title by looping over all options
		if (!empty($field)) {
			/** @var Tx_News_Utility_TemplateLayout $templateLayoutsUtility */
			$templateLayoutsUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_News_Utility_TemplateLayout');
			foreach ($templateLayoutsUtility->getAvailableTemplateLayouts($pageUid) as $layout) {
				if ($layout[1] === $field) {
					$title = $layout[0];
				}
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
		$field = $this->getFieldFromFlexform('settings.disableOverrideDemand', 'additional');

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
		$value = $this->getFieldFromFlexform('settings.startingpoint');

		if (!empty($value)) {
			$pagesOut = array();
			$rawPagesRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'*',
				'pages',
				'deleted=0 AND uid IN(' . implode(',', \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $value, TRUE)) . ')'
			);

			foreach ($rawPagesRecords as $page) {
				$pagesOut[] = htmlspecialchars(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('pages', $page)) . ' (' . $page['uid'] . ')';
			}

			$recursiveLevel = (int)$this->getFieldFromFlexform('settings.recursive');
			$recursiveLevelText = '';
			if ($recursiveLevel === 250) {
				$recursiveLevelText = $GLOBALS['LANG']->sL('LLL:EXT:cms/locallang_ttc.xml:recursive.I.5');
			} elseif ($recursiveLevel > 0) {
				$recursiveLevelText = $GLOBALS['LANG']->sL('LLL:EXT:cms/locallang_ttc.xml:recursive.I.' . $recursiveLevel);
			}

			if (!empty($recursiveLevelText)) {
				$recursiveLevelText = '<br />' .
					$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_general.xml:LGL.recursive', TRUE) . ' ' .
					$recursiveLevelText;
			}

			$this->tableData[] = array(
				$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_general.php:LGL.startingpoint'),
				implode(', ', $pagesOut) . $recursiveLevelText
			);
		}
	}

	/**
	 * Render the settings as table for Web>Page module
	 * System settings are displayed in mono font
	 *
	 * @return string
	 */
	protected function renderSettingsAsTable() {
		if (count($this->tableData) == 0) {
			return '';
		}

		$content = '';
		foreach ($this->tableData as $line) {
			$content .= '<strong>' . $line[0] . '</strong>' . ' ' .$line[1] . '<br />';
		}

		return '<pre>' . $content . '</pre>';
	}

	/**
	 * Get field value from flexform configuration,
	 * including checks if flexform configuration is available
	 *
	 * @param string $key name of the key
	 * @param string $sheet name of the sheet
	 * @return string|NULL if nothing found, value if found
	 */
	protected function getFieldFromFlexform($key, $sheet = 'sDEF') {
		$flexform = $this->flexformData;
		if (isset($flexform['data'])) {
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