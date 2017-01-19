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
use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Service\CategoryService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Category repository with all callable functionality
 *
 */
class CategoryRepository extends \GeorgRinger\News\Domain\Repository\AbstractDemandedRepository
{

    protected function createConstraintsFromDemand(
        QueryInterface $query,
        DemandInterface $demand
    ) {
    }

    protected function createOrderingsFromDemand(DemandInterface $demand)
    {
    }

    /**
     * Find category by import source and import id
     *
     * @param string $importSource import source
     * @param int $importId import id
     * @return Category
     */
    public function findOneByImportSourceAndImportId($importSource, $importId)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        return $query->matching(
            $query->logicalAnd(
                $query->equals('importSource', $importSource),
                $query->equals('importId', $importId)
            ))->execute()->getFirst();
    }

    /**
     * Find categories by a given pid
     *
     * @param int $pid pid
     * @return QueryInterface
     */
    public function findParentCategoriesByPid($pid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        return $query->matching(
            $query->logicalAnd(
                $query->equals('pid', (int)$pid),
                $query->equals('parentcategory', 0)
            ))->execute();
    }

    /**
     * Find category tree
     *
     * @param array $rootIdList list of id s
     * @return QueryInterface|array
     */
    public function findTree(array $rootIdList, $startingPoint = null)
    {
        $subCategories = CategoryService::getChildrenCategories(implode(',', $rootIdList));

        $idList = explode(',', $subCategories);
        if (empty($idList)) {
            return [];
        }

        $ordering = ['sorting' => QueryInterface::ORDER_ASCENDING];
        $categories = $this->findByIdList($idList, $ordering, $startingPoint);
        $flatCategories = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            $flatCategories[$category->getUid()] = [
                'item' => $category,
                'parent' => ($category->getParentcategory()) ? $category->getParentcategory()->getUid() : null
            ];
        }

        $tree = [];

        // If leaves are selected without its parents selected, those are shown as parent
        foreach ($flatCategories as $id => &$flatCategory) {
            if (!isset($flatCategories[$flatCategory['parent']])) {
                $flatCategory['parent'] = null;
            }
        }

        foreach ($flatCategories as $id => &$node) {
            if ($node['parent'] === null) {
                $tree[$id] = &$node;
            } else {
                $flatCategories[$node['parent']]['children'][$id] = &$node;
            }
        }

        return $tree;
    }

    /**
     * Find categories by a given pid
     *
     * @param array $idList list of id s
     * @param array $ordering ordering
     * @return QueryInterface
     */
    public function findByIdList(array $idList, array $ordering = [], $startingPoint = null)
    {
        if (empty($idList)) {
            throw new \InvalidArgumentException('The given id list is empty.', 1484823597);
        }
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setRespectSysLanguage(false);

        if (count($ordering) > 0) {
            $query->setOrderings($ordering);
        }
        $this->overlayTranslatedCategoryIds($idList);

        $conditions = [];
        $conditions[] = $query->in('uid', $idList);

        if (is_null($startingPoint) === false) {
            $conditions[] = $query->in('pid', GeneralUtility::trimExplode(',', $startingPoint, true));
        }

        return $query->matching(
            $query->logicalAnd(
                $conditions
            ))->execute();
    }

    /**
     * Find categories by a given parent
     *
     * @param int $parent parent
     * @return QueryInterface
     */
    public function findChildren($parent)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        return $query->matching(
            $query->logicalAnd(
                $query->equals('parentcategory', (int)$parent)
            ))->execute();
    }

    /**
     * Overlay the category ids with the ones from current language
     *
     * @param array $idList
     * return void
     */
    protected function overlayTranslatedCategoryIds(array &$idList)
    {
        $language = $this->getSysLanguageUid();
        if ($language > 0 && !empty($idList)) {
            if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
                $whereClause = 'sys_language_uid=' . $language . ' AND l10n_parent IN(' . implode(',',
                        $idList) . ')' . $GLOBALS['TSFE']->sys_page->enableFields('sys_category');
                $rows = (array)$GLOBALS['TYPO3_DB']->exec_SELECTgetRows('l10n_parent, uid,sys_language_uid', 'sys_category',
                    $whereClause);

                $idList = $this->replaceCategoryIds($idList, $rows);
            }
            // @todo currently only implemented for the frontend
        }
    }

    /**
     * Get the current sys language uid
     *
     * @return int
     */
    protected function getSysLanguageUid()
    {
        $sysLanguage = 0;
        if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            $sysLanguage = $GLOBALS['TSFE']->sys_language_content;
        } elseif (intval(\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('L'))) {
            $sysLanguage = intval(\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('L'));
        }

        return $sysLanguage;
    }

    /**
     * Replace ids in array by the given ones
     *
     * @param array $idList
     * @param array $rows
     * @return array
     */
    protected function replaceCategoryIds(array $idList, array $rows)
    {
        foreach ($rows as $row) {
            $pos = array_search($row['l10n_parent'], $idList);
            if ($pos !== false) {
                $idList[$pos] = (int)$row['uid'];
            }
        }

        return $idList;
    }
}
