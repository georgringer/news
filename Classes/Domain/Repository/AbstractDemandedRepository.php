<?php

namespace GeorgRinger\News\Domain\Repository;

use GeorgRinger\News\Domain\Model\DemandInterface;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\BackendInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Abstract demanded repository
 */
abstract class AbstractDemandedRepository extends Repository implements DemandedRepositoryInterface
{

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\Storage\BackendInterface
     */
    protected $storageBackend;

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Storage\BackendInterface $storageBackend
     *
     * @return void
     */
    public function injectStorageBackend(
        BackendInterface $storageBackend
    ): void {
        $this->storageBackend = $storageBackend;
    }

    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param DemandInterface $demand
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface[]
     * @abstract
     */
    abstract protected function createConstraintsFromDemand(
        QueryInterface $query,
        DemandInterface $demand
    ): array;

    /**
     * Returns an array of orderings created from a given demand object.
     *
     * @param DemandInterface $demand
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface[]
     * @abstract
     */
    abstract protected function createOrderingsFromDemand(DemandInterface $demand): array;

    /**
     * Returns the objects of this repository matching the demand.
     *
     * @param DemandInterface $demand
     * @param bool $respectEnableFields
     * @param bool $disableLanguageOverlayMode
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
     */
    public function findDemanded(DemandInterface $demand, $respectEnableFields = true, $disableLanguageOverlayMode = false)
    {
        $query = $this->generateQuery($demand, $respectEnableFields, $disableLanguageOverlayMode);

        return $query->execute();
    }

    /**
     * Returns the database query to get the matching result
     *
     * @param DemandInterface $demand
     * @param bool $respectEnableFields
     * @param bool $disableLanguageOverlayMode
     * @return string
     */
    public function findDemandedRaw(DemandInterface $demand, $respectEnableFields = true, $disableLanguageOverlayMode = false): string
    {
        $query = $this->generateQuery($demand, $respectEnableFields, $disableLanguageOverlayMode);
        $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
        if ($versionInformation->getMajorVersion() >= 11) {
            $queryParser = GeneralUtility::makeInstance(Typo3DbQueryParser::class);
        } else {
            $queryParser = $this->objectManager->get(Typo3DbQueryParser::class);
        }

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

    /**
     * @param DemandInterface $demand
     * @param bool $respectEnableFields
     * @param bool $disableLanguageOverlayMode
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    protected function generateQuery(DemandInterface $demand, $respectEnableFields = true, $disableLanguageOverlayMode = false): \TYPO3\CMS\Extbase\Persistence\QueryInterface
    {
        $query = $this->createQuery();

        $query->getQuerySettings()->setRespectStoragePage(false);

        if ($disableLanguageOverlayMode) {
            $query->getQuerySettings()->setLanguageOverlayMode(false);
        }

        $constraints = $this->createConstraintsFromDemand($query, $demand);

        // Call hook functions for additional constraints
        if ($hooks = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'] ?? []) {
            $params = [
                'demand' => $demand,
                'respectEnableFields' => &$respectEnableFields,
                'query' => $query,
                'constraints' => &$constraints,
            ];
            foreach ($hooks as $reference) {
                GeneralUtility::callUserFunction($reference, $params, $this);
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
     * @return int
     */
    public function countDemanded(DemandInterface $demand, $respectEnableFields = true, $disableLanguageOverlayMode = false): int
    {
        $query = $this->generateQuery($demand, $respectEnableFields, $disableLanguageOverlayMode);

        if ($constraints = $this->createConstraintsFromDemand($query, $demand)) {
            $query->matching(
                $query->logicalAnd($constraints)
            );
        }

        $result = $query->execute();
        return $result->count();
    }
}
