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
 * Hook into \TYPO3\CMS\Backend\Utility\BackendUtility to change flexform behaviour
 * depending on action selection
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Hooks_T3libBefunc {

	/**
	 * Fields which are removed in detail view
	 *
	 * @var array
	 */
	public $removedFieldsInDetailView = array(
			'sDEF' => 'orderBy,orderDirection,categories,categoryConjunction,includeSubCategories,
						archiveRestriction,timeRestriction,timeRestrictionHigh,topNewsRestriction,
						dateField',
			'additional' => 'limit,offset,hidePagination,topNewsFirst,listPid',
			'template' => 'cropMaxCharacters'
		);

	/**
	 * Fields which are removed in list view
	 *
	 * @var array
	 */
	public $removedFieldsInListView = array(
			'sDEF' => 'dateField,singleNews,previewHiddenRecords',
			'additional' => '',
			'template' => ''
		);

	/**
	 * Fields which are removed in dateMenu view
	 *
	 * @var array
	 */
	public $removedFieldsInDateMenuView = array(
			'sDEF' => 'orderBy,singleNews',
			'additional' => 'limit,offset,hidePagination,topNewsFirst,detailPid,backPid,previewHiddenRecords,excludeAlreadyDisplayedNews',
			'template' => 'cropMaxCharacters,media.maxWidth,media.maxHeight'
		);

	/**
	 * Fields which are removed in search form view
	 *
	 * @var array
	 */
	public $removedFieldsInSearchFormView = array(
			'sDEF' => 'orderBy,orderDirection,categories,categoryConjunction,includeSubCategories,
						archiveRestriction,timeRestriction,timeRestrictionHigh,topNewsRestriction,
						startingpoint,recursive,dateField,singleNews,previewHiddenRecords',
			'additional' => 'limit,offset,hidePagination,topNewsFirst,detailPid,backPid,excludeAlreadyDisplayedNews',
			'template' => 'cropMaxCharacters,media.maxWidth,media.maxHeight'
		);

	/**
	 * Fields which are removed in category list view
	 *
	 * @var array
	 */
	public $removedFieldsInCategoryListView = array(
			'sDEF' => 'orderBy,orderDirection,categoryConjunction,includeSubCategories,
						archiveRestriction,timeRestriction,timeRestrictionHigh,topNewsRestriction,
						startingpoint,recursive,dateField,singleNews,previewHiddenRecords',
			'additional' => 'limit,offset,hidePagination,topNewsFirst,detailPid,backPid,excludeAlreadyDisplayedNews',
			'template' => 'cropMaxCharacters,media.maxWidth,media.maxHeight'
		);

	/**
	 * Fields which are removed in tag list view
	 *
	 * @var array
	 */
	public $removedFieldsInTagListView = array(
			'sDEF' => 'categories,categoryConjunction,includeSubCategories,
						archiveRestriction,timeRestriction,timeRestrictionHigh,topNewsRestriction,
						dateField,singleNews,previewHiddenRecords',
			'additional' => 'limit,offset,hidePagination,topNewsFirst,detailPid,backPid,excludeAlreadyDisplayedNews',
			'template' => 'cropMaxCharacters,media.maxWidth,media.maxHeight'
		);
	/**
	 * Hook function of \TYPO3\CMS\Backend\Utility\BackendUtility
	 * It is used to change the flexform if it is about news
	 *
	 * @param array &$dataStructure Flexform structure
	 * @param array $conf some strange configuration
	 * @param array $row row of current record
	 * @param string $table table name
	 * @return void
	 */
	public function getFlexFormDS_postProcessDS(&$dataStructure, $conf, $row, $table) {
		if ($table === 'tt_content' && $row['list_type'] === 'news_pi1' && is_array($dataStructure)) {
			$this->updateFlexforms($dataStructure, $row);
		}
	}

	/**
	 * Update flexform configuration if a action is selected
	 *
	 * @param array|string &$dataStructure flexform structure
	 * @param array $row row of current record
	 * @return void
	 */
	protected function updateFlexforms(array &$dataStructure, array $row) {
		$selectedView = '';

			// get the first selected action
		if (is_string($row['pi_flexform'])) {
			$flexformSelection = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($row['pi_flexform']);
		} else {
			$flexformSelection = $row['pi_flexform'];
		}
		if (is_array($flexformSelection) && is_array($flexformSelection['data'])) {
			$selectedView = $flexformSelection['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];
			if (!empty($selectedView)) {
				$actionParts = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(';', $selectedView, TRUE);
				$selectedView = $actionParts[0];
			}

			// new plugin element
		} elseif (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($row['uid'], 'NEW')) {
				// use List as starting view
			$selectedView = 'News->list';
		}

		if (!empty($selectedView)) {
				// Modify the flexform structure depending on the first found action
			switch ($selectedView) {
				case 'News->list':
				case 'News->searchResult':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInListView);
					break;
				case 'News->detail':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInDetailView);
					break;
				case 'News->searchForm':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInSearchFormView);
					break;
				case 'News->dateMenu':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInDateMenuView);
					break;
				case 'Category->list':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInCategoryListView);
					break;
				case 'Tag->list':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInTagListView);
					break;
				default:
			}

			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Hooks/T3libBefunc.php']['updateFlexforms'])) {
				$params = array(
					'selectedView' => $selectedView,
					'dataStructure' => &$dataStructure,
				);
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Hooks/T3libBefunc.php']['updateFlexforms'] as $reference) {
					\TYPO3\CMS\Core\Utility\GeneralUtility::callUserFunction($reference, $params, $this);
				}
			}
		}
	}

	/**
	 * Remove fields from flexform structure
	 *
	 * @param array &$dataStructure flexform structure
	 * @param array $fieldsToBeRemoved fields which need to be removed
	 * @return void
	 */
	protected function deleteFromStructure(array &$dataStructure, array $fieldsToBeRemoved) {
		foreach ($fieldsToBeRemoved as $sheetName => $sheetFields) {
			$fieldsInSheet = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $sheetFields, TRUE);

			foreach ($fieldsInSheet as $fieldName) {
				unset($dataStructure['sheets'][$sheetName]['ROOT']['el']['settings.' . $fieldName]);
			}
		}
	}
}