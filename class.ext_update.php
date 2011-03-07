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
 * Update class for extmgr.
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class ext_update {
	const STATUS_WARNING = -1;
	const STATUS_ERROR = 0;
	const STATUS_OK = 1;

	protected $messageArray = array();

	/**
	 * Renames a tabled field and does some plausibility checks.
	 *
	 * @param  string $table
	 * @param  string $oldFieldName
	 * @param  string $newFieldName
	 * @return int
	 */
	protected function renameDatabaseTableField($table, $oldFieldName, $newFieldName) {
		$title = 'Renaming "' . $table . ':' . $oldFieldName . '" to "' . $table . ':' . $newFieldName . '": ';

		$currentTableFields = $GLOBALS['TYPO3_DB']->admin_get_fields($table);

		if ($currentTableFields[$newFieldName]) {
			$message = 'Field ' . $table . ':' . $newFieldName . ' already existing.';
			$status = self::STATUS_WARNING;
		} else {
			if (!$currentTableFields[$oldFieldName]) {
				$message = 'Field ' . $table . ':' . $oldFieldName . ' not existing';
				$status = self::STATUS_ERROR;
			} else {
				$sql = 'ALTER TABLE ' . $table . ' CHANGE COLUMN ' . $oldFieldName . ' ' . $newFieldName . ' ' .
					$currentTableFields[$oldFieldName]['Type'];

				if ($GLOBALS['TYPO3_DB']->admin_query($sql) === FALSE) {
					$message = ' SQL ERROR: ' .  $GLOBALS['TYPO3_DB']->sql_error();
					$status = self::STATUS_ERROR;
				} else {
					$message = 'OK!';
					$status = self::STATUS_OK;
				}

			}
		}

		$this->messageArray[] = array($status, $title, $message);
		return $status;
	}

	/**
	 * Renames a flex form field
	 *
	 * @param  string $pluginName The pluginName used in list_type
	 * @param  array $oldFieldPointer Pointer array the old field. E.g. array('sheetName', 'fieldName');
	 * @param  array $newFieldPointer  Pointer array the new field. E.g. array('sheetName', 'fieldName');
	 * @return void
	 */
	protected function renameFlexformField($pluginName, array $oldFieldPointer, array $newFieldPointer) {
		$title = 'Renaming flexform field for "' .  $pluginName . '" - ' .
			' sheet: ' . $oldFieldPointer[0] . ', field: ' .  $oldFieldPointer[1] . ' to ' .
			' sheet: ' . $newFieldPointer[0] . ', field: ' .  $newFieldPointer[1];

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid, pi_flexform',
			'tt_content',
			'CType=\'list\' AND list_type=\'' . $pluginName . '\'');

		$flexformTools = t3lib_div::makeInstance('t3lib_flexformtools');

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

			$xmlArray = t3lib_div::xml2array($row['pi_flexform']);

			if (!$xmlArray['data'][$oldFieldPointer[0]]) {
				$status = self::STATUS_WARNING;
				$message = 'Flexform data of record tt_content:' . $row['uid'] . ' did not contain ' .
					'sheet: ' . $oldFieldPointer[0];
			} else {
				$updated = FALSE;

				foreach ($xmlArray['data'][$oldFieldPointer[0]] as $language => $fields) {
					if ($fields[$oldFieldPointer[1]]) {

						$xmlArray['data'][$newFieldPointer[0]][$language][$newFieldPointer[1]] = $fields[$oldFieldPointer[1]];
						unset($xmlArray['data'][$oldFieldPointer[0]][$language][$oldFieldPointer[1]]);

						$updated = TRUE;
					}
				}

				if ($updated === TRUE) {
					$GLOBALS['TYPO3_DB']->UPDATEquery('tt_content', 'uid=' . $row['uid'], array(
						'pi_flexform' => $flexformTools->flexArray2Xml($xmlArray)
					));

					$message = 'OK!';
					$status = self::STATUS_OK;
				} else {
					$status = self::STATUS_WARNING;
					$message = 'Flexform data of record tt_content:' . $row['uid'] . ' did not contain ' .
						'sheet: ' . $oldFieldPointer[0] . ', field: ' .  $oldFieldPointer[1];
				}
			}

			$this->messageArray[] = array($status, $title, $message);
		}
	}


	/**
	 * Main update function called by the extension manager.
	 *
	 * @return string
	 */
	public function main() {
		$this->processUpdates();
		return $this->generateOutput();
	}

	/**
	 * The actual update function. Add your update task in here.
	 *
	 * @return void
	 */
	protected function processUpdates() {
		$this->renameDatabaseTableField('tx_news2_domain_model_news', 'category', 'categories');

		$this->renameFlexformField('news2_pi1', array('sDEF', 'orderAscDesc'), array('sDEF', 'orderDirection'));
		$this->renameFlexformField('news2_pi1', array('sDEF', 'category'), array('sDEF', 'categories'));
		$this->renameFlexformField('news2_pi1', array('additional', 'pidDetail'), array('additional', 'detailPid'));
		$this->renameFlexformField('news2_pi1', array('sDEF', 'categoryMode'), array('sDEF', 'categoryConjunction'));
		$this->renameFlexformField('news2_pi1', array('sDEF', 'archive'), array('sDEF', 'archiveRestriction'));
		$this->renameFlexformField('news2_pi1', array('sDEF', 'timeLimit'), array('sDEF', 'timeRestriction'));
		$this->renameFlexformField('news2_pi1', array('sDEF', 'topNews'), array('sDEF', 'topNewsRestriction'));
		$this->renameFlexformField('news2_pi1', array('additional', 'pidBack'), array('additional', 'backPid'));
		$this->renameFlexformField('news2_pi1', array('additional', 'orderByRespectTopNews'), array('additional', 'topNewsFirst'));
		$this->renameFlexformField('news2_pi1', array('template', 'cropLength'), array('template', 'cropMaxCharacters'));
	}

	/**
	 * Generates more or less readable output.
	 *
	 * @todo: beautify output :)
	 * @return string
	 */
	protected function generateOutput() {
		$output = '';
		foreach ($this->messageArray as $messageItem) {
			$output .= '<strong>' . $messageItem[1] . '</strong><br />' .
				'&nbsp;&nbsp;&nbsp;->' . $messageItem[2] . '<br /><br />';
		}

		return $output;
	}

	/**
	 * Called by the extension manager to determine if the update menu entry should by showed.
	 *
	 * @return bool
	 * @todo find a better way to determine if update is needed or not.
	 */
	public function access() {
		return TRUE;
	}
}
?>