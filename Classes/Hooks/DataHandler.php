<?php

namespace GeorgRinger\News\Hooks;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Service\AccessControlService;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into tcemain which is used to show preview of news item
 */
class DataHandler implements SingletonInterface
{
    protected $cacheTagsToFlush = [];

    /**
     * Flushes the cache if a news record was edited.
     * This happens on two levels: by UID and by PID.
     *
     * @param array $params
     *
     * @return void
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
        }
    }

    /**
     * Generate a different preview link     *
     *
     * @param string $status status
     * @param string $table table name
     * @param int $recordUid id of the record
     * @param array $fields fieldArray
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject parent Object
     *
     * @return void
     */
    public function processDatamap_afterDatabaseOperations(
        $status,
        $table,
        $recordUid,
        array $fields,
        \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject
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
     * @param array $fieldArray
     * @param string $table
     * @param int $id
     * @param $parentObject \TYPO3\CMS\Core\DataHandling\DataHandler
     *
     * @return void
     */
    public function processDatamap_preProcessFieldArray(&$fieldArray, $table, $id, $parentObject): void
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
                    if (isset($fieldArray['categories']) && strpos($fieldArray['categories'], '|') === false) {
                        $deniedCategories = AccessControlService::getAccessDeniedCategories($newsRecord);
                        if (is_array($deniedCategories)) {
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
    }

    /**
     * Prevent deleting/moving of a news record if the editor doesn't have access to all categories of the news record
     *
     * @param string $command
     * @param string $table
     * @param int $id
     * @param string $value
     * @param $parentObject \TYPO3\CMS\Core\DataHandling\DataHandler
     *
     * @return void
     */
    public function processCmdmap_preProcess($command, &$table, $id, $value, $parentObject): void
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
     * @param array $moveRec
     * @param int $resolvedPid
     * @param bool $recordWasMoved
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
     */
    public function moveRecord($table, $uid, $destPid, $propArr, $moveRec, $resolvedPid, $recordWasMoved, \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler)
    {
        if ($table === 'tx_news_domain_model_news') {
            $this->cacheTagsToFlush[] = 'tx_news_pid_' . $moveRec['pid'];
        }
    }

    /**
     * Returns the current BE user.
     *
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUser(): \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
