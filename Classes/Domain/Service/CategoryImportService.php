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
 * Category Import Service
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News_Domain_Service_CategoryImportService extends Tx_News_Domain_Service_AbstractImportService {

	const ACTION_SET_PARENT_CATEGORY = 1;
	const ACTION_CREATE_L10N_CHILDREN_CATEGORY = 2;

	/**
	 * @var Tx_News_Domain_Repository_CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Inject the category repository.
	 *
	 * @param Tx_News_Domain_Repository_CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(Tx_News_Domain_Repository_CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * @param array $importArray
	 * @return void
	 */
	public function import(array $importArray) {
		// Sort import array to import the default language first
		foreach ($importArray as $importItem) {
			$category = $this->hydrateCategory($importItem);

			if (!empty($importItem['title_lang_ol'])) {
				$this->postPersistQueue[$importItem['import_id']] = array(
					'category' => $category,
					'importItem' => $importItem,
					'action' => self::ACTION_CREATE_L10N_CHILDREN_CATEGORY,
					'titleLanguageOverlay' => $importItem['title_lang_ol']
				);
			}

			if ($importItem['parentcategory']) {
				$this->postPersistQueue[$importItem['import_id']] = array(
					'category' => $category,
					'action' => self::ACTION_SET_PARENT_CATEGORY,
					'parentCategoryOriginUid' => $importItem['parentcategory']
				);
			}
		}

		$this->persistenceManager->persistAll();

		foreach ($this->postPersistQueue as $queueItem) {
			switch ($queueItem['action']) {
				case self::ACTION_SET_PARENT_CATEGORY:
					$this->setParentCategory($queueItem);
					break;
				case self::ACTION_CREATE_L10N_CHILDREN_CATEGORY:
					$this->createL10nChildrenCategory($queueItem);
					break;
				default:
					// do nothing
					break;
			}

		}

		$this->persistenceManager->persistAll();
	}

	/**
	 * Hydrate a category record with the given array
	 *
	 * @param array $importItem
	 * @return Tx_News_Domain_Model_Category
	 */
	protected function hydrateCategory(array $importItem) {
		if (is_null($category = $this->categoryRepository->findOneByImportSourceAndImportId(
			$importItem['import_source'], $importItem['import_id']))) {

			/** @var $category Tx_News_Domain_Model_Category */
			$category = $this->objectManager->get('Tx_News_Domain_Model_Category');
			$this->categoryRepository->add($category);
		}
		$category->setPid($importItem['pid']);
		$category->setHidden($importItem['hidden']);
		$category->setStarttime($importItem['starttime']);
		$category->setEndtime($importItem['endtime']);
		$category->setCrdate($importItem['crdate']);
		$category->setTstamp($importItem['tstamp']);
		$category->setTitle($importItem['title']);
		$category->setDescription($importItem['description']);
		$this->setFileRelationFromImage($category, $importItem['image']);
		$category->setShortcut($importItem['shortcut']);
		$category->setSinglePid($importItem['single_pid']);

		$category->setImportId($importItem['import_id']);
		$category->setImportSource($importItem['import_source']);

		return $category;
	}

	/**
	 * Add category image when not already present
	 *
	 * @param Tx_News_Domain_Model_Category $category
	 * @param $image
	 */
	protected function setFileRelationFromImage($category, $image) {

		// get fileObject by given identifier (file UID, combined identifier or path/filename)
		try {
			$newImage = $this->getResourceFactory()->retrieveFileOrFolderObject($image);
		} catch (\TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException $exception) {
			$newImage = FALSE;
		}

		// new image found check if this isn't already
		if ($newImage) {
			$existingImages = $category->getImages();
			if ($existingImages->count() !== 0) {
				/** @var $item Tx_News_Domain_Model_FileReference */
				foreach ($existingImages as $item) {
					// only check already persisted items
					if ($item->getFileUid() === (int)$newImage->getUid()
						||
						($item->getUid() &&
							$item->getOriginalResource()->getName() === $newImage->getName() &&
							$item->getOriginalResource()->getSize() === (int)$newImage->getSize())
					) {
						$newImage = FALSE;
						break;
					}
				}
			}
		}

		if ($newImage) {
			// file not inside a storage then search for existing file or copy the one form storage 0 to the import folder
			if ($newImage->getStorage()->getUid() === 0) {

				// search DB for same file based on hash (to prevent duplicates)
				$existingFile = $this->findFileByHash($newImage->getSha1());

				// no exciting file then copy file to import folder
				if ($existingFile === NULL) {
					$newImage = $this->getResourceStorage()->copyFile($newImage, $this->getImportFolder());
				} else {
					$newImage = $existingFile;
				}
			}

			$fileReference = $this->objectManager->get('Tx_News_Domain_Model_FileReference');
			$fileReference->setFileUid($newImage->getUid());
			$fileReference->setPid($category->getPid());
			$category->addImage($fileReference);
		}
	}

	/**
	 * Set parent category
	 *
	 * @param array $queueItem
	 * @return void
	 */
	protected function setParentCategory(array $queueItem) {
		/** @var $category Tx_News_Domain_Model_Category */
		$category = $queueItem['category'];
		$parentCategoryOriginUid = $queueItem['parentCategoryOriginUid'];

		if (is_null($parentCategory = $this->postPersistQueue[$parentCategoryOriginUid]['category'])) {
			$parentCategory = $this->categoryRepository->findOneByImportSourceAndImportId(
				$category->getImportSource(),
				$parentCategoryOriginUid
			);
		}

		if ($parentCategory !== NULL) {
			$category->setParentcategory($parentCategory);
			$this->categoryRepository->update($category);
		}
	}

	/**
	 * Create l10n relation
	 *
	 * @param array $queueItem
	 * @return void
	 */
	protected function createL10nChildrenCategory(array $queueItem) {
		/** @var $category Tx_News_Domain_Model_Category */
		$category = $queueItem['category'];
		$titleLanguageOverlay = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('|', $queueItem['titleLanguageOverlay']);

		foreach ($titleLanguageOverlay as $key => $title) {
			$sysLanguageUid = $key + 1;

			$importItem = $queueItem['importItem'];
			$importItem['import_id'] = $importItem['import_id'] . '|L:' . $sysLanguageUid;

			/** @var $l10nChildrenCategory Tx_News_Domain_Model_Category */
			$l10nChildrenCategory = $this->hydrateCategory($importItem);
			$this->categoryRepository->add($l10nChildrenCategory);

			$l10nChildrenCategory->setTitle($title);
			$l10nChildrenCategory->setL10nParent((int)$category->getUid());
			$l10nChildrenCategory->setSysLanguageUid((int)$sysLanguageUid);
		}

	}

}
