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
 * News Import Service
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News2_Domain_Service_NewsImportService implements t3lib_Singleton {
	const UPLOAD_PATH = 'uploads/tx_news2/';

	/**
	 * @var Tx_Extbase_Object_ObjectManager
	 */
	protected $objectManager;

	/**
	 * @var Tx_Extbase_Persistence_Manager
	 */
	protected $persistenceManager;

	/**
	 * @var Tx_News2_Domain_Repository_NewsRepository
	 */
	protected $newsRepository;

	/**
	 * @var Tx_News2_Domain_Repository_CategoryRepository
	 */
	protected $categoryRepository;


	/**
	 * Inject the object manager
	 *
	 * @param Tx_Extbase_Object_ObjectManager $objectManager
	 * @return void
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManager $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * Inject Persistence Manager
	 *
	 * @param Tx_Extbase_Persistence_Manager $persistenceManager
	 * @return void
	 */
	public function injectPersistenceManager(Tx_Extbase_Persistence_Manager $persistenceManager) {
		$this->persistenceManager = $persistenceManager;
	}

	/**
	 * Inject the news repository
	 *
	 * @param Tx_News2_Domain_Repository_NewsRepository $newsRepository
	 * @return void
	 */
	public function injectNewsRepository(Tx_News2_Domain_Repository_NewsRepository $newsRepository) {
		$this->newsRepository = $newsRepository;
	}

	/**
	 * Inject the category repository
	 *
	 * @param Tx_News2_Domain_Repository_CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(Tx_News2_Domain_Repository_CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	public function import(array $importData, array $importItemOverwrite = array(), $settings = array()) {
		foreach ($importData as $importItem) {
			$news = NULL;

			if ($importItem['import_source'] && $importItem['import_id']) {
				$news = $this->newsRepository->findOneByImportSourceAndImportId($importItem['import_source'],
					$importItem['import_id']);
			}

			if ($news === NULL) {
				$news = $this->objectManager->get('Tx_News2_Domain_Model_News');
				$this->newsRepository->add($news);
			}

			if (!empty($importItemOverwrite)) {
				$importItem = array_merge($importItem, $importItemOverwrite);
			}

			$news->setPid($importItem['pid']);
			$news->setStarttime($importItem['starttime']);
			$news->setEndtime($importItem['endtime']);

			$news->setTitle($importItem['title']);
			$news->setTeaser($importItem['teaser']);
			$news->setBodytext($importItem['bodytext']);

			$news->setDatetime(new DateTime(date('Y-m-d H:i:sP', $importItem['datetime'])));
			$news->setArchive(new DateTime(date('Y-m-d H:i:sP', $importItem['archive'])));

			$news->setAuthor($importItem['author']);
			$news->setAuthorEmail($importItem['author_email']);

			$news->setType($importItem['type']);
			$news->setKeywords($importItem['keywords']);
			$news->setContentElements($importItem['content_elements']);

			$news->setInternalurl($importItem['internalurl']);
			$news->setExternalurl($importItem['externalurl']);

			$news->setImportid($importItem['import_id']);
			$news->setImportSource($importItem['import_source']);

			if (is_array($importItem['categories'])) {
				foreach ($importItem['categories'] as $categoryUid) {
					if ($settings['findCategoriesByImportSource']) {
						$category = $this->categoryRepository->findOneByImportSourceAndImportId(
							$settings['findCategoriesByImportSource'], $categoryUid);
					} else {
						$category = $this->categoryRepository->findByUid($categoryUid);
					}

					if ($category) {
						$news->addCategory($category);
					}
				}
			}

			$basicFileFunctions = t3lib_div::makeInstance('t3lib_basicFileFunctions');

				// media relation
			if (is_array($importItem['media'])) {
				foreach ($importItem['media'] as $mediaItem) {
					if (!$media = $this->getMediaIfAlreadyExists($news, $mediaItem['image'])) {

						$uniqueName = $basicFileFunctions->getUniqueName($mediaItem['image'],
							PATH_site . self::UPLOAD_PATH);

						copy(
							PATH_site . $mediaItem['image'],
							$uniqueName
						);

						$media = $this->objectManager->get('Tx_News2_Domain_Model_Media');
						$news->addMedia($media);

						$media->setImage(basename($uniqueName));
					}

					$media->setTitle($mediaItem['title']);
					$media->setAlt($mediaItem['alt']);
					$media->setCaption($mediaItem['caption']);
					$media->setType($mediaItem['type']);
					$media->setShowinpreview($mediaItem['showinpreview']);
				}
			}

				// related files
			if (is_array($importItem['related_files'])) {
				foreach ($importItem['related_files'] as $file) {
					if (!$relatedFile = $this->getRelatedFileIfAlreadyExists($news, $file['file'])) {

						$uniqueName = $basicFileFunctions->getUniqueName($file['file'],
						PATH_site . self::UPLOAD_PATH);

						copy(
							PATH_site . $file['file'],
							$uniqueName
						);

						$relatedFile = $this->objectManager->get('Tx_News2_Domain_Model_File');
						$news->addRelatedFile($relatedFile);

						$relatedFile->setFile(basename($uniqueName));
					}
					$relatedFile->setTitle($file['title']);
					$relatedFile->setDescription($file['description']);
				}
			}

		}

		$this->persistenceManager->persistAll();
	}

	/**
	 * Get media file if it exists
	 *
	 * @param Tx_News2_Domain_Model_News $news
	 * @param string $mediaFile
	 * @return Boolean|Tx_News2_Domain_Model_Media
	 */
	protected function getMediaIfAlreadyExists(Tx_News2_Domain_Model_News $news, $mediaFile) {
		$result = FALSE;
		$mediaItems = $news->getMedia();

		if ($mediaItems->count() !== 0) {
			foreach ($mediaItems as $mediaItem) {
				if ($mediaItem->getImage() == basename($mediaFile) &&
					$this->filesAreEqual(
						PATH_site. $mediaFile,
						PATH_site . self::UPLOAD_PATH . $mediaItem->getImage()
					)) {
					$result = $mediaItem;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Get related file if it exists
	 *
	 * @param Tx_News2_Domain_Model_News $news
	 * @param string $relatedFile
	 * @return Boolean|Tx_News2_Domain_Model_File
	 */
	protected function getRelatedFileIfAlreadyExists(Tx_News2_Domain_Model_News $news, $relatedFile) {
		$result = FALSE;
		$relatedItems = $news->getRelatedFiles();

		if ($relatedItems->count() !== 0) {
			foreach ($relatedItems as $relatedItem) {
				if ($relatedItem->getFile() == basename($relatedFile) &&
					$this->filesAreEqual(
						PATH_site. $relatedFile,
						PATH_site . self::UPLOAD_PATH . $relatedItem->getFile()
					)) {
					$result = $relatedItem;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Compares 2 files by using its filesize
	 *
	 * @param string $file1 Absolut path and filename to file1
	 * @param string $file2 Absolut path and filename to file2
	 * @return boolean
	 */
	protected function filesAreEqual($file1, $file2) {
		return (filesize($file1) === filesize($file2));
	}
}
?>