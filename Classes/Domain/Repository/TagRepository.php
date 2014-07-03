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
/**
 * Repository for tag objects
 */
class Tx_News_Domain_Repository_TagRepository extends Tx_News_Domain_Repository_AbstractDemandedRepository {

	/**
	 * Returns an array of constraints created from a given demand object.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
	 */
	protected function createConstraintsFromDemand(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, Tx_News_Domain_Model_DemandInterface $demand) {
		$constraints = array();

		// Storage page
		if ($demand->getStoragePage() != 0) {
			$pidList = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $demand->getStoragePage(), TRUE);
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
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
	 */
	protected function createOrderingsFromDemand(Tx_News_Domain_Model_DemandInterface $demand) {
		$orderings = array();

		if (Tx_News_Utility_Validation::isValidOrdering($demand->getOrder(), $demand->getOrderByAllowed())) {
			$orderList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $demand->getOrder(), TRUE);

			if (!empty($orderList)) {
				// go through every order statement
				foreach ($orderList as $orderItem) {
					list($orderField, $ascDesc) = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(' ', $orderItem, TRUE);
					// count == 1 means that no direction is given
					if ($ascDesc) {
						$orderings[$orderField] = ((strtolower($ascDesc) == 'desc') ?
							\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING :
							\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);
					} else {
						$orderings[$orderField] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
					}
				}
			}
		}

		return $orderings;
	}

}
