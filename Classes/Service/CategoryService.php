<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Service;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service for category related stuff
 */
class CategoryService
{
    /**
     * Get child categories by calling recursive function
     * and using the caching framework to save some queries
     *
     * @param int|string $idList list of category ids to start
     * @param int $counter
     * @param string $additionalWhere additional where clause
     * @param bool $removeGivenIdListFromResult remove the given id list from result
     * @return string comma separated list of category ids
     */
    public static function getChildrenCategories(
        $idList,
        $counter = 0,
        $additionalWhere = '',
        $removeGivenIdListFromResult = false
    ): string {
        if ($additionalWhere !== '') {
            throw new \UnexpectedValueException('The argument $additionalWhere is not supported anymore', 1025758541);
        }
        $cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('news_category');
        $cacheIdentifier = sha1('children' . $idList);

        $entry = $cache->get($cacheIdentifier);
        if (!$entry) {
            $entry = self::getChildrenCategoriesRecursive((string)$idList, $counter);
            $cache->set($cacheIdentifier, $entry);
        }

        if ($removeGivenIdListFromResult) {
            $entry = self::removeValuesFromString((string)$entry, (string)$idList);
        }

        return $entry;
    }

    /**
     * Remove values of a comma separated list from another comma separated list
     *
     * @param string $result string comma separated list
     * @param $toBeRemoved string comma separated list
     */
    public static function removeValuesFromString($result, string $toBeRemoved): string
    {
        $resultAsArray = GeneralUtility::trimExplode(',', $result, true);
        $idListAsArray = GeneralUtility::trimExplode(',', $toBeRemoved, true);
        return implode(',', array_diff($resultAsArray, $idListAsArray));
    }

    /**
     * Get child categories
     *
     * @param string $idList list of category ids to start
     * @param int $counter
     * @return string comma separated list of category ids
     */
    private static function getChildrenCategoriesRecursive($idList, $counter = 0): string
    {
        $result = [];

        // add idlist to the output too
        if ($counter === 0) {
            $result[] = self::cleanIntList($idList);
        }

        $rootIdList = array_map(intval(...), explode(',', (string)$idList));
        $childrenByParent = self::fetchChildrenGroupedByParent($rootIdList, $counter);
        self::addChildrenOfParents($rootIdList, $childrenByParent, $result);

        return implode(',', $result);
    }

    /**
     * Fetch all descendants of the given categories, grouped by their parent.
     *
     * The categories are collected level by level, so the number of queries depends
     * on the depth of the category tree instead of on the number of categories.
     *
     * @param int[] $parentIdList
     * @return array<int, int[]> child uids, grouped by their parent uid
     */
    private static function fetchChildrenGroupedByParent(array $parentIdList, int $counter): array
    {
        $childrenByParent = [];
        // Categories which are their own ancestor would otherwise be looked up forever
        $seenIdList = array_fill_keys($parentIdList, true);

        while ($parentIdList !== []) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('sys_category');
            $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
            $rows = $queryBuilder
                ->select('uid', 'parent')
                ->from('sys_category')
                ->where(
                    $queryBuilder->expr()->in('parent', $queryBuilder->createNamedParameter($parentIdList, Connection::PARAM_INT_ARRAY))
                )
                // Deterministic order so the resulting id list is stable across DBMS
                ->orderBy('uid')
                ->executeQuery()
                ->fetchAllAssociative();

            $parentIdList = [];
            foreach ($rows as $row) {
                $uid = (int)$row['uid'];
                if (isset($seenIdList[$uid])) {
                    continue;
                }
                $counter++;
                if ($counter > 10000) {
                    GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('EXT:news: one or more recursive categories where found');
                    return $childrenByParent;
                }
                $seenIdList[$uid] = true;
                $childrenByParent[(int)$row['parent']][] = $uid;
                $parentIdList[] = $uid;
            }
        }

        return $childrenByParent;
    }

    /**
     * Append the children of the given categories to the result, depth first,
     * so that every category is directly followed by its own descendants.
     *
     * @param int[] $parentIdList
     * @param array<int, int[]> $childrenByParent
     * @param array<int, int|string> $result
     */
    private static function addChildrenOfParents(array $parentIdList, array $childrenByParent, array &$result): void
    {
        foreach ($parentIdList as $parentId) {
            foreach ($childrenByParent[$parentId] ?? [] as $uid) {
                $result[] = $uid;
                self::addChildrenOfParents([$uid], $childrenByParent, $result);
            }
        }
    }

    /**
     * Clean list of integers
     *
     * @param string $list
     */
    private static function cleanIntList($list): string
    {
        return implode(',', GeneralUtility::intExplode(',', $list));
    }
}
