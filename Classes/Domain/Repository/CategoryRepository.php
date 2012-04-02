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
 * Category repository with all callable functionality
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Domain_Repository_CategoryRepository extends Tx_News_Domain_Repository_AbstractDemandedRepository {

	protected function createConstraintsFromDemand(Tx_Extbase_Persistence_QueryInterface $query,
		Tx_News_Domain_Model_DemandInterface $demand) {}

	protected function createOrderingsFromDemand(Tx_News_Domain_Model_DemandInterface $demand) {}

	/**
	 * Find category by import source and import id
	 *
	 * @param string $importSource import source
	 * @param integer $importId import id
	 * @return Tx_Extbase_Persistence_QueryInterface
	 */
	public function findOneByImportSourceAndImportId($importSource, $importId) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->getQuerySettings()->setRespectEnableFields(FALSE);
		return $query->matching(
			$query->logicalAnd(
				$query->equals('importSource', $importSource),
				$query->equals('importId', $importId)
			))->execute()->getFirst();
	}

	/**
	 * Find categories by a given pid
	 *
	 * @param integer $pid pid
	 * @return Tx_Extbase_Persistence_QueryInterface
	 */
	public function findParentCategoriesByPid($pid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		return $query->matching(
			$query->logicalAnd(
				$query->equals('pid', (int)$pid),
				$query->equals('parentcategory', 0)
			))->execute();
	}

	/**
	 * Find category tree
	 *
	 * @param array $rootIdList list of id s
	 * @return Tx_Extbase_Persistence_QueryInterface
	 */
	public function findTree(array $rootIdList) {
		$subCategories = Tx_News_Service_CategoryService::getChildrenCategories(implode(',',$rootIdList));

		$ordering = array('sorting' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING);

		$categories = $this->findByIdList(explode(',',$subCategories), $ordering);
		$flatCategories = array();
		foreach ($categories as $category) {
			$flatCategories[$category->getUid()] = Array(
				'item' =>  $category,
				'parent' => ($category->getParentcategory()) ? $category->getParentcategory()->getUid() : NULL
			);
		}

		$tree = array();
		foreach ($flatCategories as $id=>&$node) {
			if ($node['parent'] === NULL) {
				$tree[$id] = &$node;
			} else {
				$flatCategories[$node['parent']]['children'][$id] = &$node;
			}
		}

		return $tree;
	}

	/**
	 * Find categories by a given pid
	 *
	 * @param array $idList list of id s
	 * @param array $ordering ordering
	 * @return Tx_Extbase_Persistence_QueryInterface
	 */
	public function findByIdList(array $idList, array $ordering = array()) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);

		if (count($ordering) > 0) {
			$query->setOrderings($ordering);
		}

		return $query->matching(
			$query->logicalAnd(
				$query->in('uid', $idList)
			))->execute();
	}

	/**
	 * Find categories by a given parent
	 *
	 * @param integer $parent parent
	 * @return Tx_Extbase_Persistence_QueryInterface
	 */
	public function findChildren($parent) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		return $query->matching(
			$query->logicalAnd(
				$query->equals('parentcategory', (int)$parent)
			))->execute();
	}
}

?>