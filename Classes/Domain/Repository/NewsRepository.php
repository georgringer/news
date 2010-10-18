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
class Tx_News2_Domain_Repository_NewsRepository extends Tx_News2_Domain_Repository_AbstractNewsRepository {

	/**
	 * List entries
	 */
	public function findList() {
		$query = $this->createQuery();

		$constraints = array();
		$constraints[] = $this->getArchiveRestriction($query);
		$constraints[] = $this->getCategoryRestriction($query);
		$constraints[] = $this->getAdditionalCategoryRestriction($query);

		return $this->executeQuery($query, $constraints);
	}

	/**
	 * Latest entries
	 */
	public function findLatest() {
		$query = $this->createQuery();

		$constraints = array();
		$constraints[] = $this->getArchiveRestriction($query);
		$constraints[] = $this->getCategoryRestriction($query);
		$constraints[] = $this->getLatestTimeLimitRestriction($query);

		return $this->executeQuery($query, $constraints);
	}

    /**
     * @param  $searchString string to search for
     * @return
     */
	public function findBySearch($searchString) {
		$query = $this->createQuery();

		$constraints = array();
		$constraints[] = $this->getArchiveRestriction($query);
		$constraints[] = $this->getCategoryRestriction($query);
		$constraints[] = $this->getSearchConstraint($query, $searchString);

		return $this->executeQuery($query, $constraints);
	}


	/**
	 * Add the constraints to the query and add Ordering, Limit + Offset
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param array $constraints
	 * @return array<Tx_News2_Domain_Model_News>
	 */
	public function executeQuery(Tx_Extbase_Persistence_QueryInterface $query, array $constraints) {
		$constraints = $this->checkConstraintArray($constraints);

		if(!empty($constraints)) {
        	$query->matching(
				$query->logicalAnd($constraints)
			);
		}

		$this->setFinalOrdering($query);
		$this->setLimitRestriction($query);
		$this->setOffsetRestriction($query);

		return $query->execute();
	}


}


?>