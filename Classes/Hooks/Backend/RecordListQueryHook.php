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
use GeorgRinger\News\Backend\RecordList\RecordListConstraint;
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

    protected static $count = 0;

    /** @var RecordListConstraint */
    protected $recordListConstraint;

    public function __construct()
    {
        $this->recordListConstraint = GeneralUtility::makeInstance(RecordListConstraint::class);
    }

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

                if (self::$count === 0) {
                    $message = GeneralUtility::makeInstance(
                        FlashMessage::class,
                        $this->getLanguageService()->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:hiddenContentElements.description'),
                        '',
                        FlashMessage::INFO
                    );
                    $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
                    $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
                    $defaultFlashMessageQueue->enqueue($message);
                }
                self::$count++;
            }
        } elseif ($table === 'tx_news_domain_model_news' && $this->recordListConstraint->isInAdministrationModule()) {
            $vars = GeneralUtility::_GET('tx_news_web_newstxnewsm2');

            if (is_array($vars) && is_array($vars['demand'])) {
                $vars = $vars['demand'];
                $parts = [];
                $this->recordListConstraint->extendQuery($parts, $vars);
                if (is_array($parts['where']) && !empty($parts['where'])) {
                    $queryParts['WHERE'] .= ' AND ' . implode(' AND ', $parts['where']);
                }
                if (is_array($parts['orderBy']) && !empty($parts['orderBy'])) {
                    $queryParts['ORDERBY'] = $parts['orderBy'][0][0] . ' ' . $parts['orderBy'][0][1];
                }
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
