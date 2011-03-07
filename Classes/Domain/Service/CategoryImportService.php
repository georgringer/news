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
 * @subpackage tx_news2
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News2_Domain_Service_CategoryImportService implements t3lib_Singleton {
	/**
	 * @var Tx_Extbase_Object_ObjectManager
	 */
	protected $objectManager;

	/**
	 * @var Tx_News2_Domain_Repository_NewsRepository
	 */
	protected $categoryRepository;

	/**
	 * @var Tx_Extbase_Persistence_Manager
	 */
	protected $persistenceManager;

	/**
	 * @var array
	 */
	protected $postPersistQueue = array();

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
	 *
	 * @param Tx_Extbase_Persistence_Manager $persistenceManager
	 * @return void
	 */
	public function injectPersistenceManager(Tx_Extbase_Persistence_Manager $persistenceManager) {
		$this->persistenceManager = $persistenceManager;
	}

	/**
	 * Inject the category repository.
	 *
	 * @param Tx_News2_Domain_Repository_CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(Tx_News2_Domain_Repository_CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	public function import(array $importArray) {
		foreach ($importArray as $importItem) {
			if (is_null($category = $this->categoryRepository->findOneByImportSourceAndImportId(
				$importItem['import_source'], $importItem['import_id']))) {

				$category = $this->objectManager->get('Tx_News2_Domain_Model_Category');
				$this->categoryRepository->add($category);
			}
			$category->setPid($importItem['pid']);
			$category->setStarttime($importItem['starttime']);
			$category->setEndtime($importItem['endtime']);
			$category->setTitle($importItem['title']);
			$category->setDescription($importItem['description']);
			$category->setImage($importItem['image']);
			$category->setShortcut($importItem['shortcut']);
			$category->setSinglePid($importItem['single_pid']);

			$category->setImportId($importItem['import_id']);
			$category->setImportSource($importItem['import_source']);

			if ($importItem['parentcategory']) {
				$this->postPersistQueue[$importItem['import_id']] = array(
					'category' => $category,
					'parentCategoryOriginUid' => $importItem['parentcategory']
				);
			}
		}

		$this->persistenceManager->persistAll();

		foreach ($this->postPersistQueue as $originalPrimaryKey => $queueItem) {
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
			}

		}

		$this->persistenceManager->persistAll();
	}

}
?>