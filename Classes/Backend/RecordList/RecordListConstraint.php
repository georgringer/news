<?php

namespace GeorgRinger\News\Backend\RecordList;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Service\CategoryService;
use GeorgRinger\News\Utility\ConstraintHelper;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\EndTimeRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\StartTimeRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class for the list rendering of administration module
 */
class RecordListConstraint
{
    const TABLE = 'tx_news_domain_model_news';

    /**
     * Check if current module is the news administration module
     *
     * @return bool
     */
    public function isInAdministrationModule(): bool
    {
        $vars = GeneralUtility::_GET('route');
        return strpos($vars, '/module/web/NewsAdministration') !== false;
    }

    public function extendQuery(array &$parameters, array $arguments): void
    {
        $parameters['whereDoctrine'] = [];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');

        $expressionBuilder = $queryBuilder->expr();

        // search word
        if (isset($arguments['searchWord']) && !empty($arguments['searchWord'])) {
            $words = GeneralUtility::trimExplode(' ', $arguments['searchWord'], true);
            $fields = ['title', 'teaser', 'bodytext'];
            $fieldParts = [];
            foreach ($fields as $field) {
                $likeParts = [];
                $nameParts = str_getcsv($arguments['searchWord'], ' ');
                foreach ($nameParts as $part) {
                    $part = trim($part);
                    if ($part !== '') {
                        $likeParts[] = $expressionBuilder->like($field, $queryBuilder->quote('%' . $queryBuilder->escapeLikeWildcards($part) . '%'));
                    }
                }
                if (!empty($likeParts)) {
                    $fieldParts[] = $expressionBuilder->orX(...$likeParts);
                }
            }
            $parameters['whereDoctrine'][] = $expressionBuilder->orX(...$fieldParts);
            $parameters['where'][] = $expressionBuilder->orX(...$fieldParts);
        }
        // top news
        $topNewsSetting = (int)$arguments['topNewsRestriction'];
        if ($topNewsSetting > 0) {
            if ($topNewsSetting === 1) {
                $parameters['where'][] = 'istopnews=1';
                $parameters['whereDoctrine'][] = $expressionBuilder->eq('istopnews', 1);
            } elseif ($topNewsSetting === 2) {
                $parameters['where'][] = 'istopnews=0';
                $parameters['whereDoctrine'][] = $expressionBuilder->eq('istopnews', 0);
            }
        }

        // archived (1==active, 2==archived)
        $archived = (int)$arguments['archived'];
        if ($archived > 0) {
            $currentTime = $GLOBALS['EXEC_TIME'];
            if ($archived === 1) {
                $parameters['where'][] = '(archive > ' . $currentTime . ' OR archive=0)';
                $parameters['whereDoctrine'][] = $expressionBuilder->orX(
                    $expressionBuilder->gt('archive', $currentTime),
                    $expressionBuilder->eq('archive', 0)
                );
            } elseif ($archived === 2) {
                $parameters['where'][] = 'archive > 0 AND archive <' . $currentTime;
                $parameters['whereDoctrine'][] = $expressionBuilder->andX(
                    $expressionBuilder->gt('archive', 0),
                    $expressionBuilder->lt('archive', $currentTime)
                );
            }
        }

        // hidden
        $hidden = (int)$arguments['hidden'];
        if ($hidden > 0) {
            if ($hidden === 1) {
                $parameters['where'][] = 'hidden=1';
                $parameters['whereDoctrine'][] = $expressionBuilder->eq('hidden', 1);
            } elseif ($hidden === 2) {
                $parameters['where'][] = 'hidden=0';
                $parameters['whereDoctrine'][] = $expressionBuilder->eq('hidden', 0);
            }
        }

        // time constraint low
        if (isset($arguments['timeRestriction']) && !empty($arguments['timeRestriction'])) {
            try {
                $limit = ConstraintHelper::getTimeRestrictionLow($arguments['timeRestriction']);
                $parameters['where'][] = 'datetime >=' . $limit;
                $parameters['whereDoctrine'][] = $expressionBuilder->gte('datetime', $limit);
            } catch (\Exception $e) {
                // @todo add flash message
            }
        }

        // time constraint high
        if (isset($arguments['timeRestrictionHigh']) && !empty($arguments['timeRestrictionHigh'])) {
            try {
                $limit = ConstraintHelper::getTimeRestrictionHigh($arguments['timeRestrictionHigh']);
                $parameters['where'][] = 'datetime <=' . $limit;
                $parameters['whereDoctrine'][] = $expressionBuilder->lte('datetime', $limit);
            } catch (\Exception $e) {
                // @todo add flash message
            }
        }

        // categories
        if (isset($arguments['selectedCategories']) && is_array($arguments['selectedCategories'])) {
            $categoryMode = strtolower($arguments['categoryConjunction']);
            foreach ($arguments['selectedCategories'] as $key => $category) {
                if ((int)$category === 0) {
                    unset($arguments['selectedCategories'][$key]);
                }
            }
            if (!empty($arguments['selectedCategories'])) {
                if ((int)$arguments['includeSubCategories'] === 1) {
                    $categoryList = implode(',', $arguments['selectedCategories']);
                    $listWithSubCategories = CategoryService::getChildrenCategories($categoryList);
                    $arguments['selectedCategories'] = explode(',', $listWithSubCategories);
                }
                switch ($categoryMode) {
                    case 'and':
                        foreach ($arguments['selectedCategories'] as $category) {
                            $idList = $this->getNewsIdsOfCategory($category);
                            if (empty($idList)) {
                                $parameters['where'][] = '1=2';
                                $parameters['whereDoctrine'][] = $expressionBuilder->eq('uid', 0);
                            } else {
                                $parameters['where'][] = sprintf('uid IN(%s)', implode(',', $idList));
                                $parameters['whereDoctrine'][] = $expressionBuilder->in('uid', $idList);
                            }
                        }
                        break;
                    case 'or':
                        $orConstraint = $orConstraintDoctrine = [];
                        foreach ($arguments['selectedCategories'] as $category) {
                            $idList = $this->getNewsIdsOfCategory($category);
                            if (!empty($idList)) {
                                $orConstraint[] = sprintf('uid IN(%s)', implode(',', $idList));
                                $orConstraintDoctrine[] = $expressionBuilder->in('uid', $idList);
                            }
                        }
                        if (empty($orConstraint)) {
                            $parameters['where'][] = '1=2';
                            $parameters['whereDoctrine'][] = $expressionBuilder->eq('uid', 0);
                        } else {
                            $parameters['where'][] = implode(' OR ', $orConstraint);
                            $parameters['whereDoctrine'][] = $expressionBuilder->orX(...$orConstraintDoctrine);
                        }
                        break;
                    // @todo test that
                    case 'notor':
                        $orConstraint = $orConstraintDoctrine = [];
                        foreach ($arguments['selectedCategories'] as $category) {
                            $idList = $this->getNewsIdsOfCategory($category);
                            if (!empty($idList)) {
                                $orConstraint[] = sprintf('(uid IN (%s))', implode(',', $idList));
                                $orConstraintDoctrine[] = $expressionBuilder->notIn('uid', $idList);
                            } else {
                                $orConstraint[] = '1=2';
                                $parameters['whereDoctrine'][] = $expressionBuilder->eq('uid', 0);
                            }
                        }
                        if (empty($orConstraint)) {
                            $parameters['where'][] = '1=2';
                            $parameters['whereDoctrine'][] = $expressionBuilder->eq('uid', 0);
                        } else {
                            $orConstraint = array_unique($orConstraint);
                            $parameters['where'][] = ' NOT (' . implode(' OR ', $orConstraint) . ')';
                            $parameters['whereDoctrine'][] = $expressionBuilder->andX(...$orConstraintDoctrine);
                        }
                        break;
                    case 'notand':
                        foreach ($arguments['selectedCategories'] as $category) {
                            $idList = $this->getNewsIdsOfCategory($category);
                            if (!empty($idList)) {
                                $parameters['where'][] = sprintf('uid NOT IN(%s)', implode(',', $idList));
                                $parameters['whereDoctrine'][] = $expressionBuilder->notIn('uid', $idList);
                            }
                        }
                        break;
                }
            }
        }

        // order
        if (isset($arguments['sortingField']) && isset($GLOBALS['TCA']['tx_news_domain_model_news']['columns'][$arguments['sortingField']])) {
            $direction = ($arguments['sortingDirection'] === 'asc' || $arguments['sortingDirection'] === 'desc') ? $arguments['sortingDirection'] : '';
            $parameters['orderBy'] = [[$arguments['sortingField'], $direction]];
        }
    }

