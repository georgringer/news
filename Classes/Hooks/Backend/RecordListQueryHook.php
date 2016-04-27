<?php

namespace GeorgRinger\News\Hooks\Backend;

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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;
use TYPO3\CMS\Recordlist\RecordList\AbstractDatabaseRecordList;

/**
 * Hook into AbstractDatabaseRecordList to hide tt_content elements in list view
 *
 */
class RecordListQueryHook
{

    /**
     * @param array $queryParts
     * @param AbstractDatabaseRecordList $recordList
     * @param string $table
     */
    public function makeQueryArray_post(array &$queryParts, AbstractDatabaseRecordList $recordList, $table)
    {
        if ($table === 'tt_content' && (int)$recordList->searchLevels === 0 && $recordList->id > 0) {
            $pageRecord = BackendUtility::getRecord('pages', $recordList->id, 'uid', ' AND doktype="254" AND module="news"');
            if (is_array($pageRecord)) {
                $tsConfig = BackendUtility::getPagesTSconfig($recordList->id);
                if (isset($tsConfig['tx_news.']) && is_array($tsConfig['tx_news.']) && $tsConfig['tx_news.']['showContentElementsInNewsSysFolder'] == 1) {
                    return;
                }
                $queryParts['WHERE'] = '1=2';
                $message = GeneralUtility::makeInstance(
                    FlashMessage::class,
                    $this->getLanguageService()->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:hiddenContentElements.description'),
                    '',
                    FlashMessage::INFO
                );
                /** @var $flashMessageService \TYPO3\CMS\Core\Messaging\FlashMessageService */
                $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
                /** @var $defaultFlashMessageQueue \TYPO3\CMS\Core\Messaging\FlashMessageQueue */
                $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $defaultFlashMessageQueue->enqueue($message);
            }
        }
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}