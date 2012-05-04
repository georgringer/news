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
 * Abstraced demanded repository
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
abstract class Tx_News_Domain_Repository_AbstractDemandedRepository extends Tx_Extbase_Persistence_Repository implements Tx_News_Domain_Repository_DemandedRepositoryInterface {

	/**
	 * Returns an array of constraints created from a given demand object.
	 *
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @return array<Tx_Extbase_Persistence_QOM_Constrain>
	 * @abstract
	 */
	abstract protected function createConstraintsFromDemand(Tx_Extbase_Persistence_QueryInterface $query, Tx_News_Domain_Model_DemandInterface $demand);

	/**
	 * Returns an array of orderings created from a given demand object.
	 *
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @return array<Tx_Extbase_Persistence_QOM_Constrain>
	 * @abstract
	 */
	abstract protected function createOrderingsFromDemand(Tx_News_Domain_Model_DemandInterface $demand);

	/**
	 * Returns the total number objects of this repository matching the demand.
	 *
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @param boolean $respectEnableFields
	 * @return Tx_Extbase_Persistence_QueryResultInterface
	 */
	public function findDemanded(Tx_News_Domain_Model_DemandInterface $demand, $respectEnableFields = TRUE) {

		$query = $this->createQuery();

			// @todo find a better place for setting respectStoragePage. Perhaps $this->createQuery().
		$query->getQuerySettings()->setRespectStoragePage(FALSE);

		$constraints = $this->createConstraintsFromDemand($query, $demand);

			// Call hook functions for additional constraints
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'])) {
			$params = array(
				'demand' => $demand,
				'respectEnableFields' => &$respectEnableFields,
				'query' => $query,
				'constraints' => &$constraints,
			);
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'] as $reference) {
				t3lib_div::callUserFunction($reference, $params, $this);
			}
		}

		if ($respectEnableFields === FALSE) {
			$query->getQuerySettings()->setRespectEnableFields(FALSE);
			$constraints[] = $query->equals('deleted', 0);
		}

		if (!empty($constraints)) {
			$query->matching(
				$query->logicalAnd($constraints)
			);
		}

		if ($orderings = $this->createOrderingsFromDemand($demand)) {
			$query->setOrderings($orderings);
		}

			// @todo consider moving this to a seperate function as well
		if ($demand->getLimit() != NULL) {
			$query->setLimit((int) $demand->getLimit());
		}

			// @todo consider moving this to a seperate function as well
		if ($demand->getOffset() != NULL) {
			$query->setOffset((int) $demand->getOffset());
		}

		return $query->execute();
	}

	/**
	 * Returns the total number objects of this repository matching the demand.
	 *
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @return Tx_Extbase_Persistence_QueryResultInterface
	 */
	public function countDemanded(Tx_News_Domain_Model_DemandInterface $demand) {
		$query = $this->createQuery();

		if ($constraints = $this->createConstraintsFromDemand($query, $demand)) {
			$query->matching(
				$query->logicalAnd($constraints)
			);
		}

		return $query->execute()->count();
	}
}
?>