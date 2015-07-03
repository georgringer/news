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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook to display verbose information about pi1 plugin in Web>Page module
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class PageLayoutView {

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
	const LLPATH = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:';

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

	/** @var  \TYPO3\CMS\Core\Database\DatabaseConnection */
	protected $databaseConnection;

	/** @var \GeorgRinger\News\Utility\TemplateLayout $templateLayoutsUtility */
	protected $templateLayoutsUtility;

	public function __construct() {
		/** @var \TYPO3\CMS\Core\Database\DatabaseConnection databaseConnection */
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
		$this->templateLayoutsUtility = GeneralUtility::makeInstance('GeorgRinger\\News\\Utility\\TemplateLayout');
	}

	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param array $params Parameters to the hook
	 * @return string Information about pi1 plugin
	 */
	public function getExtensionSummary(array $params) {
		$actionTranslationKey = '';

		$result = '<strong>' . $this->getLanguageService()->sL(self::LLPATH . 'pi1_title', TRUE) . '</strong><br>';

		if ($params['row']['list_type'] == self::KEY . '_pi1') {
			$this->flexformData = GeneralUtility::xml2array($params['row']['pi_flexform']);

			// if flexform data is found
			$actions = $this->getFieldFromFlexform('switchableControllerActions');
			if (!empty($actions)) {
				$actionList = GeneralUtility::trimExplode(';', $actions);

				// translate the first action into its translation
				$actionTranslationKey = strtolower(str_replace('->', '_', $actionList[0]));
				$actionTranslation = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.mode.' . $actionTranslationKey);

				$result .= $actionTranslation;

			} else {
				$result .= $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.mode.not_configured');
			}
			$result .= '<hr>';

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
						$this->getArchiveSettings();
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

				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['GeorgRinger\\News\\Hooks\\PageLayoutView']['extensionSummary'])) {
					$params = array(
						'action' => $actionTranslationKey
					);
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['GeorgRinger\\News\\Hooks\\PageLayoutView']['extensionSummary'] as $reference) {
						GeneralUtility::callUserFunction($reference, $params, $this);
					}
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
	public function getArchiveSettings() {
		$archive = $this->getFieldFromFlexform('settings.archiveRestriction');

		if (!empty($archive)) {
			$this->tableData[] = array(
				$this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.archiveRestriction'),
				$this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.archiveRestriction.' . $archive)
			);
		}
	}

	/**
	 * Render single news settings
	 *
	 * @return void
	 */
	public function getSingleNewsSettings() {
		$singleNewsRecord = (int)$this->getFieldFromFlexform('settings.singleNews');

		if ($singleNewsRecord > 0) {
			$newsRecord = $this->databaseConnection->exec_SELECTgetSingleRow('*', 'tx_news_domain_model_news', 'deleted=0 AND uid=' . $singleNewsRecord);

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
					$text = sprintf($this->getLanguageService()->sL(self::LLPATH . 'pagemodule.pageNotAvailable', TRUE), $newsRecord['pid']);
					$message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $text, '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
					$content = $message->render();
				}
			} else {
				/** @var $message \TYPO3\CMS\Core\Messaging\FlashMessage */
				$text = sprintf($this->getLanguageService()->sL(self::LLPATH . 'pagemodule.newsNotAvailable', TRUE), $singleNewsRecord);
				$message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $text, '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
				$content = $message->render();
			}

			$this->tableData[] = array($this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.singleNews'), $content);
		}
	}

	/**
	 * Render single news settings
	 *
	 * @return void
	 */
	public function getDetailPidSetting() {
		$detailPid = (int)$this->getFieldFromFlexform('settings.detailPid', 'additional');

		if ($detailPid > 0) {
			$content = $this->getPageRecordData($detailPid);

			$this->tableData[] = array($this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.detailPid'), $content);
		}
	}

	/**
	 * Render listPid news settings
	 *
	 * @return void
	 */
	public function getListPidSetting() {
		$listPid = (int)$this->getFieldFromFlexform('settings.listPid', 'additional');

		if ($listPid > 0) {
			$content = $this->getPageRecordData($listPid);

			$this->tableData[] = array($this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.listPid'), $content);
		}
	}

	/**
	 * Get the rendered page title including onclick menu
	 *
	 * @param $detailPid
	 * @return string
	 */
	public function getPageRecordData($detailPid) {
		$pageRecord = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $detailPid);

		if (is_array($pageRecord)) {
			$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForRecord('pages', $pageRecord, array('title' => 'Uid: ' . $pageRecord['uid']));
			$onClick = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($icon, 'pages', $pageRecord['uid'], TRUE, '', '+info,edit');

			$content = '<a href="#" onclick="' . htmlspecialchars($onClick) . '">' . $icon . '</a>' .
				htmlspecialchars(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('pages', $pageRecord));
		} else {
			/** @var $message \TYPO3\CMS\Core\Messaging\FlashMessage */
			$text = sprintf($this->getLanguageService()->sL(self::LLPATH . 'pagemodule.pageNotAvailable', TRUE), $detailPid);
			$message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $text, '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
			$content = $message->render();
		}

		return $content;
	}

	/**
	 * Get order settings
	 *
	 * @return void
	 */
	public function getOrderSettings() {
		$orderField = $this->getFieldFromFlexform('settings.orderBy');
		if (!empty($orderField)) {
			$text = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.orderBy.' . $orderField);

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
				$this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.orderBy'),
				$text
			);
		}
	}

	/**
	 * Get order direction
	 *
	 * @return string
	 */
	public function getOrderDirectionSetting() {
		$text = '';

		$orderDirection = $this->getFieldFromFlexform('settings.orderDirection');
		if (!empty($orderDirection)) {
			$text = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.orderDirection.' . $orderDirection);
		}

		return $text;
	}

	/**
	 * Get topNewsFirst setting
	 *
	 * @return string
	 */
	public function getTopNewsFirstSetting() {
		$text = '';
		$topNewsSetting = (int)$this->getFieldFromFlexform('settings.topNewsFirst', 'additional');
		if ($topNewsSetting === 1) {
			$text = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.topNewsFirst');
		}

		return $text;
	}


	/**
	 * Render category settings
	 *
	 * @param boolean $showCategoryMode show the category conjunction
	 * @return void
	 */
	public function getCategorySettings($showCategoryMode = TRUE) {
		$categoryMode = '';
		$categoriesOut = array();

		$categories = GeneralUtility::intExplode(',', $this->getFieldFromFlexform('settings.categories'), TRUE);
		if (count($categories) > 0) {

			// Category mode
			$categoryModeSelection = $this->getFieldFromFlexform('settings.categoryConjunction');

			if ($showCategoryMode) {
				if (empty($categoryModeSelection)) {
					$categoryMode = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.categoryConjunction.all');
				} else {
					$categoryMode = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.categoryConjunction.' . $categoryModeSelection);
				}

				$categoryMode = '<span style="font-weight:normal;font-style:italic">(' . htmlspecialchars($categoryMode) . ')</span>';
			}

			// Category records
			$rawCategoryRecords = $this->databaseConnection->exec_SELECTgetRows(
				'*',
				'sys_category',
				'deleted=0 AND uid IN(' . implode(',', $categories) . ')'
			);

			foreach ($rawCategoryRecords as $record) {
				$categoriesOut[] = htmlspecialchars(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('sys_category', $record));
			}

			$includeSubcategories = $this->getFieldFromFlexform('settings.includeSubCategories');
			if ($includeSubcategories) {
				$categoryMode .= '<br />+ ' . $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.includeSubCategories', TRUE);
			}

			$this->tableData[] = array(
				$this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.categories') .
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
	public function getTagRestrictionSetting() {
		$tags = GeneralUtility::intExplode(',', $this->getFieldFromFlexform('settings.tags', 'additional'), TRUE);
		if (count($tags) === 0) {
			return;
		}

		$categoryTitles = array();
		$rawTagRecords = (array)$this->databaseConnection->exec_SELECTgetRows(
			'*',
			'tx_news_domain_model_tag',
			'deleted=0 AND uid IN(' . implode(',', $tags) . ')'
		);
		foreach ($rawTagRecords as $record) {
			$categoryTitles[] = htmlspecialchars(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('tx_news_domain_model_tag', $record));
		}

		$this->tableData[] = array(
			$this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.tags'),
			implode(', ', $categoryTitles)
		);
	}

	/**
	 * Render offset & limit configuration
	 *
	 * @return void
	 */
	public function getOffsetLimitSettings() {
		$offset = $this->getFieldFromFlexform('settings.offset', 'additional');
		$limit = $this->getFieldFromFlexform('settings.limit', 'additional');
		$hidePagination = $this->getFieldFromFlexform('settings.hidePagination', 'additional');

		if ($offset) {
			$this->tableData[] = array($this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.offset'), $offset);
		}
		if ($limit) {
			$this->tableData[] = array($this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.limit'), $limit);
		}
		if ($hidePagination) {
			$this->tableData[] = array($this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.hidePagination'), NULL);
		}
	}

	/**
	 * Render date menu configuration
	 *
	 * @return void
	 */
	public function getDateMenuSettings() {
		$dateMenuField = $this->getFieldFromFlexform('settings.dateField');

		$this->tableData[] = array(
			$this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.dateField'),
			$this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.dateField.' . $dateMenuField)
		);
	}

	/**
	 * Render time restriction configuration
	 *
	 * @return void
	 */
	public function getTimeRestrictionSetting() {
		$timeRestriction = $this->getFieldFromFlexform('settings.timeRestriction');

		if (!empty($timeRestriction)) {
			$this->tableData[] = array(
				$this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.timeRestriction'),
				htmlspecialchars($timeRestriction)
			);
		}

		$timeRestrictionHigh = $this->getFieldFromFlexform('settings.timeRestrictionHigh');
		if (!empty($timeRestrictionHigh)) {
			$this->tableData[] = array(
				$this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.timeRestrictionHigh'),
				htmlspecialchars($timeRestrictionHigh)
			);
		}
	}

	/**
	 * Render top news restriction configuration
	 *
	 * @return void
	 */
	public function getTopNewsRestrictionSetting() {
		$topNewsRestriction = (int)$this->getFieldFromFlexform('settings.topNewsRestriction');
		if ($topNewsRestriction > 0) {
			$this->tableData[] = array(
				$this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.topNewsRestriction'),
				$this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.topNewsRestriction.' . $topNewsRestriction)
			);
		}
	}

	/**
	 * Render template layout configuration
	 *
	 * @param int $pageUid
	 * @return void
	 */
	public function getTemplateLayoutSettings($pageUid) {
		$title = '';
		$field = $this->getFieldFromFlexform('settings.templateLayout', 'template');

		// Find correct title by looping over all options
		if (!empty($field)) {

			foreach ($this->templateLayoutsUtility->getAvailableTemplateLayouts($pageUid) as $layout) {
				if ($layout[1] === $field) {
					$title = $layout[0];
				}
			}
		}

		if (!empty($title)) {
			$this->tableData[] = array(
				$this->getLanguageService()->sL(self::LLPATH . 'flexforms_template.templateLayout'),
				$this->getLanguageService()->sL($title)
			);
		}
	}

	/**
	 * Get information if override demand setting is disabled or not
	 *
	 * @return void
	 */
	public function getOverrideDemandSettings() {
		$field = $this->getFieldFromFlexform('settings.disableOverrideDemand', 'additional');

		if ($field == 1) {
			$this->tableData[] = array($this->getLanguageService()->sL(
				self::LLPATH . 'flexforms_additional.disableOverrideDemand'), '');
		}
	}

	/**
	 * Get the startingpoint
	 *
	 * @return void
	 */
	public function getStartingPoint() {
		$value = $this->getFieldFromFlexform('settings.startingpoint');

		if (!empty($value)) {
			$pagesOut = array();
			$rawPagesRecords = $this->databaseConnection->exec_SELECTgetRows(
				'*',
				'pages',
				'deleted=0 AND uid IN(' . implode(',', GeneralUtility::intExplode(',', $value, TRUE)) . ')'
			);

			foreach ($rawPagesRecords as $page) {
				$pagesOut[] = htmlspecialchars(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('pages', $page)) . ' (' . $page['uid'] . ')';
			}

			$recursiveLevel = (int)$this->getFieldFromFlexform('settings.recursive');
			$recursiveLevelText = '';
			if ($recursiveLevel === 250) {
				$recursiveLevelText = $this->getLanguageService()->sL('LLL:EXT:cms/locallang_ttc.xlf:recursive.I.5');
			} elseif ($recursiveLevel > 0) {
				$recursiveLevelText = $this->getLanguageService()->sL('LLL:EXT:cms/locallang_ttc.xlf:recursive.I.' . $recursiveLevel);
			}

			if (!empty($recursiveLevelText)) {
				$recursiveLevelText = '<br />' .
					$this->getLanguageService()->sL('LLL:EXT:lang/locallang_general.xlf:LGL.recursive', TRUE) . ' ' .
					$recursiveLevelText;
			}

			$this->tableData[] = array(
				$this->getLanguageService()->sL('LLL:EXT:lang/locallang_general.php:LGL.startingpoint'),
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
			$content .= '<strong>' . $line[0] . '</strong>' . ' ' . $line[1] . '<br />';
		}

		return '<pre style="white-space:normal">' . $content . '</pre>';
	}

	/**
	 * Get field value from flexform configuration,
	 * including checks if flexform configuration is available
	 *
	 * @param string $key name of the key
	 * @param string $sheet name of the sheet
	 * @return string|NULL if nothing found, value if found
	 */
	public function getFieldFromFlexform($key, $sheet = 'sDEF') {
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
	 * Return language service instance
	 *
	 * @return \TYPO3\CMS\Lang\LanguageService
	 */
	public function getLanguageService() {
		return $GLOBALS['LANG'];
	}
}