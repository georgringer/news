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
 *
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_News2_Domain_Repository_AbstractNewsRepository extends Tx_Extbase_Persistence_Repository {

	protected $order;
	protected $orderRespectTopNews;
	protected $categorySetting;
	protected $categories;
	protected $additionalCategories;
	protected $additionalCategorySetting = 'and';
	protected $archiveSetting;
	protected $latestTimeLimit = NULL;
	protected $searchFields;
	protected $limit = NULL;
	protected $offset = NULL;
	protected $constraints = array();

	/**
	 * Set the order like title desc, tstamp asc
	 *
	 * @param  string $order order
	 */
	public function setOrder($order) {
	 $this->order = $order;
	}

	public function setOrderRespectTopNews($respectTopNews) {
		$this->orderRespectTopNews = $respectTopNews;
	}

	/**
	 * Category setting
	 *
	 * @param  string $settings
	 */
	public function setCategorySettings($settings) {
		$this->categorySetting = $settings;
	}

	/**
	 * Categories
	 *
	 * @param  $categories comma separated list
	 */
	public function setCategories($categories) {
		$this->categories = t3lib_div::intExplode(',', $categories, TRUE);
	}

	/**
	 * Additional Categories
	 *
	 * @param  $categories string comma separated list
	 */
	public function setAdditionalCategories($categories) {
		$this->additionalCategories = t3lib_div::intExplode(',', $categories, TRUE);
	}

	/**
	 * Set the limit for latest news
	 *
	 * @param  mixed $timeLimit integers for a difference to now like 86400 for 1 day or use text like "3 days"
	 * @return void
	 */
	public function setLatestTimeLimit($timeLimit) {
			// integer = timestamp
		if (is_int($timeLimit)) {
			$this->latestTimeLimit = $GLOBALS['EXEC_TIME'] - $timeLimit;
		} else {
				// try to check strtotime
			$timeFromString = strtotime($timeLimit);
			if($timeFromString) {
				$this->latestTimeLimit = $timeFromString;
			} else {
				throw new Exception('Latest time limit could not be resolved to an integer. Given was: ' . htmlspecialchars($timeLimit));
			}

		}
	}

	/**
	 * Archive settings
	 *
	 * @param string $settings
	 */
	public function setArchiveSettings($settings) {
		$this->archiveSetting = $settings;
	}

	/**
	 * Limit
	 *
	 * @param  integer $limit
	 */
	public function setLimit($limit) {
		$limit = (int)$limit;
		if ($limit > 0) {
			$this->limit = $limit;
		}
	}

	/**
	 * Offset
	 *
	 * @param  integer $offset
	 */
	public function setOffset($offset) {
		$offset = (int)$offset;
		if ($offset > 0) {
			$this->offset = $offset;
		}
	}

	/**
	 * Search fields
	 *
	 * @param  string $searchFields comma separated list of fields to search in
	 */
	public function setSearchFields($searchFields) {
		$this->searchFields = t3lib_div::trimExplode(',', $searchFields, TRUE);
	}


	/**
	 * Get the category constraints
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 */
	protected function getCategoryRestriction(Tx_Extbase_Persistence_QueryInterface $query) {
        $constraint = NULL;
		$selectedCategories = $this->categories;

		if (count($selectedCategories) > 0 && !empty($this->categorySetting)) {
			$constraintList = array();

			foreach($selectedCategories as $categoryId) {
				$constraintList[] = $query->equals('category.uid', $categoryId);
			}

				// OR > Show items with selected categories (OR)
			if ($this->categorySetting  == 'or') {
				$constraint = $query->logicalOr($constraintList);

				// AND > Show items with selected categories (AND)
			} elseif ($this->categorySetting  == 'and') {
				$constraint = $query->logicalAnd($constraintList);

				// NOT > Do NOT show items with selected categories (AND)
			} elseif($this->categorySetting == 'notand') {
				$constraint = $query->logicalNot($query->logicalAnd($constraintList));

				// NOT > Do NOT show items with selected categories (OR)
			} elseif($this->categorySetting == 'notor') {
				$constraint = $query->logicalNot($query->logicalOr($constraintList));
			}
		}

        return $constraint;
	}

	protected function getAdditionalCategoryRestriction(Tx_Extbase_Persistence_QueryInterface $query) {
        $constraint = NULL;
		$selectedCategories = $this->additionalCategories;

		if (count($selectedCategories) > 0 && !empty($this->additionalCategorySetting)) {
			$constraintList = array();

			foreach($selectedCategories as $categoryId) {
				$constraintList[] = $query->equals('category.uid', $categoryId);
			}

				// OR > Show items with selected categories (OR)
			if ($this->additionalCategorySetting  == 'or') {
				$constraint = $query->logicalOr($constraintList);

				// AND > Show items with selected categories (AND)
			} elseif ($this->additionalCategorySetting  == 'and') {
				$constraint = $query->logicalAnd($constraintList);

				// NOT > Do NOT show items with selected categories (AND)
			} elseif($this->additionalCategorySetting == 'notand') {
				$constraint = $query->logicalNot($query->logicalAnd($constraintList));

				// NOT > Do NOT show items with selected categories (OR)
			} elseif($this->additionalCategorySetting == 'notor') {
				$constraint = $query->logicalNot($query->logicalOr($constraintList));
			}
		}

        return $constraint;
	}

	/**
	 * Get the archive constraints
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @return
	 */
	protected function getArchiveRestriction(Tx_Extbase_Persistence_QueryInterface $query) {
        $constraint = NULL;

			// archived news
		if ($this->archiveSetting == 'archived') {
			$constraint = $query->lessThan(
				'archive', $GLOBALS['EXEC_TIME']
			);
			// active news
		} elseif ($this->archiveSetting == 'active') {
			$constraint = $query->greaterThanOrEqual(
				'archive', $GLOBALS['EXEC_TIME']
			);
		}

        return $constraint;
	}

	/**
	 * Get the latest time limit constraint
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 */
	protected function getLatestTimeLimitRestriction(Tx_Extbase_Persistence_QueryInterface $query) {
		$constraint = NULL;
		if ( $this->latestTimeLimit !== NULL) {
			$constraint = $query->greaterThanOrEqual(
				'datetime',
				$this->latestTimeLimit
			);
		}

		return $constraint;
	}

	/**
	 * Get the search constraints
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param  string $searchString
	 * @return
	 */
	protected function getSearchConstraint(Tx_Extbase_Persistence_QueryInterface $query, $searchString) {
		$constraint = NULL;
		$searchFields = $this->searchFields;

		if (!empty($searchString) && !empty($searchFields)) {
			$constraintItems = array();

			foreach($searchFields as $searchField) {
				$constraintItems[] = $query->like($searchField, '%' . $searchString . '%');
			}

			$constraint = $query->logicalOr($constraintItems);
		}

		return $constraint;
	}

	/**
	 * Set limit
	 *
	 * @param Tx_Extbase_Persistence_Query $query
	 */
	protected function setLimitRestriction(Tx_Extbase_Persistence_QueryInterface $query) {
		if ($this->limit != NULL) {
			$query->setLimit($this->limit);
		}
	}

	/**
	 * Set offset
	 *
	 * @param Tx_Extbase_Persistence_Query $query
	 */
	protected function setOffsetRestriction(Tx_Extbase_Persistence_QueryInterface $query) {
		if ($this->offset != NULL) {
			$query->setOffset($this->offset);
		}
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
			foreach($orderList as $orderEntry) {
				$simpleOrderStatement = t3lib_div::trimExplode(' ', $orderEntry, TRUE);
				$count = count($simpleOrderStatement);

					// count == 1 means that no direction is given
				if ($count == 1) {
					$finalOrdering[$simpleOrderStatement[0]] = Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING;
					// count == 2 means that field + direction is given
				} elseif($count == 2) {
					$finalOrdering[$simpleOrderStatement[0]] = (strtolower(($simpleOrderStatement[1]) == 'desc')) ? Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING : Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING;
				}
			}
		}

		if (!empty($finalOrdering)) {
			$query->setOrderings($finalOrdering);
		}
	}



	/**
	 * Remove empty entries from constraint list
	 */
	public function checkConstraintArray(array $constraints) {
		foreach($constraints as $key => $constraint) {
			if ($constraint === NULL || empty($constraint)) {
				unset($constraints[$key]);
			}
		}
		return $constraints;

	}	
}

?>