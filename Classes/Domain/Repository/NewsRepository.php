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
 * @subpackage tx_news
 */
class Tx_News_Domain_Repository_NewsRepository extends Tx_News_Domain_Repository_AbstractDemandedRepository {

	/**
	 * Returns a category constraint created by
	 * a given list of categories and a junction string
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param  array $categories
	 * @param  string $conjunction
	 * @param  boolean $includeSubCategories
	 * @return Tx_Extbase_Persistence_QOM_Constraint|null
	 */
	protected function createCategoryConstraint(Tx_Extbase_Persistence_QueryInterface $query, $categories, $conjunction, $includeSubCategories = FALSE) {
		$constraint = NULL;
		$categoryConstraints = array();

		// If "ignore category selection" is used, nothing needs to be done
		if (empty($conjunction)) {
			return $constraint;
		}

		if (!is_array($categories)) {
			$categories = t3lib_div::intExplode(',', $categories, TRUE);
		}
		foreach ($categories as $category) {
			if ($includeSubCategories) {
				$subCategories = t3lib_div::trimExplode(',', Tx_News_Service_CategoryService::getChildrenCategories($category, 0, '', TRUE), TRUE);
				$subCategoryConstraint = array();
				$mixedConstraint = array();
				$subCategoryConstraint[] = $query->contains('categories', $category);
				if (count($subCategories) > 0) {
					foreach ($subCategories as $subCategory) {
						$subCategoryConstraint[] = $query->contains('categories', $subCategory);
					}
					$mixedConstraint[] = $query->logicalOr($subCategoryConstraint);
				}

				$categoryConstraints[] = $query->logicalAnd($mixedConstraint);
			} else {
				$categoryConstraints[] = $query->contains('categories', $category);
			}
		}

		switch (strtolower($conjunction)) {
			case 'or':
				$constraint = $query->logicalOr($categoryConstraints);
				break;
			case 'notor':
				$constraint = $query->logicalNot($query->logicalOr($categoryConstraints));
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
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @return array<Tx_Extbase_Persistence_QOM_Constraint>
	 */
	protected function createConstraintsFromDemand(Tx_Extbase_Persistence_QueryInterface $query, Tx_News_Domain_Model_DemandInterface $demand) {
		$constraints = array();

		if ($demand->getCategories() && $demand->getCategories() !== '0') {
			$constraints[] = $this->createCategoryConstraint(
				$query,
				$demand->getCategories(),
				$demand->getCategoryConjunction(),
				$demand->getIncludeSubCategories()
			);
		}

		// archived
		if ($demand->getArchiveRestriction() == 'archived') {
			$constraints[] = $query->logicalAnd(
				$query->lessThan('archive', $GLOBALS['EXEC_TIME']),
				$query->greaterThan('archive', 0)
			);
		} elseif ($demand->getArchiveRestriction() == 'active') {
			$constraints[] = $query->logicalOr(
				$query->greaterThanOrEqual('archive', $GLOBALS['EXEC_TIME']),
				$query->equals('archive', 0)
			);
		}

		// Time restriction greater than or equal
		if ($demand->getTimeRestriction()) {
			$timeLimit = 0;
			// integer = timestamp
			if (Tx_News_Utility_Compatibility::canBeInterpretedAsInteger($demand->getTimeRestriction())) {
				$timeLimit = $GLOBALS['EXEC_TIME'] - $demand->getTimeRestriction();
			} else {
				// try to check strtotime
				$timeFromString = strtotime($demand->getTimeRestriction());

				if ($timeFromString) {
					$timeLimit = $timeFromString;
				} else {
					throw new Exception('Time limit Low could not be resolved to an integer. Given was: ' . htmlspecialchars($timeLimit));
				}
			}

			$constraints[] = $query->greaterThanOrEqual(
				'datetime',
				$timeLimit
			);
		}

		// Time restriction less than or equal
		if ($demand->getTimeRestrictionHigh()) {
			$timeLimit = 0;
			// integer = timestamp
			if (Tx_News_Utility_Compatibility::canBeInterpretedAsInteger($demand->getTimeRestrictionHigh())) {
				$timeLimit = $GLOBALS['EXEC_TIME'] + $demand->getTimeRestrictionHigh();
			} else {
				// try to check strtotime
				$timeFromString = strtotime($demand->getTimeRestrictionHigh());

				if ($timeFromString) {
					$timeLimit = $timeFromString;
				} else {
					throw new Exception('Time limit High could not be resolved to an integer. Given was: ' . htmlspecialchars($timeLimit));
				}
			}

			$constraints[] = $query->lessThanOrEqual(
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
			$constraints[] = $query->in('pid', $pidList);
		}

		// month & year OR year only
		if ($demand->getYear() > 0) {
			if (is_null($demand->getDateField())) {
				throw new InvalidArgumentException('No Datefield is set, therefore no Datemenu is possible!');
			}
			if ($demand->getMonth() > 0) {
				if ($demand->getDay() > 0) {
					$begin = mktime(0, 0, 0, $demand->getMonth(), $demand->getDay(), $demand->getYear());
					$end = mktime(23, 59, 59, $demand->getMonth(), $demand->getDay(), $demand->getYear());
				} else {
					$begin = mktime(0, 0, 0, $demand->getMonth(), 1, $demand->getYear());
					$end = mktime(23, 59, 59, ($demand->getMonth() + 1), 0, $demand->getYear());
				}
			} else {
				$begin = mktime(0, 0, 0, 1, 1, $demand->getYear());
				$end = mktime(23, 59, 59, 12, 31, $demand->getYear());
			}
			$constraints[] = $query->logicalAnd(
				$query->greaterThanOrEqual($demand->getDateField(), $begin),
				$query->lessThanOrEqual($demand->getDateField(), $end)
			);
		}

		// Tags
		if ($demand->getTags()) {
			$constraints[] = $query->contains('tags', $demand->getTags());
		}

		// dummy records, used for UnitTests only!
		if ($demand->getIsDummyRecord()) {
			$constraints[] = $query->equals('isDummyRecord', 1);
		}

		// Search
		if ($demand->getSearch() !== NULL) {
			/* @var $searchObject Tx_News_Domain_Model_Dto_Search */
			$searchObject = $demand->getSearch();

			$searchFields = t3lib_div::trimExplode(',', $searchObject->getFields(), TRUE);
			$searchConstraints = array();

			if (count($searchFields) === 0) {
				throw new UnexpectedValueException('No search fields defined', 1318497755);
			}

			$searchSubject = $searchObject->getSubject();
			foreach ($searchFields as $field) {
				if (!empty($searchSubject)) {
					$searchConstraints[] = $query->like($field, '%' . $searchSubject . '%');
				}
			}

			if (count($searchConstraints)) {
				$constraints[] = $query->logicalOr($searchConstraints);
			}
		}

		// Exclude already displayed
		if ($demand->getExcludeAlreadyDisplayedNews() && isset($GLOBALS['EXT']['news']['alreadyDisplayed']) && !empty($GLOBALS['EXT']['news']['alreadyDisplayed'])) {
			$constraints[] = $query->logicalNot(
				$query->in(
					'uid',
					$GLOBALS['EXT']['news']['alreadyDisplayed']
				)
			);
		}

		// Clean not used constraints
		foreach ($constraints as $key => $value) {
			if (is_null($value)) {
				unset($constraints[$key]);
			}
		}

		return $constraints;
	}

	/**
	 * Returns an array of orderings created from a given demand object.
	 *
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @return array<Tx_Extbase_Persistence_QOM_Constraint>
	 */
	protected function createOrderingsFromDemand(Tx_News_Domain_Model_DemandInterface $demand) {
		$orderings = array();
		if ($demand->getTopNewsFirst()) {
			$orderings['istopnews'] = Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING;
		}

		if (Tx_News_Utility_Validation::isValidOrdering($demand->getOrder(), $demand->getOrderByAllowed())) {
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
		}

		return $orderings;
	}

	/**
	 * Find first news by import and source id
	 *
	 * @param string $importSource import source
	 * @param integer $importId import id
	 * @return Tx_News_Domain_Model_News
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
	 * Override default findByUid function to enable also the option to turn of
	 * the enableField setting
	 *
	 * @param integer $uid id of record
	 * @param boolean $respectEnableFields if set to false, hidden records are shown
	 * @return Tx_News_Domain_Model_News
	 */
	public function findByUid($uid, $respectEnableFields = TRUE) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->getQuerySettings()->setRespectEnableFields($respectEnableFields);

		return $query->matching(
			$query->logicalAnd(
				$query->equals('uid', $uid),
				$query->equals('deleted', 0)
			))->execute()->getFirst();
	}

	/**
	 * Get the count of news records by month/year and
	 * returns the result compiled as array
	 *
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @return array
	 */
	public function countByDate(Tx_News_Domain_Model_DemandInterface $demand) {
		$data = array();
		$sql = $this->findDemandedRaw($demand);

		// Get the month/year into the result
		$sql = 'SELECT FROM_UNIXTIME(datetime, "%m") AS "_Month",' .
		' FROM_UNIXTIME(datetime, "%Y") AS "_Year" ,' .
		' count(FROM_UNIXTIME(datetime, "%m")) as count_month,' .
		' count(FROM_UNIXTIME(datetime, "%y")) as count_year' .
		' FROM tx_news_domain_model_news ' . substr($sql, strpos($sql, 'WHERE '));

		// strip unwanted order by
		$sql = $GLOBALS['TYPO3_DB']->stripOrderBy($sql);

		// group by custom month/year fields
		$orderDirection = strtolower($demand->getOrder());
		if ($orderDirection !== 'desc' && $orderDirection != 'asc') {
			$orderDirection = 'asc';
		}
		$sql .= ' GROUP BY _Month, _Year ORDER BY _Year ' . $orderDirection . ', _Month ' . $orderDirection;

		$res = $GLOBALS['TYPO3_DB']->sql_query($sql);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$data['single'][$row['_Year']][$row['_Month']] = $row['count_month'];
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		// Add totals
		foreach ($data['single'] as $year => $months) {
			$countOfYear = 0;
			foreach ($months as $month) {
				$countOfYear += $month;
			}
			$data['total'][$year] = $countOfYear;
		}

		return $data;
	}

}

?>