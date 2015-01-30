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

/**
 * Abstract demanded repository
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
abstract class AbstractDemandedRepository
	extends \TYPO3\CMS\Extbase\Persistence\Repository
	implements \GeorgRinger\News\Domain\Repository\DemandedRepositoryInterface {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\Storage\BackendInterface
	 */
	protected $storageBackend;
	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\Storage\BackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\TYPO3\CMS\Extbase\Persistence\Generic\Storage\BackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Returns an array of constraints created from a given demand object.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
	 * @abstract
	 */
	abstract protected function createConstraintsFromDemand(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, DemandInterface $demand);

	/**
	 * Returns an array of orderings created from a given demand object.
	 *
	 * @param DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
	 * @abstract
	 */
	abstract protected function createOrderingsFromDemand(DemandInterface $demand);

	/**
	 * Returns the objects of this repository matching the demand.
	 *
	 * @param DemandInterface $demand
	 * @param boolean $respectEnableFields
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findDemanded(DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->generateQuery($demand, $respectEnableFields);

		return $query->execute();
	}

	/**
	 * Returns the database query to get the matching result
	 *
	 * @param DemandInterface $demand
	 * @param boolean $respectEnableFields
	 * @return string
	 */
	public function findDemandedRaw(DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->generateQuery($demand, $respectEnableFields);

		$queryParser = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Storage\\Typo3DbQueryParser');
		list($hash, $parameters) = $queryParser->preparseQuery($query);
		$statementParts = $queryParser->parseQuery($query);

		// Limit and offset are not cached to allow caching of pagebrowser queries.
		$statementParts['limit'] = ((int)$query->getLimit() ?: NULL);
		$statementParts['offset'] = ((int)$query->getOffset() ?: NULL);

		$tableNameForEscape = (reset($statementParts['tables']) ?: 'foo');
		foreach ($parameters as $parameterPlaceholder => $parameter) {
			if ($parameter instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
				$parameter = $parameter->_loadRealInstance();
			}

			if ($parameter instanceof \DateTime) {
				$parameter = $parameter->format('U');
			} elseif ($parameter instanceof \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface) {
				$parameter = (int)$parameter->getUid();
			} elseif (is_array($parameter)) {
				$subParameters = array();
				foreach ($parameter as $subParameter) {
					$subParameters[] = $GLOBALS['TYPO3_DB']->fullQuoteStr($subParameter, $tableNameForEscape);
				}
				$parameter = implode(',', $subParameters);
			} elseif ($parameter === NULL) {
				$parameter = 'NULL';
			} elseif (is_bool($parameter)) {
				return ($parameter === TRUE ? 1 : 0);
			} else {
				$parameter = $GLOBALS['TYPO3_DB']->fullQuoteStr((string)$parameter, $tableNameForEscape);
			}

			$statementParts['where'] = str_replace($parameterPlaceholder, $parameter, $statementParts['where']);
		}

		$statementParts = array(
			'selectFields' => implode(' ', $statementParts['keywords']) . ' ' . implode(',', $statementParts['fields']),
			'fromTable'    => implode(' ', $statementParts['tables']) . ' ' . implode(' ', $statementParts['unions']),
			'whereClause'  => (!empty($statementParts['where']) ? implode('', $statementParts['where']) : '1')
				. (!empty($statementParts['additionalWhereClause'])
					? ' AND ' . implode(' AND ', $statementParts['additionalWhereClause'])
					: ''
			),
			'orderBy'      => (!empty($statementParts['orderings']) ? implode(', ', $statementParts['orderings']) : ''),
			'limit'        => ($statementParts['offset'] ? $statementParts['offset'] . ', ' : '')
				. ($statementParts['limit'] ? $statementParts['limit'] : '')
		);

		$sql = $GLOBALS['TYPO3_DB']->SELECTquery(
			$statementParts['selectFields'],
			$statementParts['fromTable'],
			$statementParts['whereClause'],
			'',
			$statementParts['orderBy'],
			$statementParts['limit']
		);

		return $sql;
	}

	protected function generateQuery(DemandInterface $demand, $respectEnableFields = TRUE) {
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
				\TYPO3\CMS\Core\Utility\GeneralUtility::callUserFunction($reference, $params, $this);
			}
		}

		if ($respectEnableFields === FALSE) {
			$query->getQuerySettings()->setIgnoreEnableFields(TRUE);

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
			if (!$query->getLimit()) {
				$query->setLimit(PHP_INT_MAX);
			}
			$query->setOffset((int) $demand->getOffset());
		}

		return $query;
	}

	/**
	 * Returns the total number objects of this repository matching the demand.
	 *
	 * @param DemandInterface $demand
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function countDemanded(DemandInterface $demand) {
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
