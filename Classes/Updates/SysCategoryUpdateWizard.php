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

class Tx_News_Updates_SysCategoryUpdateWizard extends \TYPO3\CMS\Install\Updates\AbstractUpdate {

	const FOLDER_CATEGORY_IMAGES = '/_migrated/news_categories';

	/**
	 * Title of the update wizard
	 * @var string
	 */
	protected $title = 'Migrate news categories to sys_categories';

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @var \TYPO3\CMS\Core\Resource\ResourceFactory
	 */
	protected $resourceFactory;


	public function __construct() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];

		$this->resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');
	}


	/**
	 * Checks whether updates are required.
	 *
	 * @param string &$description The description for the update
	 * @return boolean Whether an update is required (TRUE) or not (FALSE)
	 */
	public function checkForUpdate(&$description) {
		if ($this->isWizardDone() || !$this->checkIfTableExists('tx_news_domain_model_category')) {
			return FALSE;
		}

		// check if there are categories present else no update is needed
		$oldCategoryCount = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows(
			'uid',
			'tx_news_domain_model_category',
			"deleted = 0"
		);

		if ($oldCategoryCount === 0) {
			return FALSE;
		}

		$description = 'EXT:news migrated from custom category records to system categories.<br> ' .
			'This wizard will migrate all categories and relations.';
		return TRUE;
	}

	/**
	 * Performs the accordant updates.
	 *
	 * @param array &$dbQueries Queries done in this update
	 * @param mixed &$customMessages Custom messages
	 * @return boolean Whether everything went smoothly or not
	 */
	public function performUpdate(array &$dbQueries, &$customMessages) {
		$oldCategoryTableFields = $this->databaseConnection->admin_get_fields('tx_news_domain_model_category');

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
		$this->updateCategoryPermissionFields('be_groups', $oldNewCategoryUidMapping);
		$this->updateCategoryPermissionFields('be_users', $oldNewCategoryUidMapping);
		$this->migrateCategoryImages();

		$this->markWizardAsDone();
		return TRUE;
	}


	/**
	 * Process not yet migrated tx_news category records to sys_category records
	 */
	protected function migrateNewsCategoryRecords() {

		$rows = $this->databaseConnection->exec_SELECTgetRows(
			'uid, pid, tstamp, crdate, cruser_id, starttime, endtime, sorting, ' .
			'sys_language_uid, l10n_parent, l10n_diffsource, ' .
			'title, description, ' .
			'fe_group, single_pid, shortcut, import_id, import_source',
			'tx_news_domain_model_category',
			'migrate_sys_category_uid = 0 AND deleted = 0'
		);

		if ($this->databaseConnection->sql_error()) {
			$message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
			$status = FlashMessage::ERROR;
			$title = 'Failed selecting old category records';
			$this->messageArray[] = array($status, $title, $message);
		}

		// Create a new sys_category record for each found record
		$newCategoryRecords = 0;
		foreach ($rows as $row) {
			$oldUid = $row['uid'];
			unset($row['uid']);

			if (is_null($row['l10n_diffsource'])) {
				$row['l10n_diffsource'] = '';
			}

			if ($this->databaseConnection->exec_INSERTquery('sys_category', $row) !== FALSE) {
				$newUid = $this->databaseConnection->sql_insert_id();
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
			'uid, category_perms, tx_news_categorymounts',
			$table,
			"tx_news_categorymounts != ''"
		);

		foreach ($rows as $row) {
			$oldUids = GeneralUtility::trimExplode(',', $row['tx_news_categorymounts']);
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
					$fileObject = $this->getCategoryImageFolder()->addFile(PATH_site . 'uploads/tx_news/' . $file);
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
	 * Get Category Image folder
	 *
	 * @return \TYPO3\CMS\Core\Resource\Folder|void
	 * @throws Exception
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


}