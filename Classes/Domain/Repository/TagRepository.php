<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * Repository for tag objects
 */
class Tx_News_Domain_Repository_TagRepository extends Tx_News_Domain_Repository_AbstractDemandedRepository {

	/**
	 * Returns an array of constraints created from a given demand object.
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @return array<Tx_Extbase_Persistence_QOM_Constrain>
	 */
	protected function createConstraintsFromDemand(Tx_Extbase_Persistence_QueryInterface $query, Tx_News_Domain_Model_DemandInterface $demand) {
		$constraints = array();

		// Storage page
		if ($demand->getStoragePage() != 0) {
			$pidList = t3lib_div::intExplode(',', $demand->getStoragePage(), TRUE);
			$constraints[] = $query->in('pid', $pidList);
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
	 * @return array<Tx_Extbase_Persistence_QOM_Constrain>
	 */
	protected function createOrderingsFromDemand(Tx_News_Domain_Model_DemandInterface $demand) {
		$orderings = array();

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

}

?>