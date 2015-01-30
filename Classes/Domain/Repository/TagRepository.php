<?php

namespace GeorgRinger\News\Domain\Repository;

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

use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Utility\Validation;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Repository for tag objects
 */
class TagRepository extends \GeorgRinger\News\Domain\Repository\AbstractDemandedRepository {

	/**
	 * Returns an array of constraints created from a given demand object.
	 *
	 * @param QueryInterface $query
	 * @param DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
	 */
	protected function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand) {
		$constraints = array();

		// Storage page
		if ($demand->getStoragePage() != 0) {
			$pidList = GeneralUtility::intExplode(',', $demand->getStoragePage(), TRUE);
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
	 * @param DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
	 */
	protected function createOrderingsFromDemand(DemandInterface $demand) {
		$orderings = array();

		if (Validation::isValidOrdering($demand->getOrder(), $demand->getOrderByAllowed())) {
			$orderList = GeneralUtility::trimExplode(',', $demand->getOrder(), TRUE);

			if (!empty($orderList)) {
				// go through every order statement
				foreach ($orderList as $orderItem) {
					list($orderField, $ascDesc) = GeneralUtility::trimExplode(' ', $orderItem, TRUE);
					// count == 1 means that no direction is given
					if ($ascDesc) {
						$orderings[$orderField] = ((strtolower($ascDesc) == 'desc') ?
							QueryInterface::ORDER_DESCENDING :
							QueryInterface::ORDER_ASCENDING);
					} else {
						$orderings[$orderField] = QueryInterface::ORDER_ASCENDING;
					}
				}
			}
		}

		return $orderings;
	}

}
