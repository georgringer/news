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
 * Update class for the extension manager.
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class ext_update {
	const STATUS_WARNING = -1;
	const STATUS_ERROR = 0;
	const STATUS_OK = 1;

	protected $messageArray = array();

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
	 * Called by the extension manager to determine if the update menu entry
	 * should by showed.
	 *
	 * @return bool
	 * @todo find a better way to determine if update is needed or not.
	 */
	public function access() {
		return TRUE;
	}

	/**
	 * The actual update function. Add your update task in here.
	 *
	 * @return void
	 */
	protected function processUpdates() {

		$this->updateContentRelationToMm();

		/*
		$this->renameDatabaseTable('tx_news2_domain_model_news', 'tx_news_domain_model_news');
		$this->renameDatabaseTable('tx_news2_domain_model_category', 'tx_news_domain_model_category');
		$this->renameDatabaseTable('tx_news2_domain_model_news_category_mm', 'tx_news_domain_model_news_category_mm');
		$this->renameDatabaseTable('tx_news2_domain_model_news_related_mm', 'tx_news_domain_model_news_related_mm');
		$this->renameDatabaseTable('tx_news2_domain_model_media', 'tx_news_domain_model_media');
		$this->renameDatabaseTable('tx_news2_domain_model_news_file_mm', 'tx_news_domain_model_news_file_mm');
		$this->renameDatabaseTable('tx_news2_domain_model_file', 'tx_news_domain_model_file');
		$this->renameDatabaseTable('tx_news2_domain_model_link', 'tx_news_domain_model_link');
		$this->renameDatabaseTable('tx_news2_domain_model_tag', 'tx_news_domain_model_tag');
		$this->renameDatabaseTable('tx_news2_domain_model_news_tag_mm', 'tx_news_domain_model_news_tag_mm');
		$this->renameNews2toNews();


		$this->renameDatabaseTableField('tx_news_domain_model_news', 'category', 'categories');

		$this->renameFlexformField('news_pi1', array('sDEF', 'settings.category'), array('sDEF', 'settings.categories'));
		$this->renameFlexformField('news_pi1', array('sDEF', 'settings.orderAscDesc'), array('sDEF', 'settings.orderDirection'));
		$this->renameFlexformField('news_pi1', array('additional', 'settings.pidDetail'), array('additional', 'settings.detailPid'));
		$this->renameFlexformField('news_pi1', array('sDEF', 'settings.categoryMode'), array('sDEF', 'settings.categoryConjunction'));
		$this->renameFlexformField('news_pi1', array('sDEF', 'settings.archive'), array('sDEF', 'settings.archiveRestriction'));
		$this->renameFlexformField('news_pi1', array('sDEF', 'settings.timeLimit'), array('sDEF', 'settings.timeRestriction'));
		$this->renameFlexformField('news_pi1', array('sDEF', 'settings.topNews'), array('sDEF', 'settings.topNewsRestriction'));
		$this->renameFlexformField('news_pi1', array('additional', 'settings.pidBack'), array('additional', 'settings.backPid'));
		$this->renameFlexformField('news_pi1', array('additional', 'settings.orderByRespectTopNews'), array('additional', 'settings.topNewsFirst'));
		$this->renameFlexformField('news_pi1', array('template', 'settings.cropLength'), array('template', 'settings.cropMaxCharacters'));

		$this->renameDatabaseTableField('tx_news_domain_model_media', 'content', 'image');
		*/
	}

	/**
	 * news records got a relation to content elements and the relation uses now a mm query
	 * This method allows to update the mm table to got everything in sync again
	 *
	 * @return void
	 */
	protected function updateContentRelationToMm() {
		$title = 'Update tt_content relation';

		$countMmTable = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows('*', 'tx_news_domain_model_news_ttcontent_mm', '1=1');
		$countContentElementRelation = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows('*', 'tx_news_domain_model_news', 'deleted=0 AND content_elements != ""');
		if ($countMmTable === 0 && $countContentElementRelation > 0) {
			$newsCount = 0;
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,content_elements', 'tx_news_domain_model_news', 'deleted=0 AND content_elements != ""');
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$newsCount++;
				$contentElementUids = explode(',', $row['content_elements']);
				$i = 1;
				foreach ($contentElementUids as $contentElement) {
						// Insert mm relation
					$insert = array(
						'uid_local' => $row['uid'],
						'uid_foreign' => $contentElement,
						'sorting' => $i++
					);
					$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_news_domain_model_news_ttcontent_mm', $insert);
				}

					// Update new record
				$update = array('content_elements' => count($contentElementUids));
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_news_domain_model_news', 'uid=' . $row['uid'], $update);
			}
			$GLOBALS['TYPO3_DB']->sql_free_result($res);

			$this->messageArray[] = array(t3lib_FlashMessage::OK, $title, $newsCount . ' news records have been updated!');
		} else {
			$this->messageArray[] = array(t3lib_FlashMessage::NOTICE, $title, 'Not needed/possible anymore as the mm table is already filled!');
		}
	}

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
		$message = '';
		$status = NULL;

		$currentTableFields = $GLOBALS['TYPO3_DB']->admin_get_fields($table);

		if ($currentTableFields[$newFieldName]) {
			$message = 'Field ' . $table . ':' . $newFieldName . ' already existing.';
			$status = t3lib_FlashMessage::OK;
		} else {
			if (!$currentTableFields[$oldFieldName]) {
				$message = 'Field ' . $table . ':' . $oldFieldName . ' not existing';
				$status = t3lib_FlashMessage::ERROR;
			} else {
				$sql = 'ALTER TABLE ' . $table . ' CHANGE COLUMN ' . $oldFieldName . ' ' . $newFieldName . ' ' .
					$currentTableFields[$oldFieldName]['Type'];

				if ($GLOBALS['TYPO3_DB']->admin_query($sql) === FALSE) {
					$message = ' SQL ERROR: ' .  $GLOBALS['TYPO3_DB']->sql_error();
					$status = t3lib_FlashMessage::ERROR;
				} else {
					$message = 'OK!';
					$status = t3lib_FlashMessage::OK;
				}

			}
		}

		$this->messageArray[] = array($status, $title, $message);
		return $status;
	}

	/**
	 * Rename a DB  table
	 *
	 * @param string $oldTableName old table name
	 * @param string $newTableName new table name
	 * @return boolean
	 */
	protected function renameDatabaseTable($oldTableName, $newTableName) {
		$message = '';
		$status = NULL;
		$title = 'Renaming "' . $oldTableName . '" to "' . $newTableName . '" ';

		$tables = $GLOBALS['TYPO3_DB']->admin_get_tables();
		if (isset($tables[$newTableName])) {
			$message = 'Table ' . $newTableName . ' already exists';
			$status = t3lib_FlashMessage::OK;
		} elseif(!isset($tables[$oldTableName])) {
			$message = 'Table ' . $oldTableName . ' does not exist';
			$status = t3lib_FlashMessage::ERROR;
		} else {
			$sql = 'RENAME TABLE ' . $oldTableName .' TO ' . $newTableName . ';';

			if ($GLOBALS['TYPO3_DB']->admin_query($sql) === FALSE) {
				$message = ' SQL ERROR: ' .  $GLOBALS['TYPO3_DB']->sql_error();
				$status = t3lib_FlashMessage::ERROR;
			} else {
				$message = 'OK!';
				$status = t3lib_FlashMessage::OK;
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

			if (!is_array($xmlArray) || !isset($xmlArray['data'])) {
				$status = t3lib_FlashMessage::ERROR;
					// @todo: This will happen when trying to update news2 > news but pluginName is already set to news
					// proposal for future: check for news2 somehow?
				$message = 'Flexform data of plugin "' . $pluginName . '" not found.';
			} elseif (!$xmlArray['data'][$oldFieldPointer[0]]) {
				$status = t3lib_FlashMessage::WARNING;
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

					$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid=' . $row['uid'], array(
						'pi_flexform' => $flexformTools->flexArray2Xml($xmlArray)
					));

					$message = 'OK!';
					$status = t3lib_FlashMessage::OK;
				} else {
					$status = t3lib_FlashMessage::NOTICE;
					$message = 'Flexform data of record tt_content:' . $row['uid'] . ' did not contain ' .
						'sheet: ' . $oldFieldPointer[0] . ', field: ' .  $oldFieldPointer[1] . '. This can
						also be because field has been updated already...';
				}
			}

			$this->messageArray[] = array($status, $title, $message);
		}
	}

	/**
	 * Rename news2 to news: Including tt_content & uploads tx_news2
	 *
	 * @return void
	 */
	protected function renameNews2toNews() {
		$title = 'Renaming news2 to news';
		$message = '';
		$status = NULL;

			// update tt_content to match list_type again
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
			'tt_content',
			'list_type="news2_pi1"',
			array(
				'list_type' => 'news_pi1'
			));
		$affectedRows = $GLOBALS['TYPO3_DB']->sql_affected_rows();
		if ($affectedRows == 0) {
			$status = t3lib_FlashMessage::OK;
			$message = 'No rows updated in tt_content, everything seems fine';
		} else {
			$status = t3lib_FlashMessage::OK;
			$message = (int)$affectedRows . ' rows changed';
		}

		$this->messageArray[] = array($status, $title, $message);

			// get affected news2 ts records
		$affectedTsFiles = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'sys_template',
			'deleted=0 AND (include_static_file LIKE "%news2%" OR constants LIKE "%news2%" OR config LIKE "%news2%")'
		);
		if (count($affectedTsFiles) == 0) {
			$status = t3lib_FlashMessage::OK;
			$message = '"news" has not been found in the TS files *inside the database*. No search has been done in external TS files!';
		} else {
			$status = t3lib_FlashMessage::WARNING;
			$messageTmp = array();
			foreach ($affectedTsFiles as $tsFile) {
				$messageTmp[] = sprintf('"News2" found in TS record with title "%s" on page with uid "%s".', $tsFile['title'], $tsFile['pid']);
			}
			$message = implode('<br />', $messageTmp);
		}

		$this->messageArray[] = array($status, $title, $message);

			// try to rename directory
		if (is_dir('../uploads/tx_news2/')) {
			if (is_dir('../uploads/tx_news/')) {
				$status = t3lib_FlashMessage::ERROR;
				$message = 'Directory uploads/tx_news/ already exists. Move all files from uploads/tx_news2/ to uploads/tx_news/ and delete the old directory.';
			} else {
					// try to rename directory
				$renameStatus = rename('../uploads/tx_news2/', '../uploads/tx_news/');
				if ($renameStatus) {
					$status = t3lib_FlashMessage::OK;
					$message = 'Directory uploads/tx_news2/ has been renamed';
				} else {
					$status = t3lib_FlashMessage::ERROR;
					$message = 'Directory uploads/tx_news2/ could not be renamed to uploads/tx_news/. Solve it manually!';
				}
			}

		} elseif (is_dir('../uploads/tx_news/')) {
				$status = t3lib_FlashMessage::OK;
				$message = 'No action needed, directory uploads/tx_news exists and uploads/tx_news2 doesn\'t.';
		} else {
			$status = t3lib_FlashMessage::ERROR;
			$message = 'None of the directories uploads/tx_news/ and uploads/tx_news/ exist. Data lost or just no directory there? Solve it manually!';
		}

		$this->messageArray[] = array($status, $title, $message);
	}

	/**
	 * Generates output by using flash messages
	 *
	 * @return string
	 */
	protected function generateOutput() {
		$output = '';
		foreach ($this->messageArray as $messageItem) {
			$flashMessage = t3lib_div::makeInstance(
					't3lib_FlashMessage',
					$messageItem[2],
					$messageItem[1],
					$messageItem[0]);
			$output .= $flashMessage->render();
		}

		return $output;
	}

}
?>