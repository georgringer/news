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
 * News repository with all the callable functionality
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class Tx_News2_Domain_Repository_NewsRepository extends Tx_News2_Domain_Repository_AbstractDemandedRepository  {

	/**
	 * Returns a category contstrain created by a given list of categories and a junction string
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param  array $categories
	 * @param  string $conjunction
	 * @return Tx_Extbase_Persistence_QOM_Constrain|null
	 */
	protected function createCategoryConstraint(Tx_Extbase_Persistence_QueryInterface $query, $categories, $conjunction) {
		$constraint = NULL;
		$categoryConstraints = array();

		if (!is_array($categories)) {
			$categories = t3lib_div::intExplode(',', $categories, TRUE);
		}
		foreach ($categories as $category) {
			$categoryConstraints[] = $query->contains('categories', $category);
		}

		switch(strtolower($conjunction)) {
			case 'or':
				$constraint = $query->logicalOr($categoryConstraints);
				break;
			case 'notor':
				$constraint =  $query->logicalNot($query->logicalOr($categoryConstraints));
				break;
			case 'notand':
				$constraint = $query->logicalNot($query->logicalAnd($categoryConstraints));
				break;
			case 'and':
			default:
				$constraint = $query->logicalAnd($categoryConstraints);
		}

		return $constraint;
	}

	/**
	 * Returns an array of constraints created from a given demand object.
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param Tx_News2_Domain_Model_DemandInterface $demand
	 * @return array<Tx_Extbase_Persistence_QOM_Constrain>
	 */
	protected function createConstraintsFromDemand(Tx_Extbase_Persistence_QueryInterface $query, Tx_News2_Domain_Model_DemandInterface $demand) {
		$constraints = array();

		if ($demand->getCategories() && $demand->getCategories() !== '0') {
			$constraints[] = $this->createCategoryConstraint($query, $demand->getCategories(),
				$demand->getCategoryConjunction());
		}

			// archived
		if ($demand->getArchiveRestriction() == 'archived') {
			$constraints[] = $query->logicalAnd(
				$query->lessThan('archive', $GLOBALS['EXEC_TIME']),
				$query->greaterThan('archive', 0)
			);
		} elseif ($demand->getArchiveRestriction() == 'active') {
			$constraints[] = $query->greaterThanOrEqual('archive', $GLOBALS['EXEC_TIME']);
		}

			// latest time
		if ($demand->getTimeRestriction()) {
			$timeLimit = 0;
				// integer = timestamp
			if (t3lib_div::testInt($demand->getTimeRestriction())) {
				$timeLimit = $GLOBALS['EXEC_TIME'] - $demand->getTimeRestriction();
			} else {
					// try to check strtotime
				$timeFromString = strtotime($demand->getTimeRestriction());

				if ($timeFromString) {
					$timeLimit = $timeFromString;
				} else {
					throw new Exception('Latest time limit could not be resolved to an integer. Given was: ' . htmlspecialchars($timeLimit));
				}
			}

			$constraints[] = $query->greaterThanOrEqual(
				'datetime',
				$timeLimit
			);
		}

			// top news
		if ($demand->getTopNewsRestriction() == 1) {
			$constraints[] = $query->equals('istopnews', 1);
		} elseif ($demand->getTopNewsRestriction() == 2) {
			$constraints[] = $query->equals('istopnews', 0);
		}

			// storage page
		if ($demand->getStoragePage() != 0) {
			$pidList = t3lib_div::intExplode(',', $demand->getStoragePage(), TRUE);
			$constraints[]  = $query->in('pid', $pidList);
		}

			// month & year
		if ($demand->getYear() > 0 && $demand->getMonth() > 0) {
			if (is_null($demand->getDateField())) {
				throw new InvalidArgumentException('No Datefield is set, therefore no Datemenu is possible!');
			}
			$begin = mktime(0, 0, 0, $demand->getMonth(), 1, $demand->getYear());
			$end = mktime(0, 0, 0, ($demand->getMonth() + 1), 0, $demand->getYear());

			$constraints[] = $query->logicalAnd(
					$query->greaterThanOrEqual($demand->getDateField(), $begin),
					$query->lessThanOrEqual($demand->getDateField(), $end)
				);
		}

			// Tags
		if ($demand->getTags()) {
			$constraints = $query->contains('tags', $demand->getTags());
		}

			// dummy records, used for UnitTests only!
		if ($demand->getIsDummyRecord()) {
			$constraints[] = $query->equals('isDummyRecord', 1);
		}

			// Search
		if ($demand->getSearch() !== NULL) {
			/** @var $searchObject Tx_News2_Domain_Model_Dto_Search */
			$searchObject = $demand->getSearch();

			$searchString = $searchObject->getSearchString();
			if (!empty($searchString)) {
				$constraints[] = $query->like('title', '%' . $searchString . '%');
			}
		}

		return $constraints;
	}

	/**
	 * Returns an array of orderings created from a given demand object.
	 *
	 * @param Tx_News2_Domain_Model_DemandInterface $demand
	 * @return array<Tx_Extbase_Persistence_QOM_Constrain>
	 */
	protected function createOrderingsFromDemand(Tx_News2_Domain_Model_DemandInterface $demand) {
		$orderings = array();
		if ($demand->getTopNewsFirst()) {
			$orderings['istopnews'] = Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING;
		}

		$orderList = t3lib_div::trimExplode(',', $demand->getOrder(), TRUE);

		if (!empty($orderList)) {
				// go through every order statement
			foreach ($orderList as $orderItem) {
				list($orderField, $ascDesc) = t3lib_div::trimExplode(' ', $orderItem, TRUE);
					// count == 1 means that no direction is given
				if ($ascDesc) {
					$orderings[$orderField] = ((strtolower($ascDesc) == 'desc') ?
						Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING :
						Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING);
				} else {
					$orderings[$orderField] = Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING;
				}
			}
		}

		return $orderings;
	}

	/**
	 * Find first news by import and source id
	 *
	 * @param string $importSource import source
	 * @param integer $importId import id
	 * @return Tx_News2_Domain_Model_News
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

}
?>