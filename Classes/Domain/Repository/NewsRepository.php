<?php

namespace GeorgRinger\News\Domain\Repository;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Service\CategoryService;
use GeorgRinger\News\Utility\ConstraintHelper;
use GeorgRinger\News\Utility\Validation;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * News repository with all the callable functionality
 */
class NewsRepository extends AbstractDemandedRepository
{

    /**
     * Returns a category constraint created by
     * a given list of categories and a junction string
     *
     * @param QueryInterface $query
     * @param  array $categories
     * @param  string $conjunction
     * @param  bool $includeSubCategories
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface|null
     */
    protected function createCategoryConstraint(
        QueryInterface $query,
        $categories,
        $conjunction,
        $includeSubCategories = false
    ): ?\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface {
        $constraint = null;
        $categoryConstraints = [];

        // If "ignore category selection" is used, nothing needs to be done
        if (empty($conjunction)) {
            return $constraint;
        }

        if (!is_array($categories)) {
            $categories = GeneralUtility::intExplode(',', $categories, true);
        }
        foreach ($categories as $category) {
            if ($includeSubCategories) {
                $subCategories = GeneralUtility::trimExplode(
                    ',',
                    CategoryService::getChildrenCategories($category, 0, '', true),
                    true
                );
                $subCategoryConstraint = [];
                $subCategoryConstraint[] = $query->contains('categories', $category);
                if (count($subCategories) > 0) {
                    foreach ($subCategories as $subCategory) {
                        $subCategoryConstraint[] = $query->contains('categories', $subCategory);
                    }
                }
                if ($subCategoryConstraint) {
                    $categoryConstraints[] = $query->logicalOr($subCategoryConstraint);
                }
            } else {
                $categoryConstraints[] = $query->contains('categories', $category);
            }
        }

        if ($categoryConstraints) {
            switch (strtolower($conjunction)) {
                case 'or':
                    $constraint = $query->logicalOr($categoryConstraints);
                    break;
                case 'notor':
                    $constraint = $query->logicalNot($query->logicalOr($categoryConstraints));
                    break;
                case 'notand':
                    $constraint = $query->logicalNot($query->logicalAnd($categoryConstraints));
                    break;
                case 'and':
                default:
                    $constraint = $query->logicalAnd($categoryConstraints);
            }
        }

        return $constraint;
    }

    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @param QueryInterface $query
     * @param DemandInterface $demand
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Exception
     *
     * @return (\TYPO3\CMS\Extbase\Persistence\Generic\Qom\AndInterface|\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ComparisonInterface|\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface|\TYPO3\CMS\Extbase\Persistence\Generic\Qom\NotInterface|\TYPO3\CMS\Extbase\Persistence\Generic\Qom\OrInterface|null)[]
     *
     * @psalm-return array<string, \TYPO3\CMS\Extbase\Persistence\Generic\Qom\AndInterface|\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ComparisonInterface|\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface|\TYPO3\CMS\Extbase\Persistence\Generic\Qom\NotInterface|\TYPO3\CMS\Extbase\Persistence\Generic\Qom\OrInterface|null>
     */
    protected function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand): array
    {
        /** @var NewsDemand $demand */
        $constraints = [];

        if ($demand->getCategories() && $demand->getCategories() !== '0') {
            $constraints['categories'] = $this->createCategoryConstraint(
                $query,
                $demand->getCategories(),
                $demand->getCategoryConjunction(),
                $demand->getIncludeSubCategories()
            );
        }

        if ($demand->getAuthor()) {
            $constraints['author'] = $query->equals('author', $demand->getAuthor());
        }

        if ($demand->getTypes()) {
            $constraints['types'] = $query->in('type', $demand->getTypes());
        }

        // archived
        if ($demand->getArchiveRestriction() === 'archived') {
            $constraints['archived'] = $query->logicalAnd(
                $query->lessThan('archive', $GLOBALS['SIM_EXEC_TIME']),
                $query->greaterThan('archive', 0)
            );
        } elseif ($demand->getArchiveRestriction() === 'active') {
            $constraints['active'] = $query->logicalOr(
                $query->greaterThanOrEqual('archive', $GLOBALS['SIM_EXEC_TIME']),
                $query->equals('archive', 0)
            );
        }

        // Time restriction greater than or equal
        $timeRestrictionField = $demand->getDateField();
        $timeRestrictionField = (empty($timeRestrictionField)) ? 'datetime' : $timeRestrictionField;

        if ($demand->getTimeRestriction()) {
            $timeLimit = ConstraintHelper::getTimeRestrictionLow($demand->getTimeRestriction());

            $constraints['timeRestrictionGreater'] = $query->greaterThanOrEqual(
                $timeRestrictionField,
                $timeLimit
            );
        }

        // Time restriction less than or equal
        if ($demand->getTimeRestrictionHigh()) {
            $timeLimit = ConstraintHelper::getTimeRestrictionHigh($demand->getTimeRestrictionHigh());

            $constraints['timeRestrictionLess'] = $query->lessThanOrEqual(
                $timeRestrictionField,
                $timeLimit
            );
        }

        // top news
        if ($demand->getTopNewsRestriction() == 1) {
            $constraints['topNews1'] = $query->equals('istopnews', 1);
        } elseif ($demand->getTopNewsRestriction() == 2) {
            $constraints['topNews2'] = $query->equals('istopnews', 0);
        }

        // storage page
        if ($demand->getStoragePage()) {
            $pidList = GeneralUtility::intExplode(',', $demand->getStoragePage(), true);
            $constraints['pid'] = $query->in('pid', $pidList);
        }

        // month & year OR year only
        if ($demand->getYear() > 0) {
            if (null === $demand->getDateField()) {
                throw new \InvalidArgumentException('No Datefield is set, therefore no Datemenu is possible!');
            }
            if ($demand->getMonth() > 0) {
                if ($demand->getDay() > 0) {
                    $begin = mktime(0, 0, 0, $demand->getMonth(), $demand->getDay(), $demand->getYear());
                    $end = mktime(23, 59, 59, $demand->getMonth(), $demand->getDay(), $demand->getYear());
                } else {
                    $begin = mktime(0, 0, 0, $demand->getMonth(), 1, $demand->getYear());
                    $end = mktime(23, 59, 59, ($demand->getMonth() + 1), 0, $demand->getYear());
                }
            } else {
                $begin = mktime(0, 0, 0, 1, 1, $demand->getYear());
                $end = mktime(23, 59, 59, 12, 31, $demand->getYear());
            }
            $constraints['datetime'] = $query->logicalAnd([
                $query->greaterThanOrEqual($demand->getDateField(), $begin),
                $query->lessThanOrEqual($demand->getDateField(), $end)
            ]);
        }

        // Tags
        $tags = $demand->getTags();
        if ($tags && is_string($tags)) {
            $tagList = explode(',', $tags);

            $subConstraints = [];
            foreach ($tagList as $singleTag) {
                $subConstraints[] = $query->contains('tags', $singleTag);
            }
            if (count($subConstraints) > 0) {
                $constraints['tags'] = $query->logicalOr($subConstraints);
            }
        }

        // Search
        $searchConstraints = $this->getSearchConstraints($query, $demand);
        if (!empty($searchConstraints)) {
            $constraints['search'] = $query->logicalAnd($searchConstraints);
        }

        // Exclude already displayed
        if ($demand->getExcludeAlreadyDisplayedNews() && isset($GLOBALS['EXT']['news']['alreadyDisplayed']) && !empty($GLOBALS['EXT']['news']['alreadyDisplayed'])) {
            $constraints['excludeAlreadyDisplayedNews'] = $query->logicalNot(
                $query->in(
                    'uid',
                    $GLOBALS['EXT']['news']['alreadyDisplayed']
                )
            );
        }

        // Hide id list
        $hideIdList = $demand->getHideIdList();
        if ($hideIdList) {
            $constraints['hideIdInList'] = $query->logicalNot(
                $query->in(
                    'uid',
                    GeneralUtility::intExplode(',', $hideIdList, true)
                )
            );
        }

        // Id list
        $idList = $demand->getIdList();
        if ($idList) {
            $constraints['idList'] = $query->in('uid', GeneralUtility::intExplode(',', $idList, true));
        }

        // Clean not used constraints
        foreach ($constraints as $key => $value) {
            if (null === $value) {
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
        if ($demand->getTopNewsFirst()) {
            $orderings['istopnews'] = QueryInterface::ORDER_DESCENDING;
        }

        if (Validation::isValidOrdering($demand->getOrder(), $demand->getOrderByAllowed())) {
            $orderList = GeneralUtility::trimExplode(',', $demand->getOrder(), true);

            if (!empty($orderList)) {
                // go through every order statement
                foreach ($orderList as $orderItem) {
                    $orderSplit = GeneralUtility::trimExplode(' ', $orderItem, true);
                    $orderField = $orderSplit[0];
                    $ascDesc = $orderSplit[1] ?? '';
                    if ($ascDesc) {
                        $orderings[$orderField] = ((strtolower($ascDesc) === 'desc') ?
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

    /**
     * Find first news by import and source id
     *
     * @param string $importSource import source
     * @param int $importId import id
     * @param bool $asArray return result as array
     * @return \GeorgRinger\News\Domain\Model\News|array
     */
    public function findOneByImportSourceAndImportId($importSource, $importId, $asArray = false)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setRespectSysLanguage(false);
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        $result = $query->matching(
            $query->logicalAnd(
                $query->equals('importSource', $importSource),
                $query->equals('importId', $importId)
            )
        )->execute($asArray);
        if ($asArray) {
            if (isset($result[0])) {
                return $result[0];
            }
            return [];
        }
        return $result->getFirst();
    }

    /**
     * Override default findByUid function to enable also the option to turn of
     * the enableField setting
     *
     * @param int $uid id of record
     * @param bool $respectEnableFields if set to false, hidden records are shown
     * @return \GeorgRinger\News\Domain\Model\News|null
     */
    public function findByUid($uid, $respectEnableFields = true): ?\GeorgRinger\News\Domain\Model\News
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setRespectSysLanguage(false);

        if (!$respectEnableFields) {
            $query->getQuerySettings()->setIgnoreEnableFields(true);
            $query->getQuerySettings()->setLanguageOverlayMode(false);
        }

        return $query->matching(
            $query->logicalAnd(
                $query->equals('uid', $uid),
                $query->equals('deleted', 0)
            )
        )->execute()->getFirst();
    }

    /**
     * Get the count of news records by month/year and
     * returns the result compiled as array
     *
     * @param DemandInterface $demand
     * @return array
     */
    public function countByDate(DemandInterface $demand): array
    {
        $data = [];
        $sql = $this->findDemandedRaw($demand);

        // strip unwanted order by
        $sql = $this->stripOrderBy($sql);

        // Get the month/year into the result
        $field = $demand->getDateField();
        $field = empty($field) ? 'datetime' : $field;

        $sql = 'SELECT MONTH(FROM_UNIXTIME(0) + INTERVAL ' . $field . ' SECOND ) AS "_Month",' .
            ' YEAR(FROM_UNIXTIME(0) + INTERVAL ' . $field . ' SECOND) AS "_Year" ,' .
            ' count(MONTH(FROM_UNIXTIME(0) + INTERVAL ' . $field . ' SECOND )) as count_month,' .
            ' count(YEAR(FROM_UNIXTIME(0) + INTERVAL ' . $field . ' SECOND)) as count_year' .
            ' FROM tx_news_domain_model_news ' . substr($sql, strpos($sql, 'WHERE '));

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_news_domain_model_news');

        if (TYPO3_MODE === 'FE') {
            $sql .= $GLOBALS['TSFE']->sys_page->enableFields('tx_news_domain_model_news');
        } else {
            $expressionBuilder = $connection
                ->createQueryBuilder()
                ->expr();
            $sql .= BackendUtility::BEenableFields('tx_news_domain_model_news') .
                ' AND ' . $expressionBuilder->eq('deleted', 0);
        }

        // group by custom month/year fields
        $orderDirection = strtolower($demand->getOrder());
        if ($orderDirection !== 'desc' && $orderDirection !== 'asc') {
            $orderDirection = 'asc';
        }
        $sql .= ' GROUP BY _Month, _Year ORDER BY _Year ' . $orderDirection . ', _Month ' . $orderDirection;

        $res = $connection->query($sql);
        while ($row = $res->fetch()) {
            $month = strlen($row['_Month']) === 1 ? ('0' . $row['_Month']) : $row['_Month'];
            $data['single'][$row['_Year']][$month] = $row['count_month'];
        }

        // Add totals
        if (is_array($data['single'])) {
            foreach ($data['single'] as $year => $months) {
                $countOfYear = 0;
                foreach ($months as $month) {
                    $countOfYear += $month;
                }
                $data['total'][$year] = $countOfYear;
            }
        }

        return $data;
    }

    /**
     * Get the search constraints
     *
     * @param QueryInterface $query
     * @param DemandInterface $demand
     * @return array
     * @throws \UnexpectedValueException
     */
    protected function getSearchConstraints(QueryInterface $query, DemandInterface $demand): array
    {
        $constraints = [];
        if ($demand->getSearch() === null) {
            return $constraints;
        }

        /* @var $searchObject \GeorgRinger\News\Domain\Model\Dto\Search */
        $searchObject = $demand->getSearch();

        $searchSubject = $searchObject->getSubject();
        if (!empty($searchSubject)) {
            $queryBuilder = $this->getQueryBuilder('tx_news_domain_model_news');

            $searchFields = GeneralUtility::trimExplode(',', $searchObject->getFields(), true);
            $searchConstraints = [];

            if (count($searchFields) === 0) {
                throw new \UnexpectedValueException('No search fields defined', 1318497755);
            }
            $searchSubjectSplitted = str_getcsv($searchSubject, ' ');
            if ($searchObject->isSplitSubjectWords()) {
                foreach ($searchFields as $field) {
                    $subConstraints = [];
                    foreach ($searchSubjectSplitted as $searchSubjectSplittedPart) {
                        $searchSubjectSplittedPart = trim($searchSubjectSplittedPart);
                        if ($searchSubjectSplittedPart) {
                            $subConstraints[] = $query->like($field, '%' . $searchSubjectSplittedPart . '%');
                        }
                    }
                    $searchConstraints[] = $query->logicalAnd($subConstraints);
                }
                if (count($searchConstraints)) {
                    $constraints[] = $query->logicalOr($searchConstraints);
                }
            } else {
                if (!empty($searchSubject)) {
                    foreach ($searchFields as $field) {
                        $searchConstraints[] = $query->like($field, '%' . $searchSubject . '%');
                    }
                }
                if (count($searchConstraints)) {
                    $constraints[] = $query->logicalOr($searchConstraints);
                }
            }
        }

        $minimumDate = strtotime($searchObject->getMinimumDate());
        if ($minimumDate) {
            $field = $searchObject->getDateField();
            if (empty($field)) {
                throw new \UnexpectedValueException('No date field is defined', 1396348732);
            }
            $constraints[] = $query->greaterThanOrEqual($field, $minimumDate);
        }
        $maximumDate = strtotime($searchObject->getMaximumDate());
        if ($maximumDate) {
            $field = $searchObject->getDateField();
            if (empty($field)) {
                throw new \UnexpectedValueException('No date field is defined', 1396348733);
            }
            $maximumDate += 86400;
            $constraints[] = $query->lessThanOrEqual($field, $maximumDate);
        }

        return $constraints;
    }

    /**
     * @param string $table table name
     * @return QueryBuilder
     */
    protected function getQueryBuilder(string $table): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
    }

    /**
     * Return stripped order sql
     *
     * @param string $str
     * @return string
     */
    private function stripOrderBy(string $str): string
    {
        /** @noinspection NotOptimalRegularExpressionsInspection */
        return preg_replace('/(?:ORDER[[:space:]]*BY[[:space:]]*.*)+/i', '', trim($str));
    }
}
