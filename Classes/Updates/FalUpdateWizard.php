<?php

namespace GeorgRinger\News\Updates;

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

class FalUpdateWizard extends \TYPO3\CMS\Install\Updates\AbstractUpdate {

	const FOLDER_ContentUploads = '_migrated/news_uploads';

	/**
	 * @var string
	 */
	protected $title = 'Migrate file relations of EXT:news';

	/**
	 * @var string
	 */
	protected $targetDirectory;

	/**
	 * @var \TYPO3\CMS\Core\Resource\ResourceFactory
	 */
	protected $fileFactory;

	/**
	 * @var \TYPO3\CMS\Core\Resource\FileRepository
	 */
	protected $fileRepository;

	/**
	 * @var \TYPO3\CMS\Core\Resource\ResourceStorage
	 */
	protected $storage;

	/**
	 * Initialize all required repository and factory objects.
	 *
	 * @throws \RuntimeException
	 */
	protected function init() {
		$fileadminDirectory = rtrim($GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir'], '/') . '/';
		/** @var $storageRepository \TYPO3\CMS\Core\Resource\StorageRepository */
		$storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
		$storages = $storageRepository->findAll();
		foreach ($storages as $storage) {
			$storageRecord = $storage->getStorageRecord();
			$configuration = $storage->getConfiguration();
			$isLocalDriver = $storageRecord['driver'] === 'Local';
			$isOnFileadmin = !empty($configuration['basePath']) && \TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($configuration['basePath'], $fileadminDirectory);
			if ($isLocalDriver && $isOnFileadmin) {
				$this->storage = $storage;
				break;
			}
		}
		if (!isset($this->storage)) {
			throw new \RuntimeException('Local default storage could not be initialized - might be due to missing sys_file* tables.');
		}
		$this->fileFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');
		$this->fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
		$this->targetDirectory = PATH_site . $fileadminDirectory . self::FOLDER_ContentUploads . '/';
	}

	/**
	 * Checks if an update is needed
	 *
	 * @param string &$description : The description for the update
	 * @return boolean TRUE if an update is needed, FALSE otherwise
	 */
	public function checkForUpdate(&$description) {
		$updateNeeded = FALSE;
		// Fetch records where the old relation is used and the new one is empty
		$notMigratedMediaRowsCount = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows(
			'tx_news_domain_model_media.uid',
			'tx_news_domain_model_media LEFT JOIN tx_news_domain_model_news ON tx_news_domain_model_media.parent = tx_news_domain_model_news.uid',
			'tx_news_domain_model_news.fal_media = 0 AND tx_news_domain_model_media.type = 0 AND tx_news_domain_model_media.image != "" AND
					tx_news_domain_model_media.deleted=0 AND
					tx_news_domain_model_news.deleted=0
			'
		);
		if ($notMigratedMediaRowsCount > 0) {
			$description = 'There are <strong>' . $notMigratedMediaRowsCount . '</strong> media records which are using the old media relation. '
				. 'This wizard will copy the files to "fileadmin/' . self::FOLDER_ContentUploads . '".';
			$updateNeeded = TRUE;
		}

		$notMigratedRelatedFileRowsCount = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows(
			'tx_news_domain_model_file.uid',
			'tx_news_domain_model_file LEFT JOIN tx_news_domain_model_news ON tx_news_domain_model_file.parent = tx_news_domain_model_news.uid',
			'tx_news_domain_model_news.fal_related_files = 0 AND
					tx_news_domain_model_file.deleted=0 AND
					tx_news_domain_model_news.deleted=0
			'
		);
		if ($notMigratedRelatedFileRowsCount > 0) {
			$description .= '<br /><br />There are <strong>' . $notMigratedRelatedFileRowsCount . '</strong> related file records which are using the old relation. '
				. 'This wizard will copy the files to "fileadmin/' . self::FOLDER_ContentUploads . '".';
			$updateNeeded = TRUE;
		}

		if ($updateNeeded) {
			$description .= '<br /><br /><strong>Important:</strong> The <strong>first</strong> local storage inside "' . $GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir'] . '" will
			be used for the migration. If you have multiple storages, only enable the one which should be used for the migration.';
		}

		return $updateNeeded;
	}

	/**
	 * Performs the database update.
	 *
	 * @param array &$dbQueries Queries done in this update
	 * @param mixed &$customMessages Custom messages
	 * @return boolean TRUE on success, FALSE on error
	 */
	public function performUpdate(array &$dbQueries, &$customMessages) {
		$this->init();
		$this->checkPrerequisites();

		$mediaRecords = $this->getMediaRecordsFromTable('tx_news_domain_model_media', 'fal_media');
		if ($mediaRecords) {
			foreach ($mediaRecords as $singleRecord) {
				$this->migrateRecord($singleRecord, 'fal_media');
			}
			$this->setCountInNewsRecord('fal_media');
		}

		$relatedFileRecords = $this->getMediaRecordsFromTable('tx_news_domain_model_file', 'fal_related_files');
		if ($relatedFileRecords) {
			foreach ($relatedFileRecords as $singleRecord) {
				$this->migrateRecord($singleRecord, 'fal_related_files');
			}
			$this->setCountInNewsRecord('fal_related_files');
		}


		return TRUE;
	}

	/**
	 * Update the news table and set the count of relations
	 *
	 * @param string $field
	 * @return void
	 */
	protected function setCountInNewsRecord($field) {
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'count(*) as count, uid_foreign as uid',
			'sys_file_reference',
			'deleted=0 AND hidden=0 AND
				cruser_id=999 AND fieldname= "' . $field . '" AND tablenames= "tx_news_domain_model_news"',
			'uid_foreign'
		);

		foreach ($rows as $row) {
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				'tx_news_domain_model_news',
				$field . '= 0 AND uid=' . (int)$row['uid'],
				array($field => $row['count'])
			);
		}
	}

	/**
	 * Ensures a new folder "fileadmin/content_upload/" is available.
	 *
	 * @return void
	 */
	protected function checkPrerequisites() {
		if (!$this->storage->hasFolder(self::FOLDER_ContentUploads)) {
			$this->storage->createFolder(self::FOLDER_ContentUploads, $this->storage->getRootLevelFolder());
		}
	}

	/**
	 * Processes the actual transformation from CSV to sys_file_references
	 *
	 * @param array $record
	 * @param string $field
	 * @return void
	 */
	protected function migrateRecord(array $record, $field) {
		if ($field === 'fal_related_files') {
			$file = $record['file'];
		} else {
			$file = $record['image'];
		}

		if (!empty($file) && file_exists(PATH_site . 'uploads/tx_news/' . $file)) {
			\TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move(
				PATH_site . 'uploads/tx_news/' . $file,
				$this->targetDirectory . $file);

			$fileObject = $this->storage->getFile(self::FOLDER_ContentUploads . '/' . $file);
			$this->fileRepository->add($fileObject);
			$dataArray = array(
				'uid_local' => $fileObject->getUid(),
				'tablenames' => 'tx_news_domain_model_news',
				'fieldname' => $field,
				'uid_foreign' => $record['newsUid'],
				'table_local' => 'sys_file',
				// the sys_file_reference record should always placed on the same page
				// as the record to link to, see issue #46497
				'cruser_id' => 999,
				'pid' => $record['newsPid'],
				'sorting_foreign' => $record['sorting'],
				'title' => $record['title'],
				'hidden' => $record['hidden'],
			);

			if ($field === 'fal_media') {
				$description = array();
				if (!empty($record['caption'])) {
					$description[] = $record['caption'];
				}
				if (!empty($record['description'])) {
					$description[] = $record['description'];
				}
				$additionalData = array(
					// @todo how to proceed with: copyright
					'description' => implode(LF . LF, $description),
					'alternative' => $record['alt'],
					'showinpreview' => $record['showinpreview']
				);
			} else {
				$additionalData = array(
					'description' => $record['description']
				);
			}
			$dataArray += $additionalData;

			$GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_file_reference', $dataArray);
		}
	}

	/**
	 * Retrieve every record which needs to be processed
	 *
	 * @param $table string table name
	 * @param $field string field name
	 * @return array
	 */
	protected function getMediaRecordsFromTable($table, $field) {
		$additionalWhereClause = '';
		if ($table === 'tx_news_domain_model_media') {
			$additionalWhereClause = ' AND tx_news_domain_model_media.type = 0 AND tx_news_domain_model_media.image != ""';
		}
		$records = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			$table . '.*, tx_news_domain_model_news.uid AS newsUid, tx_news_domain_model_news.pid as newsPid',
			$table . ' LEFT JOIN tx_news_domain_model_news ON ' . $table . '.parent = tx_news_domain_model_news.uid',
			'tx_news_domain_model_news.' . $field . ' = 0 AND
' . $table . '.deleted=0 AND tx_news_domain_model_news.deleted=0' . $additionalWhereClause
		);

		return $records;
	}
}