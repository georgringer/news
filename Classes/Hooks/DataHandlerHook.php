<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Hooks;

use GeorgRinger\News\Service\AccessControlService;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into DataHandler which is used to show preview of news item
 */
class DataHandlerHook implements SingletonInterface
{
    protected $cacheTagsToFlush = [];

    /**
     * Flushes the cache if a news record was edited.
     * This happens on two levels: by UID and by PID.
     */
    public function clearCachePostProc(array $params): void
    {
        if (isset($params['table']) && $params['table'] === 'tx_news_domain_model_news') {
            if (isset($params['uid'])) {
                $this->cacheTagsToFlush[] = 'tx_news_uid_' . $params['uid'];
            }
            if (isset($params['uid_page'])) {
                $this->cacheTagsToFlush[] = 'tx_news_pid_' . $params['uid_page'];
            }

            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
            foreach (array_unique($this->cacheTagsToFlush) as $cacheTag) {
                $cacheManager->flushCachesInGroupByTag('pages', $cacheTag);
            }
            $this->cacheTagsToFlush = [];
        }
    }

    /**
     * Generate a different preview link     *
     *
     * @param string $status status
     * @param string $table table name
     * @param int $recordUid id of the record
     * @param array $fields fieldArray
     * @param DataHandler $parentObject parent Object
     */
    public function processDatamap_afterDatabaseOperations(
        $status,
        $table,
        $recordUid,
        array $fields,
        DataHandler $parentObject
    ): void {
        // Clear category cache
        if ($table === 'sys_category') {
            $cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('news_category');
            $cache->flush();
        }
    }

    /**
     * Prevent saving of a news record if the editor doesn't have access to all categories of the news record
     *
     * @param string $table
     * @param int $id
     * @param $parentObject DataHandler
     */
    public function processDatamap_preProcessFieldArray(array &$fieldArray, $table, $id, $parentObject): void
    {
        if ($table === 'tx_news_domain_model_news') {
            // check permissions of assigned categories
            if (is_int($id) && !$this->getBackendUser()->isAdmin()) {
                $newsRecord = BackendUtilityCore::getRecord($table, $id);
                if (!AccessControlService::userHasCategoryPermissionsForRecord($newsRecord)) {
                    $parentObject->log(
                        $table,
                        $id,
                        2,
                        0,
                        1,
                        "processDatamap: Attempt to modify a record from table '%s' without permission. Reason: the record has one or more categories assigned that are not defined in your BE usergroup.",
                        1,
                        [$table]
                    );
                    // unset fieldArray to prevent saving of the record
                    $fieldArray = [];
                } else {
                    // If the category relation has been modified, no | is found anymore
                    if (isset($fieldArray['categories']) && !str_contains($fieldArray['categories'], '|')) {
                        $deniedCategories = AccessControlService::getAccessDeniedCategories($newsRecord);
                        foreach ($deniedCategories as $deniedCategory) {
                            $fieldArray['categories'] .= ',' . $deniedCategory['uid'];
                        }
                        // Check if the categories are not empty,
                        if (!empty($fieldArray['categories'])) {
                            $fieldArray['categories'] = trim($fieldArray['categories'], ',');
                        }
                    }
                }
            }
        }
    }

    /**
     * Prevent deleting/moving of a news record if the editor doesn't have access to all categories of the news record
     *
     * @param string $table
     * @param int $id
     * @param string $value
     * @param $parentObject DataHandler
     */
    public function processCmdmap_preProcess(string $command, &$table, $id, $value, $parentObject): void
    {
        if ($table === 'tx_news_domain_model_news' && !$this->getBackendUser()->isAdmin() && is_int($id) && $command !== 'undelete') {
            $newsRecord = BackendUtilityCore::getRecord($table, $id);
            if (is_array($newsRecord) && !AccessControlService::userHasCategoryPermissionsForRecord($newsRecord)) {
                $parentObject->log(
                    $table,
                    $id,
                    2,
                    0,
                    1,
                    'processCmdmap: Attempt to ' . $command . " a record from table '%s' without permission. Reason: the record has one or more categories assigned that are not defined in the BE usergroup.",
                    1,
                    [$table]
                );
                // unset table to prevent saving
                $table = '';
            }
        }
    }

    /**
     * Flush cache for old news pid when moving record
     *
     * @param string $table
     * @param int $uid
     * @param int $destPid
     * @param array $propArr
     * @param int $resolvedPid
     * @param bool $recordWasMoved
     */
    public function moveRecord($table, $uid, $destPid, $propArr, array $moveRec, $resolvedPid, $recordWasMoved, DataHandler $dataHandler): void
    {
        if ($table === 'tx_news_domain_model_news') {
            $this->cacheTagsToFlush[] = 'tx_news_pid_' . $moveRec['pid'];
        }
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
