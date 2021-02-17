<?php

namespace GeorgRinger\News\Service;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\EndTimeRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\StartTimeRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service for access control related stuff
 */
class AccessControlService
{

    /**
     * Check if a user has access to all categories of a news record
     *
     * @param array $newsRecord
     * @return bool
     */
    public static function userHasCategoryPermissionsForRecord(array $newsRecord): bool
    {
        $settings = GeneralUtility::makeInstance(EmConfiguration::class);
        if (!$settings->getCategoryBeGroupTceFormsRestriction()) {
            return true;
        }

        if (self::getBackendUser()->isAdmin()) {
            // an admin may edit all news
            return true;
        }

        // If there are any categories with denied access, the user has no permission
        if (count(self::getAccessDeniedCategories($newsRecord))) {
            return false;
        }

        return true;
    }

    /**
     * Get an array with the uid and title of all categories the user doesn't have access to
     *
     * @param array $newsRecord
     * @return array
     */
    public static function getAccessDeniedCategories(array $newsRecord): array
    {
        if (self::getBackendUser()->isAdmin()) {
            // an admin may edit all news so no categories without access
            return [];
        }

        // no category mounts set means access to all
        $backendUserCategories = self::getBackendUser()->getCategoryMountPoints();
        if ($backendUserCategories === []) {
            return [];
        }

        $catService = GeneralUtility::makeInstance(CategoryService::class);
        $subCategories = $catService::getChildrenCategories(implode(',', $backendUserCategories));
        if (!empty($subCategories)) {
            $backendUserCategories = explode(',', $subCategories);
        }

        $newsRecordCategories = self::getCategoriesForNewsRecord($newsRecord);

        // Remove categories the user has access to
        foreach ($newsRecordCategories as $key => $newsRecordCategory) {
            if (in_array($newsRecordCategory['uid'], $backendUserCategories)) {
                unset($newsRecordCategories[$key]);
            }
        }

        return $newsRecordCategories;
    }

    /**
     * Get all categories for a news record respecting l10n_mode
     *
     * @param array $newsRecord
     * @return array
     */
    public static function getCategoriesForNewsRecord($newsRecord): array
    {
        // determine localization overlay mode to select categories either from parent or localized record
        if ($newsRecord['sys_language_uid'] > 0 && $newsRecord['l10n_parent'] > 0) {
            // localized version of a news record
            $categoryL10nMode = $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['categories']['l10n_mode'];
            if ($categoryL10nMode === 'mergeIfNotBlank') {
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('sys_category_record_mm');
                $newsRecordCategoriesCount = $queryBuilder->count('*')
                    ->from('sys_category_record_mm')
                    ->where(
                        $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($newsRecord['uid'], \PDO::PARAM_INT)),
                        $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter('tx_news_domain_model_news', \PDO::PARAM_STR)),
                        $queryBuilder->expr()->eq('fieldname', $queryBuilder->createNamedParameter('categories', \PDO::PARAM_STR))
                    )
                    ->execute()
                    ->fetchColumn(0);
                if ($newsRecordCategoriesCount > 0) {
                    // take categories from localized version
                    $newsRecordUid = $newsRecord['uid'];
                } else {
                    // inherit categories from parent
                    $newsRecordUid = $newsRecord['l10n_parent'];
                }
            } elseif ($categoryL10nMode === 'exclude') {
                // exclude: The localized version inherits the categories of the parent
                $newsRecordUid = $newsRecord['l10n_parent'];
            } else {
                // noCopy/prefixLangTitle: no inheritance
                $newsRecordUid = $newsRecord['uid'];
            }
        } else {
            $newsRecordUid = $newsRecord['uid'];
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        $queryBuilder->getRestrictions()
            ->removeByType(StartTimeRestriction::class)
            ->removeByType(HiddenRestriction::class)
            ->removeByType(EndTimeRestriction::class);
        $res = $queryBuilder
            ->select('sys_category_record_mm.uid_local', 'sys_category.title')
            ->from('sys_category')
            ->leftJoin(
                'sys_category',
                'sys_category_record_mm',
                'sys_category_record_mm',
                $queryBuilder->expr()->eq('sys_category_record_mm.uid_local', $queryBuilder->quoteIdentifier('sys_category.uid'))
            )
            ->where(
                $queryBuilder->expr()->eq('sys_category_record_mm.tablenames', $queryBuilder->createNamedParameter('tx_news_domain_model_news', \PDO::PARAM_STR)),
                $queryBuilder->expr()->eq('sys_category_record_mm.fieldname', $queryBuilder->createNamedParameter('categories', \PDO::PARAM_STR)),
                $queryBuilder->expr()->eq('sys_category_record_mm.uid_foreign', $queryBuilder->createNamedParameter($newsRecordUid, \PDO::PARAM_INT))
            )
            ->execute();

        $categories = [];
        while ($row =$res->fetch()) {
            $categories[] = [
                'uid' => $row['uid_local'],
                'title' => $row['title']
            ];
        }
        return $categories;
    }

    /**
     * Returns the current BE user.
     *
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected static function getBackendUser(): \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
