<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event\Listener;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\Event\ModifyDatabaseQueryForRecordListingEvent;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Event for PageLayoutView to hide tt_content elements in page view
 */
final class ModifyDatabaseQueryForRecordListingEventListener
{
    protected static int $count = 0;

    public function modify(ModifyDatabaseQueryForRecordListingEvent $event): void
    {
        if ($event->getTable() === 'tt_content' && $event->getPageId() > 0) {
            // Get page record base on page uid
            $pageRecord = BackendUtility::getRecord('pages', $event->getPageId(), 'uid', " AND doktype='254' AND module='news'");
            if (is_array($pageRecord)) {
                $tsConfig = BackendUtility::getPagesTSconfig($event->getPageId());

                if ((int)($tsConfig['tx_news.']['showContentElementsInNewsSysFolder'] ?? 0)  === 1) {
                    return;
                }

                // Only hide elements which are inline, allowing for standard
                // elements to show
                $event->getQueryBuilder()->andWhere(
                    $event->getQueryBuilder()->expr()->eq('tx_news_related_news', $event->getQueryBuilder()->createNamedParameter(0, Connection::PARAM_INT))
                );

                if (self::$count === 0) {
                    $this->addFlashMessage();
                }

                self::$count++;
            }
        }
    }

    /**
     * Render flash message to inform user
     * that no elements belonging to news articles
     * are rendered in the page module
     */
    protected function addFlashMessage(): void
    {
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            $this->getLanguageService()->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:hiddenContentElements.description'),
            '',
            ContextualFeedbackSeverity::INFO
        );
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $defaultFlashMessageQueue->enqueue($message);
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
