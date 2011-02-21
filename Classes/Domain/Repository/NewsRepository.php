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
 * @version $Id$
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

		foreach($categories as $category) {
			$categoryConstraints[] = $query->contains('category', $category);
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
				$demand->getCategorySetting());
		}

			// archived
		if ($demand->getArchiveSetting() == 'archived') {
			$constraints[] = $query->logicalAnd(
				$query->lessThan('archive', $GLOBALS['EXEC_TIME']),
				$query->greaterThan('archive', 0)
			);
		} elseif ($demand->getArchiveSetting() == 'active') {
			$constraints[] = $query->greaterThanOrEqual('archive', $GLOBALS['EXEC_TIME']);
		}

			// latest time
		if ($demand->getLatestTimeLimit() !== NULL) {
			$constraints[] = $query->greaterThanOrEqual(
				'datetime',
				$demand->getLatestTimeLimit()
			);
		}

			// top news
		if ($demand->getTopNewsSetting() == 1) {
			$constraints[] = $query->equals('istopnews', 1);
		} elseif ($demand->getTopNewsSetting() == 2) {
			$constraints[] = $query->greaterThanOrEqual('istopnews', 0);
		}

			// storage page
		if ($demand->getStoragePage() != 0) {
			$pidList = t3lib_div::intExplode(',', $demand->getStoragePage(), TRUE);
			$constraints[]  = $query->in('pid', $pidList);
		}

			// month & year
		if ($demand->getYear() > 0 && $demand->getMonth() > 0) {
			$begin = mktime(0, 0, 0, $demand->getMonth(), 0, $demand->getYear());
			$end = mktime(0, 0, 0, ($demand->getMonth() + 1), 0, $demand->getYear());

			$constraints[] = $query->logicalAnd(
					$query->greaterThanOrEqual($demand->getDateField(), $begin),
					$query->lessThanOrEqual($demand->getDateField(), $end)
				);
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
		if ($demand->getOrderRespectTopNews()) {
			$orderings['istopnews'] = Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING;
		}

		$orderList = t3lib_div::trimExplode(',', $demand->getOrder(), TRUE);

		if (!empty($orderList)) {
				// go through every order statement
			foreach($orderList as $orderItem) {
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

}
?>
