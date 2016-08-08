<?php

namespace GeorgRinger\News\Service;

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
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service for category related stuff
 *
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
    ) {
        $cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_news_category');
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
    public static function removeValuesFromString($result, $toBeRemoved)
    {
        $resultAsArray = GeneralUtility::trimExplode(',', $result, true);
        $idListAsArray = GeneralUtility::trimExplode(',', $toBeRemoved, true);

        $result = implode(',', array_diff($resultAsArray, $idListAsArray));
        return $result;
    }

    /**
     * Get rootline up by calling recursive function
     * and using the caching framework to save some queries
     *
     * @param int $id category id to start
     * @param string $additionalWhere additional where clause
     * @return string comma separated list of category ids
     */
    public static function getRootline($id, $additionalWhere = '')
    {
        $cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_news_category');
        $cacheIdentifier = sha1('rootline' . $id);

        $entry = $cache->get($cacheIdentifier);
        if (!$entry) {
            $entry = self::getRootlineRecursive($id, $additionalWhere);
            $cache->set($cacheIdentifier, $entry);
        }
        return $entry;
    }

    /**
     * Get child categories
     *
     * @param string $idList list of category ids to start
     * @param int $counter
     * @param string $additionalWhere additional where clause
     * @return string comma separated list of category ids
     */
    private static function getChildrenCategoriesRecursive($idList, $counter = 0, $additionalWhere = '')
    {
        $result = [];

        // add idlist to the output too
        if ($counter === 0) {
            $result[] = $GLOBALS['TYPO3_DB']->cleanIntList($idList);
        }

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid',
            'sys_category',
            'sys_category.parent IN (' . $GLOBALS['TYPO3_DB']->cleanIntList($idList) . ')
				AND deleted=0 ' . $additionalWhere);

        while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
            $counter++;
            if ($counter > 10000) {
                $GLOBALS['TT']->setTSlogMessage('EXT:news: one or more recursive categories where found');
                return implode(',', $result);
            }
            $subcategories = self::getChildrenCategoriesRecursive($row['uid'], $counter, $additionalWhere);
            $result[] = $row['uid'] . ($subcategories ? ',' . $subcategories : '');
        }
        $GLOBALS['TYPO3_DB']->sql_free_result($res);

        $result = implode(',', $result);
        return $result;
    }

    /**
     * Get rootline categories
     *
     * @param int $id category id to start
     * @param int $counter counter
     * @param string $additionalWhere additional where clause
     * @return string comma separated list of category ids
     */
    public static function getRootlineRecursive($id, $counter = 0, $additionalWhere = '')
    {
        $id = (int)$id;
        $result = [];

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid,parent',
            'sys_category',
            'uid=' . $id . ' AND deleted=0 ' . $additionalWhere);

        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        if ($id === 0 || !$row || $counter > 10000) {
            $GLOBALS['TT']->setTSlogMessage('EXT:news: one or more recursive categories where found');
            return $id;
        }

        $parent = self::getRootlineRecursive($row['parent'], $counter, $additionalWhere);
        $result[] = $row['parent'];
        if ($parent > 0) {
            $result[] = $parent;
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
    public static function translateCategoryRecord($default, array $row = [])
    {
        if (TYPO3_MODE != 'BE') {
            throw new \UnexpectedValueException('TYPO3 Mode must be BE');
        }

        $overlayLanguage = (int)$GLOBALS['BE_USER']->uc['newsoverlay'];

        $title = '';

        if ($row['uid'] > 0 && $overlayLanguage > 0 && $row['sys_language_uid'] == 0) {
            $overlayRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
                '*',
                'sys_category',
                'deleted=0 AND sys_language_uid=' . $overlayLanguage . ' AND l10n_parent=' . $row['uid']
            );
            if (isset($overlayRecord[0]['title'])) {
                $title = $overlayRecord[0]['title'] . ' (' . $row['title'] . ')';
            }
        }

        $title = ($title ? $title : $default);

        return $title;
    }
}
