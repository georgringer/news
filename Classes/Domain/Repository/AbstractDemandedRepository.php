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
 * Abstract demanded repository
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
abstract class Tx_News_Domain_Repository_AbstractDemandedRepository
	extends Tx_Extbase_Persistence_Repository
	implements Tx_News_Domain_Repository_DemandedRepositoryInterface {

	/**
	 * @var Tx_Extbase_Persistence_Storage_BackendInterface
	 */
	protected $storageBackend;
	/**
	 * @param Tx_Extbase_Persistence_Storage_BackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(Tx_Extbase_Persistence_Storage_BackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

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
	 * Returns the objects of this repository matching the demand.
	 *
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @param boolean $respectEnableFields
	 * @return Tx_Extbase_Persistence_QueryResultInterface
	 */
	public function findDemanded(Tx_News_Domain_Model_DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->generateQuery($demand, $respectEnableFields);

		return $query->execute();
	}

	/**
	 * Returns the database query to get the matching result
	 *
	 * @param Tx_News_Domain_Model_DemandInterface $demand
	 * @param boolean $respectEnableFields
	 * @return string
	 */
	public function findDemandedRaw(Tx_News_Domain_Model_DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->generateQuery($demand, $respectEnableFields);

		$dbStorage = $this->storageBackend;

		$parameters = array();
		$statementParts = $dbStorage->parseQuery($query, $parameters);
		$sql = $dbStorage->buildQuery($statementParts, $parameters);
		$tableName = 'foo';
		if (is_array($statementParts && !empty($statementParts['tables'][0]))) {
			$tableName = $statementParts['tables'][0];
		}

		$this->replacePlaceholders($sql, $parameters, $tableName);

		return $sql;
	}

	protected function generateQuery(Tx_News_Domain_Model_DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->createQuery();

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

		// @todo consider moving this to a separate function as well
		if ($demand->getLimit() != NULL) {
			$query->setLimit((int) $demand->getLimit());
		}

		// @todo consider moving this to a separate function as well
		if ($demand->getOffset() != NULL) {
			$query->setOffset((int) $demand->getOffset());
		}

		return $query;
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

		$result = $query->execute();
		return $result->count();
	}

	/**
	 * Copy of the one from Typo3DbBackend
	 * Replace query placeholders in a query part by the given
	 * parameters.
	 *
	 * @param string $sqlString The query part with placeholders
	 * @param array $parameters The parameters
	 * @param string $tableName
	 *
	 * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
	 * @return void
	 */
	protected function replacePlaceholders(&$sqlString, array $parameters, $tableName = 'foo') {
		if (substr_count($sqlString, '?') !== count($parameters)) {
			throw new \TYPO3\CMS\Extbase\Persistence\Generic\Exception('The number of question marks to replace must be equal to the number of parameters.', 1242816074);
		}
		$offset = 0;
		foreach ($parameters as $parameter) {
			$markPosition = strpos($sqlString, '?', $offset);
			if ($markPosition !== FALSE) {
				if ($parameter === NULL) {
					$parameter = 'NULL';
				} elseif (is_array($parameter) || $parameter instanceof \ArrayAccess || $parameter instanceof \Traversable) {
					$items = array();
					foreach ($parameter as $item) {
						$items[] = $GLOBALS['TYPO3_DB']->fullQuoteStr($item, $tableName);
					}
					$parameter = '(' . implode(',', $items) . ')';
				} else {
					$parameter = $GLOBALS['TYPO3_DB']->fullQuoteStr($parameter, $tableName);
				}
				$sqlString = substr($sqlString, 0, $markPosition) . $parameter . substr($sqlString, ($markPosition + 1));
			}
			$offset = $markPosition + strlen($parameter);
		}
	}
}
?>