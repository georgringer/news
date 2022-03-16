<?php

namespace GeorgRinger\News\Hooks\Backend;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into PageLayoutView to hide tt_content elements in page view
 */
class PageViewQueryHook
{
    protected static $count = 0;

    /**
     * Prevent inline tt_content elements in news articles from
     * showing in the page module.
     *
     * @param array $parameters
     * @param string $table
     * @param int $pageId
     * @param array $additionalConstraints
     * @param string[] $fieldList
     * @param QueryBuilder $queryBuilder
     */
    public function modifyQuery(
        $parameters,
        $table,
        $pageId,
        $additionalConstraints,
        $fieldList,
        QueryBuilder $queryBuilder
    ): void {
        if ($table === 'tt_content' && $pageId > 0) {

            // Get page record base on page uid
            $pageRecord = BackendUtility::getRecord('pages', $pageId, 'uid', " AND doktype='254' AND module='news'");

            if (is_array($pageRecord)) {
                $tsConfig = BackendUtility::getPagesTSconfig($pageId);

                if ((int)($tsConfig['tx_news.']['showContentElementsInNewsSysFolder'] ?? 0)  === 1) {
                    return;
                }

                // Only hide elements which are inline, allowing for standard
                // elements to show
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->eq('tx_news_related_news', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT))
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
     *
     * @return void
     */
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
