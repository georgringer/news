<?php

namespace GeorgRinger\News\Domain\Repository;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Utility\Validation;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Repository for tag objects
 */
class TagRepository extends AbstractDemandedRepository
{

    /**
     * Find categories by a given pid
     *
     * @param array $idList list of id s
     * @param array $ordering ordering
     * @param string $startingPoint starting point uid or comma separated list
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
     */
    public function findByIdList(array $idList, array $ordering = [], $startingPoint = null)
    {
        if (empty($idList)) {
            throw new \InvalidArgumentException('The given id list is empty.', 1484823596);
        }
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setRespectSysLanguage(false);

        if (count($ordering) > 0) {
            $query->setOrderings($ordering);
        }

        $conditions = [];
        $conditions[] = $query->in('uid', $idList);

        if ($startingPoint !== null) {
            $conditions[] = $query->in('pid', GeneralUtility::trimExplode(',', $startingPoint, true));
        }

        return $query->matching(
            $query->logicalAnd(
                $conditions
            )
        )->execute();
    }

    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @param QueryInterface $query
     * @param DemandInterface $demand
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface[]
     */
    protected function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand): array
    {
        $constraints = [];

        // Storage page
        if ($demand->getStoragePage()) {
            $pidList = GeneralUtility::intExplode(',', $demand->getStoragePage(), true);
            $constraints[] = $query->in('pid', $pidList);
        }

        // Tags
        if ($demand->getTags()) {
            $tagList = GeneralUtility::intExplode(',', $demand->getTags(), true);
            $constraints[] = $query->in('uid', $tagList);
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
     *
     * @return string[]
     *
     * @psalm-return array<string, string>
     */
    protected function createOrderingsFromDemand(DemandInterface $demand): array
    {
        $orderings = [];

        if (Validation::isValidOrdering($demand->getOrder(), $demand->getOrderByAllowed())) {
            $orderList = GeneralUtility::trimExplode(',', $demand->getOrder(), true);

            if (!empty($orderList)) {
                // go through every order statement
                foreach ($orderList as $orderItem) {
                    list($orderField, $ascDesc) = GeneralUtility::trimExplode(' ', $orderItem, true);
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
