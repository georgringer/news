<?php
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

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Update class for the extension manager.
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class ext_update {

	const FOLDER_CATEGORY_IMAGES = '/_migrated/news_categories';

	/**
	 * Array of flash messages (params) array[][status,title,message]
	 *
	 * @var array
	 */
	protected $messageArray = array();

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @var \TYPO3\CMS\Core\Resource\ResourceFactory
	 */
	protected $resourceFactory;

	/**
	 * @var \TYPO3\CMS\Core\Resource\Folder
	 */
	protected $categoryImageFolder;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];

		$this->resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');

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
		$this->updateFileRelations();

		$this->migrateNewsCategoriesToSysCategories();
	}

	/**
	 *  Updates relations between news records and files.
	 *
	 * @return void
	 */
	protected function updateFileRelations() {
		$title = "Update related files";
		$countNewsWithFileRelation = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows('*', 'tx_news_domain_model_news', 'deleted=0 AND related_files != ""');
		$countFilesWithParent = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows('*', 'tx_news_domain_model_file', 'deleted=0 AND parent != 0');
		if ($countFilesWithParent === 0 && $countNewsWithFileRelation > 0) {
			$newsCount = 0;
			// select news with related files
			$newsQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,related_files', 'tx_news_domain_model_news', 'deleted=0 AND related_files !=""');
			while ($newsRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($newsQuery)) {
				$newsCount++;
				$news = $newsRow['uid'];
				$relatedFilesUids = explode(',', $newsRow['related_files']);
				// update news
				$update = array('related_files' => count($relatedFilesUids));
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_news_domain_model_news', 'uid=' . $news, $update);
				foreach ($relatedFilesUids as $relatedFile) {
					// update file
					$update = array('parent' => $news);
					$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_news_domain_model_file', 'uid=' . $relatedFile, $update);
				}
			}
			$GLOBALS['TYPO3_DB']->sql_free_result($newsQuery);

			$this->messageArray[] = array(FlashMessage::OK, $title, $newsCount . ' news records have been updated!');
		} else {
			if ($countNewsWithFileRelation == 0) {
				$this->messageArray[] = array(FlashMessage::NOTICE, $title, 'Nothing to do! There are no news with related files');
			}
			if ($countFilesWithParent != 0) {
				$this->messageArray[] = array(FlashMessage::NOTICE, $title, 'Can not update because there are files with new relation model!');
			}
		}
	}

	/**
	 * news records got a relation to content elements and the relation uses now a mm query
	 * This method allows to update the mm table to got everything in sync again
	 *
	 * @return void
	 */
	protected function updateContentRelationToMm() {
		$title = 'Update tt_content relation';

		$countMmTable = $this->databaseConnection->exec_SELECTcountRows('*', 'tx_news_domain_model_news_ttcontent_mm', '1=1');
		$countContentElementRelation = $this->databaseConnection->exec_SELECTcountRows('*', 'tx_news_domain_model_news', 'deleted=0 AND content_elements != ""');
		if ($countMmTable === 0 && $countContentElementRelation > 0) {
			$newsCount = 0;
			$res = $this->databaseConnection->exec_SELECTquery('uid,content_elements', 'tx_news_domain_model_news', 'deleted=0 AND content_elements != ""');
			while ($row = $this->databaseConnection->sql_fetch_assoc($res)) {
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
					$this->databaseConnection->exec_INSERTquery('tx_news_domain_model_news_ttcontent_mm', $insert);
				}

				// Update new record
				$update = array('content_elements' => count($contentElementUids));
				$this->databaseConnection->exec_UPDATEquery('tx_news_domain_model_news', 'uid=' . $row['uid'], $update);
			}
			$this->databaseConnection->sql_free_result($res);

			$this->messageArray[] = array(FlashMessage::OK, $title, $newsCount . ' news records have been updated!');
		} else {
			$this->messageArray[] = array(FlashMessage::NOTICE, $title, 'Not needed/possible anymore as the mm table is already filled!');
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

		$currentTableFields = $this->databaseConnection->admin_get_fields($table);

		if ($currentTableFields[$newFieldName]) {
			$message = 'Field ' . $table . ':' . $newFieldName . ' already existing.';
			$status = FlashMessage::OK;
		} else {
			if (!$currentTableFields[$oldFieldName]) {
				$message = 'Field ' . $table . ':' . $oldFieldName . ' not existing';
				$status = FlashMessage::ERROR;
			} else {
				$sql = 'ALTER TABLE ' . $table . ' CHANGE COLUMN ' . $oldFieldName . ' ' . $newFieldName . ' ' .
					$currentTableFields[$oldFieldName]['Type'];

				if ($this->databaseConnection->admin_query($sql) === FALSE) {
					$message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
					$status = FlashMessage::ERROR;
				} else {
					$message = 'OK!';
					$status = FlashMessage::OK;
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
		$title = 'Renaming "' . $oldTableName . '" to "' . $newTableName . '" ';

		$tables = $this->databaseConnection->admin_get_tables();
		if (isset($tables[$newTableName])) {
			$message = 'Table ' . $newTableName . ' already exists';
			$status = FlashMessage::OK;
		} elseif (!isset($tables[$oldTableName])) {
			$message = 'Table ' . $oldTableName . ' does not exist';
			$status = FlashMessage::ERROR;
		} else {
			$sql = 'RENAME TABLE ' . $oldTableName . ' TO ' . $newTableName . ';';

			if ($this->databaseConnection->admin_query($sql) === FALSE) {
				$message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
				$status = FlashMessage::ERROR;
			} else {
				$message = 'OK!';
				$status = FlashMessage::OK;
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
	 * @param  array $newFieldPointer Pointer array the new field. E.g. array('sheetName', 'fieldName');
	 * @return void
	 */
	protected function renameFlexformField($pluginName, array $oldFieldPointer, array $newFieldPointer) {
		$title = 'Renaming flexform field for "' . $pluginName . '" - ' .
			' sheet: ' . $oldFieldPointer[0] . ', field: ' . $oldFieldPointer[1] . ' to ' .
			' sheet: ' . $newFieldPointer[0] . ', field: ' . $newFieldPointer[1];

		$res = $this->databaseConnection->exec_SELECTquery('uid, pi_flexform',
			'tt_content',
			'CType=\'list\' AND list_type=\'' . $pluginName . '\'');

		/** @var \TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools $flexformTools */
		$flexformTools = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Configuration\\FlexForm\\FlexFormTools');

		while ($row = $this->databaseConnection->sql_fetch_assoc($res)) {

			$xmlArray = GeneralUtility::xml2array($row['pi_flexform']);

			if (!is_array($xmlArray) || !isset($xmlArray['data'])) {
				$status = FlashMessage::ERROR;
				// @todo: This will happen when trying to update news2 > news but pluginName is already set to news
				// proposal for future: check for news2 somehow?
				$message = 'Flexform data of plugin "' . $pluginName . '" not found.';
			} elseif (!$xmlArray['data'][$oldFieldPointer[0]]) {
				$status = FlashMessage::WARNING;
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
					$this->databaseConnection->exec_UPDATEquery('tt_content', 'uid=' . $row['uid'], array(
						'pi_flexform' => $flexformTools->flexArray2Xml($xmlArray)
					));
					$message = 'OK!';
					$status = FlashMessage::OK;
				} else {
					$status = FlashMessage::NOTICE;
					$message = 'Flexform data of record tt_content:' . $row['uid'] . ' did not contain ' .
						'sheet: ' . $oldFieldPointer[0] . ', field: ' . $oldFieldPointer[1] . '. This can
						also be because field has been updated already...';
				}
			}

			$this->messageArray[] = array($status, $title, $message);
		}
	}

	/**
	 * Check if there are records in the old category table and transfer
	 * these to sys_category when needed
	 */
	protected function migrateNewsCategoriesToSysCategories() {

		// check if tx_news_domain_model_category still exists
		$oldCategoryTableFields = $this->databaseConnection->admin_get_fields('tx_news_domain_model_category');
		if (count($oldCategoryTableFields) === 0) {
			$status = FlashMessage::NOTICE;
			$title = '';
			$message = 'Old category table does not exist anymore so no update needed';
			$this->messageArray[] = array($status, $title, $message);
			return;
		}

		// check if there are categories present else no update is needed
		$oldCategoryCount = $this->databaseConnection->exec_SELECTcountRows(
			'uid',
			'tx_news_domain_model_category',
			"deleted = 0"
		);

		if ($oldCategoryCount === 0) {
			$status = FlashMessage::NOTICE;
			$title = '';
			$message = 'No categories found in old table so no update needed';
			$this->messageArray[] = array($status, $title, $message);
			return;
		}

		// A temporary migration column is needed in old category table. Add this when not already present
		if (!array_key_exists('migrate_sys_category_uid', $oldCategoryTableFields)) {
			$this->databaseConnection->admin_query(
				"ALTER TABLE tx_news_domain_model_category ADD migrate_sys_category_uid int(11) DEFAULT '0' NOT NULL"
			);
		}

		// convert tx_news_domain_model_category records
		$this->migrateNewsCategoryRecords();

		// set/update all relations
		$oldNewCategoryUidMapping = $this->getOldNewCategoryUidMapping();
		$this->updateParentFieldOfMigratedCategories($oldNewCategoryUidMapping);
		$this->migrateCategoryMmRecords($oldNewCategoryUidMapping);
		$this->updateCategoryPermissionFields('be_users', $oldNewCategoryUidMapping);
		$this->migrateCategoryImages();
		$this->updateFlexformCategories('news_pi1', $oldNewCategoryUidMapping, 'settings.categories');

		/**
		 * Finished category migration
		 */
		$message = 'All categories are updated. Run <strong>DB compare</strong> in the install tool to remove the now obsolete `tx_news_domain_model_category` table and run the <strong>DB check</strong> to update the reference index.';
		$status = FlashMessage::OK;
		$title = 'Updated all categories!';
		$this->messageArray[] = array($status, $title, $message);
	}

	/**
	 * Process not yet migrated tx_news category records to sys_category records
	 */
	protected function migrateNewsCategoryRecords() {

		// migrate default language category records
		$rows = $this->databaseConnection->exec_SELECTgetRows(
			'uid, pid, tstamp, crdate, cruser_id, starttime, endtime, sorting, ' .
			'sys_language_uid, l10n_parent, l10n_diffsource, ' .
			'title, description, ' .
			'fe_group, single_pid, shortcut, import_id, import_source',
			'tx_news_domain_model_category',
			'migrate_sys_category_uid = 0 AND deleted = 0 AND sys_language_uid = 0'
		);

		if ($this->databaseConnection->sql_error()) {
			$message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
			$status = FlashMessage::ERROR;
			$title = 'Failed selecting old default language category records';
			$this->messageArray[] = array($status, $title, $message);
		}

		// Create a new sys_category record for each found record in default language, then
		$newCategoryRecords = 0;

		$oldNewDefaultLanguageCategoryUidMapping = array();
		foreach ($rows as $row) {
			$oldUid = $row['uid'];
			unset($row['uid']);

			if (is_null($row['l10n_diffsource'])) {
				$row['l10n_diffsource'] = '';
			}

			if (is_null($row['description'])) {
				$row['description'] = '';
			}

			if ($this->databaseConnection->exec_INSERTquery('sys_category', $row) !== FALSE) {
				$newUid = $this->databaseConnection->sql_insert_id();
				$oldNewDefaultLanguageCategoryUidMapping[$oldUid] = $newUid;
				$this->databaseConnection->exec_UPDATEquery(
					'tx_news_domain_model_category',
					'uid=' . $oldUid,
					array('migrate_sys_category_uid' => $newUid)
				);
				$newCategoryRecords++;
			} else {
				$message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
				$status = FlashMessage::ERROR;
				$title = 'Failed copying [' . $oldUid . '] ' . htmlspecialchars($row['title']) . ' to sys_category';
				$this->messageArray[] = array($status, $title, $message);
			}
		}

		// migrate non-default language category records
		$rows = $this->databaseConnection->exec_SELECTgetRows(
			'uid, pid, tstamp, crdate, cruser_id, starttime, endtime, sorting, ' .
			'sys_language_uid, l10n_parent, l10n_diffsource, ' .
			'title, description, ' .
			'fe_group, single_pid, shortcut, import_id, import_source',
			'tx_news_domain_model_category',
			'migrate_sys_category_uid = 0 AND deleted = 0 AND sys_language_uid != 0'
		);

		if ($this->databaseConnection->sql_error()) {
			$message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
			$status = FlashMessage::ERROR;
			$title = 'Failed selecting old non-default language category records';
			$this->messageArray[] = array($status, $title, $message);
		}

		foreach ($rows as $row) {
			$oldUid = $row['uid'];
			unset($row['uid']);

			if (is_null($row['l10n_diffsource'])) {
				$row['l10n_diffsource'] = '';
			}

			if (is_null($row['description'])) {
				$row['description'] = '';
			}
			// set l10n_parent if category is a localized version
			if (array_key_exists($row['l10n_parent'], $oldNewDefaultLanguageCategoryUidMapping)) {
				$row['l10n_parent'] = $oldNewDefaultLanguageCategoryUidMapping[$row['l10n_parent']];
			}

			if ($this->databaseConnection->exec_INSERTquery('sys_category', $row) !== FALSE) {
				$newUid = $this->databaseConnection->sql_insert_id();
				$oldNewDefaultLanguageCategoryUidMapping[$oldUid] = $newUid;
				$this->databaseConnection->exec_UPDATEquery(
					'tx_news_domain_model_category',
					'uid=' . $oldUid,
					array('migrate_sys_category_uid' => $newUid)
				);
				$newCategoryRecords++;
			} else {
				$message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
				$status = FlashMessage::ERROR;
				$title = 'Failed copying [' . $oldUid . '] ' . htmlspecialchars($row['title']) . ' to sys_category';
				$this->messageArray[] = array($status, $title, $message);
			}
		}

		$message = 'Created ' . $newCategoryRecords . ' sys_category records';
		$status = FlashMessage::INFO;
		$title = '';
		$this->messageArray[] = array($status, $title, $message);
	}

	/**
	 * Create a mapping array of old->new category uids
	 *
	 * @return array
	 */
	protected function getOldNewCategoryUidMapping() {
		$rows = $this->databaseConnection->exec_SELECTgetRows(
			'uid, migrate_sys_category_uid',
			'tx_news_domain_model_category',
			'migrate_sys_category_uid > 0'
		);

		$oldNewCategoryUidMapping = array();
		foreach ($rows as $row) {
			$oldNewCategoryUidMapping[$row['uid']] = $row['migrate_sys_category_uid'];
		}

		return $oldNewCategoryUidMapping;
	}

	/**
	 * Update parent column of migrated categories
	 *
	 * @param array $oldNewCategoryUidMapping
	 */
	protected function updateParentFieldOfMigratedCategories(array $oldNewCategoryUidMapping) {
		$updatedRecords = 0;
		$toUpdate = $this->databaseConnection->exec_SELECTgetRows('uid, parentcategory', 'tx_news_domain_model_category', 'parentcategory > 0');
		foreach ($toUpdate as $row) {
			if (!empty($oldNewCategoryUidMapping[$row['parentcategory']])) {
				$sysCategoryUid = $oldNewCategoryUidMapping[$row['uid']];
				$newParentUId = $oldNewCategoryUidMapping[$row['parentcategory']];
				$this->databaseConnection->exec_UPDATEquery('sys_category', 'uid=' . $sysCategoryUid, array('parent' => $newParentUId));
				$updatedRecords++;
			}
		}

		$message = 'Set for ' . $updatedRecords . ' sys_category records the parent field';
		$status = FlashMessage::INFO;
		$title = '';
		$this->messageArray[] = array($status, $title, $message);
	}

	/**
	 * Create new category MM records
	 *
	 * @param array $oldNewCategoryUidMapping
	 */
	protected function migrateCategoryMmRecords(array $oldNewCategoryUidMapping) {
		$newMmCount = 0;
		$oldMmRecords = $this->databaseConnection->exec_SELECTgetRows('uid_local, uid_foreign, tablenames, sorting', 'tx_news_domain_model_news_category_mm', '');
		foreach ($oldMmRecords as $oldMmRecord) {

			$oldCategoryUid = $oldMmRecord['uid_foreign'];

			if (!empty($oldNewCategoryUidMapping[$oldCategoryUid])) {
				$newMmRecord = array(
					'uid_local' => $oldNewCategoryUidMapping[$oldCategoryUid],
					'uid_foreign' => $oldMmRecord['uid_local'],
					'tablenames' => $oldMmRecord['tablenames'] ? : 'tx_news_domain_model_news',
					'sorting_foreign' => $oldMmRecord['sorting'],
					'fieldname' => 'categories',
				);

				// check if relation already exists
				$foundRelations = $this->databaseConnection->exec_SELECTcountRows(
					'uid_local',
					'sys_category_record_mm',
					'uid_local=' . $newMmRecord['uid_local'] .
					' AND uid_foreign=' . $newMmRecord['uid_foreign'] .
					' AND tablenames="' . $newMmRecord['tablenames'] . '"' .
					' AND fieldname="' . $newMmRecord['fieldname'] . '"'
				);

				if ($foundRelations === 0) {
					$this->databaseConnection->exec_INSERTquery('sys_category_record_mm', $newMmRecord);
					if ($this->databaseConnection->sql_affected_rows()) {
						$newMmCount++;
					}
				}
			}
		}

		$message = 'Created ' . $newMmCount . ' new MM relations';
		$status = FlashMessage::INFO;
		$title = '';
		$this->messageArray[] = array($status, $title, $message);
	}

	/**
	 * Update be_groups with tx_news_categorymounts set
	 *
	 * @param string $table
	 * @param array $oldNewCategoryUidMapping
	 */
	protected function updateCategoryPermissionFields($table, array $oldNewCategoryUidMapping) {

		$updatedRecords = 0;
		$rows = $this->databaseConnection->exec_SELECTgetRows(
			'*',
			$table,
			'1=1'
		);

		foreach ($rows as $row) {
			$oldUids = GeneralUtility::trimExplode(',', (string)$row['tx_news_categorymounts']);
			$newUids = $row['category_perms'] ? GeneralUtility::trimExplode(',', $row['category_perms']) : array();
			foreach ($oldUids as $oldUid) {
				if (!empty($oldNewCategoryUidMapping[$oldUid])) {
					$newUids[] = $oldNewCategoryUidMapping[$oldUid];
				}
			}

			$newCategoryPerms = implode(',', array_unique($newUids));
			if ($newCategoryPerms !== $row['category_perms']) {
				$this->databaseConnection->exec_UPDATEquery($table, 'uid=' . $row['uid'], array('category_perms' => $newCategoryPerms));
				$updatedRecords++;
			}
		}

		$message = 'Updated ' . $updatedRecords . ' "' . $table . '" records';
		$status = FlashMessage::INFO;
		$title = '';
		$this->messageArray[] = array($status, $title, $message);
	}

	/**
	 * Migrate news_category.image (CSV) to sys_category.images (sys_file_reference)
	 *
	 * @return void
	 */
	protected function migrateCategoryImages() {

		$oldCategories = $this->databaseConnection->exec_SELECTgetRows(
				'uid, pid, image, migrate_sys_category_uid',
				'tx_news_domain_model_category',
				'deleted=0 AND image!=""'
			);

		// no images to process then skip
		if (!count($oldCategories)) {
			return;
		}

		$processedImages = 0;
		foreach ($oldCategories as $oldCategory) {
			$files = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $oldCategory['image'], TRUE);

			$i = 0;
			foreach ($files as $file) {
				if (file_exists(PATH_site . 'uploads/tx_news/' . $file)) {
					$fileObject = $this->getCategoryImageFolder()->addFile(PATH_site . 'uploads/tx_news/'.$file);
					$dataArray = array(
						'uid_local' => $fileObject->getUid(),
						'tstamp' => $_SERVER['REQUEST_TIME'],
						'crdate' => $_SERVER['REQUEST_TIME'],
						'tablenames' => 'sys_category',
						'uid_foreign' => $oldCategory['migrate_sys_category_uid'],
						'pid' => $oldCategory['pid'],
						'fieldname' => 'images',
						'table_local' => 'sys_file',
						'sorting_foreign' => $i
					);
					$this->databaseConnection->exec_INSERTquery('sys_file_reference', $dataArray);
					$processedImages++;
				}
				$i++;
			}
		}

		$message = 'Migrated ' . $processedImages . ' category images';
		$status = FlashMessage::INFO;
		$title = '';
		$this->messageArray[] = array($status, $title, $message);
	}

	/**
	 * Update categories in flexforms
	 *
	 * @param string $pluginName
	 * @param array $oldNewCategoryUidMapping
	 * @param string $flexformField name of the flexform's field to look for
	 * @return void
	 */
	protected function updateFlexformCategories($pluginName, $oldNewCategoryUidMapping, $flexformField) {
		$count = 0;
		$title = 'Update flexforms categories (' . $pluginName . ':' . $flexformField . ')';
		$res = $this->databaseConnection->exec_SELECTquery('uid, pi_flexform',
			'tt_content',
			'CType=\'list\' AND list_type=\'' . $pluginName . '\' AND deleted=0');

		/** @var \TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools $flexformTools */
		$flexformTools = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Configuration\\FlexForm\\FlexFormTools');

		while ($row = $this->databaseConnection->sql_fetch_assoc($res)) {

			$status = NULL;
			$xmlArray = GeneralUtility::xml2array($row['pi_flexform']);

			if (!is_array($xmlArray) || !isset($xmlArray['data'])) {
				$status = FlashMessage::ERROR;
				$message = 'Flexform data of plugin "' . $pluginName . '" not found.';
			} elseif (!isset($xmlArray['data']['sDEF']['lDEF'])) {
				$status = FlashMessage::WARNING;
				$message = 'Flexform data of record tt_content:' . $row['uid'] . ' did not contain sheet: sDEF';
			} elseif (isset($xmlArray[$flexformField . '_updated'])) {
				$status = FlashMessage::NOTICE;
				$message = 'Flexform data of record tt_content:' . $row['uid'] . ' is already updated for ' . $flexformField . '. No update needed...';
			} else {
				// Some flexforms may have displayCond
				if (isset($xmlArray['data']['sDEF']['lDEF'][$flexformField]['vDEF'])) {
					$updated = FALSE;
					$oldCategories = GeneralUtility::trimExplode(',', $xmlArray['data']['sDEF']['lDEF'][$flexformField]['vDEF'], TRUE);

					if (!empty($oldCategories)) {
						$newCategories = array();

						foreach ($oldCategories as $uid) {
							if (isset($oldNewCategoryUidMapping[$uid])) {
								$newCategories[] = $oldNewCategoryUidMapping[$uid];
								$updated = TRUE;
							} else {
								$status = FlashMessage::WARNING;
								$message = 'The category ' . $uid . ' of record tt_content:' . $row['uid'] . ' was not found in sys_category records. Maybe the category was deleted before the migration? Please check manually...';
							}
						}

						if ($updated) {
							$count ++;
							$xmlArray[$flexformField . '_updated'] = 1;
							$xmlArray['data']['sDEF']['lDEF'][$flexformField]['vDEF'] = implode(',', $newCategories);
							$this->databaseConnection->exec_UPDATEquery('tt_content', 'uid=' . $row['uid'], array(
								'pi_flexform' => $flexformTools->flexArray2Xml($xmlArray)
							));
						}
					}
				}
			}

			if ($status !== NULL) {
				$this->messageArray[] = array($status, $title, $message);
			}
		}

		$status = FlashMessage::INFO;
		$message = 'Updated ' . $count . ' tt_content flexforms for  "' . $pluginName . ':' . $flexformField . '"';
		$this->messageArray[] = array($status, $title, $message);
	}

	/**
	 * Get Category Image folder
	 *
	 * @return \TYPO3\CMS\Core\Resource\Folder|void
	 * @throws \Exception
	 */
	protected function getCategoryImageFolder() {
		if ($this->categoryImageFolder === NULL) {

			$storage = $this->resourceFactory->getDefaultStorage();
			if (!$storage) {
				throw new \Exception('No default storage set!');
			}
			try {
				$this->categoryImageFolder = $storage->getFolder(self::FOLDER_CATEGORY_IMAGES);
			} catch (\TYPO3\CMS\Core\Resource\Exception\FolderDoesNotExistException $exception) {
				$this->categoryImageFolder = $storage->createFolder(self::FOLDER_CATEGORY_IMAGES);
			}
		}
		return $this->categoryImageFolder;
	}

	/**
	 * Generates output by using flash messages
	 *
	 * @return string
	 */
	protected function generateOutput() {
		$output = '';
		foreach ($this->messageArray as $messageItem) {
			/** @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
			$flashMessage = GeneralUtility::makeInstance(
				'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
				$messageItem[2],
				$messageItem[1],
				$messageItem[0]);
			$output .= $flashMessage->render();
		}
		return $output;
	}

}