    /**
     * @param int $categoryId
     * @return array
     */
    protected function getNewsIdsOfCategory($categoryId): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');
        $queryBuilder->getRestrictions()
            ->removeByType(StartTimeRestriction::class)
            ->removeByType(HiddenRestriction::class)
            ->removeByType(EndTimeRestriction::class);
        $res = $queryBuilder
            ->select('tx_news_domain_model_news.uid', 'sys_category.title')
            ->from('tx_news_domain_model_news')
            ->rightJoin(
                'tx_news_domain_model_news',
                'sys_category_record_mm',
                'sys_category_record_mm',
                $queryBuilder->expr()->eq('tx_news_domain_model_news.uid', $queryBuilder->quoteIdentifier('sys_category_record_mm.uid_foreign'))
            )
            ->rightJoin(
                'sys_category_record_mm',
                'sys_category',
                'sys_category',
                $queryBuilder->expr()->eq('sys_category.uid', $queryBuilder->quoteIdentifier('sys_category_record_mm.uid_local'))
            )
            ->where(
                $queryBuilder->expr()->eq('sys_category_record_mm.tablenames', $queryBuilder->createNamedParameter('tx_news_domain_model_news', \PDO::PARAM_STR)),
                $queryBuilder->expr()->isNotNull('tx_news_domain_model_news.uid'),
                $queryBuilder->expr()->eq('sys_category.uid', $queryBuilder->createNamedParameter($categoryId, \PDO::PARAM_INT))
            )
            ->execute();

        $idList = [];
        while ($row = $res->fetch()) {
            $idList[] = $row['uid'];
        }

        return $idList;
    }
}
