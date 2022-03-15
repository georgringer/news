<?php

namespace GeorgRinger\News\Hooks\Backend;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Backend\RecordList\RecordListConstraint;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into DatabaseRecordList to hide tt_content elements in list view
 */
class RecordListQueryHook
{
    protected static $count = 0;

    /** @var RecordListConstraint */
    protected $recordListConstraint;

    /**
     * RecordListQueryHook constructor.
     * @param RecordListConstraint $recordListConstraint
     */
    public function __construct(
        RecordListConstraint $recordListConstraint
    ) {
        $this->recordListConstraint = $recordListConstraint;
    }

    /**
     * @return void
     */
    public function modifyQuery(
        array &$parameters,
        string $table,
        int $pageId,
        array $additionalConstraints,
        array $fieldList,
        QueryBuilder $queryBuilder
    ) {
        if ($table === 'tt_content' && $pageId > 0) {
            $pageRecord = BackendUtility::getRecord('pages', $pageId, 'uid', " AND doktype='254' AND module='news'");
            if (is_array($pageRecord)) {
                $tsConfig = BackendUtility::getPagesTSconfig($pageId);
                if ((int)($tsConfig['tx_news.']['showContentElementsInNewsSysFolder'] ?? 0) === 1) {
                    return;
                }

                $queryBuilder->where(...['1=2']);

                if (self::$count === 0) {
                    $this->addFlashMessage();
                }
                self::$count++;
            }
        } elseif ($table === 'tx_news_domain_model_news' && $this->recordListConstraint->isInAdministrationModule()) {
            $vars = GeneralUtility::_GET('tx_news_web_newsadministration');
            if (is_array($vars) && is_array($vars['demand'])) {
                $vars = $vars['demand'];
                $this->recordListConstraint->extendQuery($parameters, $vars);
                if (isset($parameters['orderBy'][0])) {
                    $queryBuilder->orderBy($parameters['orderBy'][0][0], $parameters['orderBy'][0][1]);
                    unset($parameters['orderBy']);
                }
                if (!empty($parameters['whereDoctrine'])) {
                    $queryBuilder->andWhere(...$parameters['whereDoctrine']);
                    unset($parameters['where']);
                }
            }
        }
    }

    private function addFlashMessage(): void
    {
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

    /**
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
