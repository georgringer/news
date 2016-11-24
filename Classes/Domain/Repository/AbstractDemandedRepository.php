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
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;

/**
 * Abstract demanded repository
 *
 */
abstract class AbstractDemandedRepository
    extends \TYPO3\CMS\Extbase\Persistence\Repository
    implements \GeorgRinger\News\Domain\Repository\DemandedRepositoryInterface
{

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\Storage\BackendInterface
     */
    protected $storageBackend;

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Storage\BackendInterface $storageBackend
     * @return void
     */
    public function injectStorageBackend(\TYPO3\CMS\Extbase\Persistence\Generic\Storage\BackendInterface $storageBackend
    )
    {
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
    abstract protected function createConstraintsFromDemand(
        \TYPO3\CMS\Extbase\Persistence\QueryInterface $query,
        DemandInterface $demand
    );

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
     * @param bool $respectEnableFields
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findDemanded(DemandInterface $demand, $respectEnableFields = true)
    {
        $query = $this->generateQuery($demand, $respectEnableFields);

        return $query->execute();
    }

    /**
     * Returns the database query to get the matching result
     *
     * @param DemandInterface $demand
     * @param bool $respectEnableFields
     * @return string
     */
    public function findDemandedRaw(DemandInterface $demand, $respectEnableFields = true)
    {
        $query = $this->generateQuery($demand, $respectEnableFields);
        // @see https://forge.typo3.org/issues/77502
        $isBelow8 = method_exists(Typo3DbQueryParser::class, 'preparseQuery');
        // @see https://github.com/TYPO3/TYPO3.CMS/blob/ab0ce01d4abd9dfbac999f7a12bcfd1a39144474/typo3/sysext/core/Documentation/Changelog/8.4/Breaking-77379-DoctrineTypo3DbQueryParser.rst
        $isBelow8_4 = method_exists(Typo3DbQueryParser::class, 'parseQuery');
        $parameters = [];

        $queryParser = $this->objectManager->get(Typo3DbQueryParser::class);
        if ($isBelow8_4) {
            if($isBelow8) {
                list($hash, $parameters) = $queryParser->preparseQuery($query);
            }

            $statementParts = $queryParser->parseQuery($query);

            $statementParts['limit'] = ((int)$query->getLimit() ?: null);
            $statementParts['offset'] = ((int)$query->getOffset() ?: null);

            if ($isBelow8) {
                $tableNameForEscape = (reset($statementParts['tables']) ?: 'foo');
                foreach ($parameters as $parameterPlaceholder => $parameter) {
                    if ($parameter instanceof LazyLoadingProxy) {
                        $parameter = $parameter->_loadRealInstance();
                    }

                    if ($parameter instanceof \DateTime) {
                        $parameter = $parameter->format('U');
                    } elseif ($parameter instanceof DomainObjectInterface) {
                        $parameter = (int)$parameter->getUid();
                    } elseif (is_array($parameter)) {
                        $subParameters = [];
                        foreach ($parameter as $subParameter) {
                            $subParameters[] = $GLOBALS['TYPO3_DB']->fullQuoteStr($subParameter, $tableNameForEscape);
                        }
                        $parameter = implode(',', $subParameters);
                    } elseif ($parameter === null) {
                        $parameter = 'NULL';
                    } elseif (is_bool($parameter)) {
                        return $parameter === true ? 1 : 0;
                    } else {
                        $parameter = $GLOBALS['TYPO3_DB']->fullQuoteStr((string)$parameter, $tableNameForEscape);
                    }

                    $statementParts['where'] = str_replace($parameterPlaceholder, $parameter, $statementParts['where']);
                }
            }

            $statementParts = [
                'selectFields' => implode(' ', $statementParts['keywords']) . ' ' . implode(',', $statementParts['fields']),
                'fromTable' => implode(' ', $statementParts['tables']) . ' ' . implode(' ', $statementParts['unions']),
                'whereClause' => (!empty($statementParts['where']) ? implode('', $statementParts['where']) : '1')
                    . (!empty($statementParts['additionalWhereClause'])
                        ? ' AND ' . implode(' AND ', $statementParts['additionalWhereClause'])
                        : ''
                    ),
                'orderBy' => (!empty($statementParts['orderings']) ? implode(', ', $statementParts['orderings']) : ''),
                'limit' => ($statementParts['offset'] ? $statementParts['offset'] . ', ' : '')
                    . ($statementParts['limit'] ? $statementParts['limit'] : '')
            ];

            $sql = $GLOBALS['TYPO3_DB']->SELECTquery(
                $statementParts['selectFields'],
                $statementParts['fromTable'],
                $statementParts['whereClause'],
                '',
                $statementParts['orderBy'],
                $statementParts['limit']
            );

            return $sql;
        } else {
            $queryBuilder = $queryParser->convertQueryToDoctrineQueryBuilder($query);
            $queryParameters = $queryBuilder->getParameters();
            $params = [];
            foreach ($queryParameters as $key => $value) {
                // prefix array keys with ':'
                $params[':' . $key] = (is_numeric($value)) ? $value : "'" . $value . "'"; //all non numeric values have to be quoted
                unset($params[$key]);
            }
            // replace placeholders with real values
            $query = strtr($queryBuilder->getSQL(), $params);
            return $query;
        }
    }

    protected function generateQuery(DemandInterface $demand, $respectEnableFields = true)
    {
        $query = $this->createQuery();

        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraints = $this->createConstraintsFromDemand($query, $demand);

        // Call hook functions for additional constraints
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'])) {
            $params = [
                'demand' => $demand,
                'respectEnableFields' => &$respectEnableFields,
                'query' => $query,
                'constraints' => &$constraints,
            ];
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'] as $reference) {
                \TYPO3\CMS\Core\Utility\GeneralUtility::callUserFunction($reference, $params, $this);
            }
        }

        if ($respectEnableFields === false) {
            $query->getQuerySettings()->setIgnoreEnableFields(true);

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
        if ($demand->getLimit() != null) {
            $query->setLimit((int)$demand->getLimit());
        }

        // @todo consider moving this to a separate function as well
        if ($demand->getOffset() != null) {
            if (!$query->getLimit()) {
                $query->setLimit(PHP_INT_MAX);
            }
            $query->setOffset((int)$demand->getOffset());
        }

        return $query;
    }

    /**
     * Returns the total number objects of this repository matching the demand.
     *
     * @param DemandInterface $demand
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function countDemanded(DemandInterface $demand)
    {
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
    protected function replacePlaceholders(&$sqlString, array $parameters, $tableName = 'foo')
    {
        if (substr_count($sqlString, '?') !== count($parameters)) {
            throw new \TYPO3\CMS\Extbase\Persistence\Generic\Exception('The number of question marks to replace must be equal to the number of parameters.',
                1242816074);
        }
        $offset = 0;
        foreach ($parameters as $parameter) {
            $markPosition = strpos($sqlString, '?', $offset);
            if ($markPosition !== false) {
                if ($parameter === null) {
                    $parameter = 'NULL';
                } elseif (is_array($parameter) || $parameter instanceof \ArrayAccess || $parameter instanceof \Traversable) {
                    $items = [];
                    foreach ($parameter as $item) {
                        $items[] = $GLOBALS['TYPO3_DB']->fullQuoteStr($item, $tableName);
                    }
                    $parameter = '(' . implode(',', $items) . ')';
                } else {
                    $parameter = $GLOBALS['TYPO3_DB']->fullQuoteStr($parameter, $tableName);
                }
                $sqlString = substr($sqlString, 0, $markPosition) . $parameter . substr($sqlString,
                        ($markPosition + 1));
            }
            $offset = $markPosition + strlen($parameter);
        }
    }
}
