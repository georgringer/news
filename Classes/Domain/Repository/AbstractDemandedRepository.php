<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Domain\Repository;

use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Event\ModifyDemandRepositoryEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\BackendInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Abstract demanded repository
 */
abstract class AbstractDemandedRepository extends Repository implements DemandedRepositoryInterface
{
    /** @var BackendInterface */
    protected $storageBackend;
    protected EventDispatcherInterface $eventDispatcher;

    public function injectStorageBackend(
        BackendInterface $storageBackend
    ): void {
        $this->storageBackend = $storageBackend;
    }

    public function __construct()
    {
        $this->eventDispatcher = GeneralUtility::makeInstance(EventDispatcher::class);
        parent::__construct();
    }

    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @return ConstraintInterface[]
     * @abstract
     */
    abstract protected function createConstraintsFromDemand(
        QueryInterface $query,
        DemandInterface $demand
    ): array;

    /**
     * Returns an array of orderings created from a given demand object.
     *
     * @return ConstraintInterface[]
     * @abstract
     */
    abstract protected function createOrderingsFromDemand(DemandInterface $demand): array;

    /**
     * Returns the objects of this repository matching the demand.
     *
     * @param bool $respectEnableFields
     * @param bool $disableLanguageOverlayMode
     * @return QueryResultInterface
     */
    public function findDemanded(DemandInterface $demand, $respectEnableFields = true, $disableLanguageOverlayMode = false)
    {
        $query = $this->generateQuery($demand, $respectEnableFields, $disableLanguageOverlayMode);

        return $query->execute();
    }

    /**
     * Returns the database query to get the matching result
     *
     * @param bool $respectEnableFields
     * @param bool $disableLanguageOverlayMode
     */
    public function findDemandedRaw(DemandInterface $demand, $respectEnableFields = true, $disableLanguageOverlayMode = false): string
    {
        $query = $this->generateQuery($demand, $respectEnableFields, $disableLanguageOverlayMode);
        $queryParser = GeneralUtility::makeInstance(Typo3DbQueryParser::class);

        $queryBuilder = $queryParser->convertQueryToDoctrineQueryBuilder($query);
        $queryParameters = $queryBuilder->getParameters();
        $params = [];
        foreach ($queryParameters as $key => $value) {
            // prefix array keys with ':'
            $params[':' . $key] = "'" . $value . "'";
            unset($params[$key]);
        }
        // replace placeholders with real values
        $query = strtr($queryBuilder->getSQL(), $params);
        return $query;
    }

    /**
     * @param bool $respectEnableFields
     * @param bool $disableLanguageOverlayMode
     */
    protected function generateQuery(DemandInterface $demand, $respectEnableFields = true, $disableLanguageOverlayMode = false): QueryInterface
    {
        $query = $this->createQuery();

        $query->getQuerySettings()->setRespectStoragePage(false);

        if ($disableLanguageOverlayMode) {
            $languageAspect = $query->getQuerySettings()->getLanguageAspect();
            $languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), LanguageAspect::OVERLAYS_OFF);
            $query->getQuerySettings()->setLanguageAspect($languageAspect);
        }

        $constraints = $this->createConstraintsFromDemand($query, $demand);

        // Call hook functions for additional constraints
        if ($hooks = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'] ?? []) {
            trigger_error('The hook $GLOBALS[\'TYPO3_CONF_VARS\'][\'EXT\'][\'news\'][\'Domain/Repository/AbstractDemandedRepository.php\'][\'findDemanded\'] has been deprecated. Use the ModifyDemandRepositoryEvent instead.', E_USER_DEPRECATED);
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

        $event = new ModifyDemandRepositoryEvent($demand, $respectEnableFields, $query, $constraints);
        $this->eventDispatcher->dispatch($event);
        $respectEnableFields = $event->isRespectEnableFields();
        $constraints = $event->getConstraints();

        if ($respectEnableFields === false) {
            $query->getQuerySettings()->setIgnoreEnableFields(true);

            $constraints[] = $query->equals('deleted', 0);
        }

        if (!empty($constraints)) {
            $query->matching(
                $query->logicalAnd(...array_values($constraints))
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
     */
    public function countDemanded(DemandInterface $demand, $respectEnableFields = true, $disableLanguageOverlayMode = false): int
    {
        $query = $this->generateQuery($demand, $respectEnableFields, $disableLanguageOverlayMode);

        if ($constraints = $this->createConstraintsFromDemand($query, $demand)) {
            $query->matching(
                $query->logicalAnd(...$constraints)
            );
        }

        return $query->execute()->count();
    }
}
