<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\DataProcessing;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Add the current news record to any menu, e.g. breadcrumb
 *
 * 20 = add-news-to-menu
 * 20.menus = breadcrumbMenu,specialMenu
 */
class AddNewsToMenuProcessor implements DataProcessorInterface
{
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData): array
    {
        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }

        if (!$processorConfiguration['menus']) {
            return $processedData;
        }
        $newsRecord = $this->getNewsRecord();
        if ($newsRecord) {
            $menus = GeneralUtility::trimExplode(',', $processorConfiguration['menus'], true);
            foreach ($menus as $menu) {
                if (isset($processedData[$menu])) {
                    $this->addNewsRecordToMenu($newsRecord, $processedData[$menu]);
                }
            }
        }
        return $processedData;
    }

    /**
     * Add the news record to the menu items
     */
    protected function addNewsRecordToMenu(array $newsRecord, array &$menu): void
    {
        foreach ($menu as &$menuItem) {
            $menuItem['current'] = 0;
        }

        $menu[] = [
            'data' => $newsRecord,
            'title' => $newsRecord['title'],
            'active' => 1,
            'current' => 1,
            'link' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            'isNews' => true,
        ];
    }

    /**
     * Get the news record including possible translations
     */
    protected function getNewsRecord(): array
    {
        /** @var PageArguments $routing */
        $routing = $GLOBALS['TYPO3_REQUEST']->getAttribute('routing');
        $newsId = (int)($routing->getArguments()['tx_news_pi1']['news'] ?? 0);

        if ($newsId) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_news_domain_model_news');
            $row = $queryBuilder
                ->select('*')
                ->from('tx_news_domain_model_news')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($newsId, Connection::PARAM_INT))
                )
                ->executeQuery()->fetchAssociative();

            if ($row) {
                $row = $this->getTsfe()->sys_page->getLanguageOverlay('tx_news_domain_model_news', $row, $this->getCurrentLanguageAspect());
            }

            if (is_array($row) && !empty($row)) {
                return $row;
            }
        }
        return [];
    }

    /**
     * Get current language
     */
    protected function getCurrentLanguage(): int
    {
        $languageId = 0;
        $context = GeneralUtility::makeInstance(Context::class);
        try {
            $languageId = $context->getPropertyFromAspect('language', 'contentId');
        } catch (AspectNotFoundException) {
            // do nothing
        }

        return (int)$languageId;
    }

    protected function getCurrentLanguageAspect(): LanguageAspect
    {
        return GeneralUtility::makeInstance(Context::class)->getAspect('language');
    }

    protected function getTsfe(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
