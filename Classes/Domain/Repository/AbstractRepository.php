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
 * Abstract repository for commons in news & category
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id: AbstractNewsRepository.php 40038 2010-11-10 14:10:43Z just2b $
 */
class Tx_News2_Domain_Repository_AbstractRepository extends Tx_Extbase_Persistence_Repository {
	protected $storagePage;
	protected $limit = NULL;
	protected $offset = NULL;
	protected $order;



	/**
	 * Set the order like title desc, tstamp asc
	 *
	 * @param  string $order order
	 */
	public function setOrder($order) {
		$this->order = $order;
	}

	/**
	 * Limit
	 *
	 * @param  integer $limit
	 */
	public function setLimit($limit) {
		$this->limit = (int)$limit;
	}

	/**
	 * Offset
	 *
	 * @param  integer $offset
	 */
	public function setOffset($offset) {
		$this->offset = (int)$offset;
	}

	public function setStoragePage($pidlist) {
		$this->storagePage = $pidlist;
	}

	/**
	 * Storagepage restriction
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 */
	public function setStoragePageRestriction(Tx_Extbase_Persistence_QueryInterface $query) {
		$constraint = NULL;

		$query->getQuerySettings()->setRespectStoragePage(FALSE);

		if ($this->storagePage != 0) {
			$pidList = t3lib_div::intExplode(',', $this->storagePage, TRUE);
			$constraint = $query->in('pid', $pidList);
		}

		return $constraint;
	}


	/**
	 * Set Ordering
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 */
	protected function setFinalOrdering(Tx_Extbase_Persistence_QueryInterface $query) {
		$finalOrdering = array();

			// set order by top news first
		if ($this->orderRespectTopNews) {
			$finalOrdering['istopnews'] = Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING;
		}

		$orderList = t3lib_div::trimExplode(',', $this->order, TRUE);
		if (!empty($orderList)) {

				// go through every order statement
			foreach ($orderList as $orderEntry) {
				$simpleOrderStatement = t3lib_div::trimExplode(' ', $orderEntry, TRUE);
				$count = count($simpleOrderStatement);

					// count == 1 means that no direction is given
				if ($count == 1) {
					$finalOrdering[$simpleOrderStatement[0]] = Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING;
					// count == 2 means that field + direction is given
				} elseif ($count == 2) {
					$finalOrdering[$simpleOrderStatement[0]] = (strtolower(($simpleOrderStatement[1]) == 'desc')) ? Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING : Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING;
				}
			}
		}

		if (!empty($finalOrdering)) {
			$query->setOrderings($finalOrdering);
		}
	}

	/**
	 * Set limit
	 *
	 * @param Tx_Extbase_Persistence_Query $query
	 */
	public function setLimitRestriction(Tx_Extbase_Persistence_QueryInterface $query) {
		if ($this->limit != NULL) {
			$query->setLimit($this->limit);
		}
	}

	/**
	 * Set offset
	 *
	 * @param Tx_Extbase_Persistence_Query $query
	 */
	public function setOffsetRestriction(Tx_Extbase_Persistence_QueryInterface $query) {
		if ($this->offset != NULL) {
			$query->setOffset($this->offset);
		}
	}

	/**
	 * A debug constraint to show query because it will never work.
	 * SQLdebug needs to be turned on
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @return type
	 */
	public function setDebugConstraint(Tx_Extbase_Persistence_QueryInterface $query) {
		$constraint = $query->equals('xyz', 0);

		return $constraint;
	}

	/**
	 * Remove empty entries from constraint list
	 *
	 * @param array $constraints
	 * @return array $constraints
	 */
	public function checkConstraintArray(array $constraints) {
		foreach ($constraints as $key => $constraint) {
			if ($constraint === NULL || empty($constraint)) {
				unset($constraints[$key]);
			}
		}
		return $constraints;
	}

	/**
	 * Add the constraints to the query and add Ordering, Limit + Offset
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param array $constraints
	 * @return Tx_Extbase_Persistence_QueryResultInterface
	 */
	public function executeQuery(Tx_Extbase_Persistence_QueryInterface $query, array $constraints) {
		$constraints = $this->checkConstraintArray($constraints);

		if (!empty($constraints)) {
			$query->matching(
				$query->logicalAnd($constraints)
			);
		}

		$this->setFinalOrdering($query);
		$this->setLimitRestriction($query);
		$this->setOffsetRestriction($query);

		return $query->execute();
	}

	/**
	 * Count query
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param array $constraints
	 * @return integer
	 */
	public function executeCountQuery(Tx_Extbase_Persistence_QueryInterface $query, array $constraints) {
		$constraints = $this->checkConstraintArray($constraints);

		if (!empty($constraints)) {
			$query->matching(
				$query->logicalAnd($constraints)
			);
		}

//		$this->setFinalOrdering($query);
		$this->setLimitRestriction($query);
		$this->setOffsetRestriction($query);

		return $query->execute()->count();
	}

}

?>