<?php

namespace GeorgRinger\News\Service;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
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
     * @param string $idList list of category ids to start
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
            throw new \UnexpectedValueException('The argument $additionalWhere is not supported anymore');
        }
        $cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('news_category');
        $cacheIdentifier = sha1('children' . $idList);

        $entry = $cache->get($cacheIdentifier);
        if (!$entry) {
            $entry = self::getChildrenCategoriesRecursive($idList, $counter, $additionalWhere);
            $cache->set($cacheIdentifier, $entry);
        }

        if ($removeGivenIdListFromResult) {
            $entry = self::removeValuesFromString($entry, $idList);
        }

        return $entry;
    }

    /**
     * Remove values of a comma separated list from another comma separated list
     *
     * @param string $result string comma separated list
     * @param $toBeRemoved string comma separated list
     * @return string
     */
    public static function removeValuesFromString($result, string $toBeRemoved): string
    {
        $resultAsArray = GeneralUtility::trimExplode(',', $result, true);
        $idListAsArray = GeneralUtility::trimExplode(',', $toBeRemoved, true);

        $result = implode(',', array_diff($resultAsArray, $idListAsArray));
        return $result;
    }

    /**
     * Get child categories
     *
     * @param string $idList list of category ids to start
     * @param int $counter
     * @param string $additionalWhere additional where clause
     * @return string comma separated list of category ids
     */
    private static function getChildrenCategoriesRecursive($idList, $counter = 0, $additionalWhere = ''): string
    {
        $result = [];

        // add idlist to the output too
        if ($counter === 0) {
            $result[] = self::cleanIntList($idList);
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        $res = $queryBuilder
            ->select('uid')
            ->from('sys_category')
            ->where(
                $queryBuilder->expr()->in('parent', $queryBuilder->createNamedParameter(array_map('intval', explode(',', $idList)), Connection::PARAM_INT_ARRAY))
            )
            ->execute();

        while (($row = $res->fetch())) {
            $counter++;
            if ($counter > 10000) {
                GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('EXT:news: one or more recursive categories where found');
                return implode(',', $result);
            }
            $subcategories = self::getChildrenCategoriesRecursive($row['uid'], $counter);
            $result[] = $row['uid'] . ($subcategories ? ',' . $subcategories : '');
        }

        $result = implode(',', $result);
        return $result;
    }

    /**
     * Translate a category record in the backend
     *
     * @param string $default default label
     * @param array $row category record
     * @return string
     * @throws \UnexpectedValueException
     */
    public static function translateCategoryRecord($default, array $row = []): string
    {
        if (TYPO3_MODE !== 'BE') {
            throw new \UnexpectedValueException('TYPO3 Mode must be BE');
        }

        $overlayLanguage = (int)($GLOBALS['BE_USER']->uc['newsoverlay'] ?? 0);

        $title = '';

        if ($row['uid'] > 0 && $overlayLanguage > 0 && !isset($row['sys_language_uid'])) {
            $row = BackendUtility::getRecord('sys_category', $row['uid']);
        }

        if ($row['uid'] > 0 && $overlayLanguage > 0 && $row['sys_language_uid'] === 0) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('sys_category');
            $overlayRecord = $queryBuilder
                ->select('title')
                ->from('sys_category')
                ->where(
                    $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($overlayLanguage, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter($row['uid'], \PDO::PARAM_INT))
                )
                ->setMaxResults(1)
                ->execute()->fetch();

            if (is_array($overlayRecord) && !empty($overlayRecord)) {
                $title = $overlayRecord['title'] . ' (' . $row['title'] . ')';
            }
        }

        $title = $title ?: $default ?: '';

        return $title;
    }

    /**
     * Clean list of integers
     *
     * @param string $list
     * @return string
     */
    private static function cleanIntList($list): string
    {
        return implode(',', GeneralUtility::intExplode(',', $list));
    }
}
