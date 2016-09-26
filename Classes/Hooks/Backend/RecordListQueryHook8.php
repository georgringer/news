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
 * Hook into DatabaseRecordList to hide tt_content elements in list view
 *
 */
class RecordListQueryHook8
{
    protected static $count = 0;

    /** @var RecordListConstraint */
    protected $recordListConstraint;

    public function __construct()
    {
        $this->recordListConstraint = GeneralUtility::makeInstance(RecordListConstraint::class);
    }

    public function buildQueryParametersPostProcess(
        array &$parameters,
        string $table,
        int $pageId,
        array $additionalConstraints,
        array $fieldList,
        AbstractDatabaseRecordList $parentObject
    ) {
        if ($table === 'tt_content' && (int)$parentObject->searchLevels === 0 && $parentObject->id > 0) {
            $pageRecord = BackendUtility::getRecord('pages', $parentObject->id, 'uid', ' AND doktype="254" AND module="news"');
            if (is_array($pageRecord)) {
                $tsConfig = BackendUtility::getPagesTSconfig($parentObject->id);
                if (isset($tsConfig['tx_news.']) && is_array($tsConfig['tx_news.']) && $tsConfig['tx_news.']['showContentElementsInNewsSysFolder'] == 1) {
                    return;
                }
                $parameters['where'][] = '1=2';

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
                $this->recordListConstraint->extendQuery($parameters, $vars, $parentObject->id);
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
