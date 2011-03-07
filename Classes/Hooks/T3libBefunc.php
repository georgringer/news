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
 * Hook into t3lib_befunc to change flexform behaviour
 * depending on action selection
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class tx_News2_Hooks_T3libBefunc {

	/**
	 * Hook function of t3lib_befunc
	 * It is used to change the flexform if it is about news2
	 *
	 * @param array &$dataStructure Flexform structure
	 * @param array $conf some strange configuration
	 * @param array $row row of current record
	 * @param string $table table anme
	 * @param string $fieldName some strange field name
	 * @return void
	 */
	public function getFlexFormDS_postProcessDS(&$dataStructure, $conf, $row, $table, $fieldName) {
		if ($table === 'tt_content' && $row['list_type'] === 'news2_pi1' && is_array($dataStructure)) {
			$this->updateFlexforms($dataStructure, $row);
		}
	}

	/**
	 * Update flexform configuration if a action is selected
	 *
	 * @param array|string &$dataStructure flexform structur
	 * @param array $row row of current record
	 * @return void
	 */
	protected function updateFlexforms(array &$dataStructure, array $row) {
		$selectedView = '';

			// get the first selected action
		$flexformSelection = t3lib_div::xml2array($row['pi_flexform']);
		if (is_array($flexformSelection) && is_array($flexformSelection['data'])) {
			$selectedView = $flexformSelection['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];
			if (!empty($selectedView)) {
				$actionParts = t3lib_div::trimExplode(';', $selectedView, TRUE);
				$selectedView = $actionParts[0];
			}

			// new plugin element
		} elseif(t3lib_div::isFirstPartOfStr($row['uid'], 'NEW')) {
				// use List as starting view
				// @todo dynamic check, getting view from $flexformSelection
			$selectedView = 'News->list';
		}

		if (!empty($selectedView)) {
				// modify the flexform structure depending on the first found action
			switch ($selectedView) {
				case 'News->list':
				case 'News->searchResult':
					$this->updateForNewsListAction($dataStructure);
					break;
				case 'News->detail':
					$this->updateForNewsDetailAction($dataStructure);
					break;
				case 'News->searchForm':
					$this->updateForSearchFormAction($dataStructure);
					break;
				case 'News->dateMenu':
					$this->updateForDateMenuAction($dataStructure);
					break;
			}
		}
	}

	/**
	 * Change flexform for News->list which is the overall used list view
	 *
	 * @param array &$dataStructure flexform structure
	 * @return void
	 */
	protected function updateForNewsListAction(array &$dataStructure) {
		$fieldsToBeRemoved = array(
			'sDEF' => 'dateField,singleNews',
			'additional' => 'listPid',
			'template' => ''
		);

		$this->deleteFromStructure($dataStructure, $fieldsToBeRemoved);
	}

	/**
	 * Change flexform for News->detail which is the single view of a news record
	 *
	 * @param array &$dataStructure flexform structure
	 * @return void
	 */
	protected function updateForNewsDetailAction(array &$dataStructure) {
		$fieldsToBeRemoved = array(
			'sDEF' => 'orderBy,orderDirection,categories,categoryConjunction,archiveRestriction,timeRestriction,topNewsRestriction,startingpoint,recursive,dateField',
			'additional' => 'limit,offset,topNewsFirst,listPid',
			'template' => 'cropMaxCharacters'
		);

		$this->deleteFromStructure($dataStructure, $fieldsToBeRemoved);
	}

	/**
	 * Change flexform for News->detail which is the single view of a news record
	 *
	 * @param array &$dataStructure flexform structure
	 * @return void
	 */
	protected function updateForSearchFormAction(array &$dataStructure) {
		$fieldsToBeRemoved = array(
			'sDEF' => 'orderBy,orderDirection,categories,categoryConjunction,archiveRestriction,timeRestriction,topNewsRestriction,startingpoint,recursive,dateField,singleNews',
			'additional' => 'limit,offset,topNewsFirst,listPid',
			'template' => 'cropMaxCharacters,media.maxWidth,media.maxHeight'
		);

		$this->deleteFromStructure($dataStructure, $fieldsToBeRemoved);
	}

	/**
	 * Change flexform for News->detail which is the single view of a news record
	 *
	 * @param array &$dataStructure flexform structure
	 * @return void
	 */
	protected function updateForDateMenuAction(array &$dataStructure) {
		$fieldsToBeRemoved = array(
			'sDEF' => 'orderBy,orderDirection,singleNews',
			'additional' => 'limit,offset,topNewsFirst,detailPid,backPid',
			'template' => 'cropMaxCharacters,media.maxWidth,media.maxHeight'
		);

		$this->deleteFromStructure($dataStructure, $fieldsToBeRemoved);
	}


	/**
	 * Remove fields from flexform structure
	 *
	 * @param array &$dataStructure flexform structure
	 * @param array $fieldsToBeRemoved fields which need to be removed
	 * @return void
	 */
	private function deleteFromStructure(array &$dataStructure, array $fieldsToBeRemoved) {
		foreach ($fieldsToBeRemoved as $sheetName => $sheetFields) {
			$fieldsInSheet = explode(',', $sheetFields);

			foreach ($fieldsInSheet as $fieldName) {
				unset($dataStructure['sheets'][$sheetName]['ROOT']['el']['settings.' . $fieldName]);
			}
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Classes/Hooks/T3libBefunc.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Classes/Hooks/T3libBefunc.php']);
}

?>