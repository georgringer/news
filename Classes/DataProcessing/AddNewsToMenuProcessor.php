<?php

declare(strict_types=1);

namespace GeorgRinger\News\DataProcessing;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Add the current news record to any menu, e.g. breadcrumb
 *
 * 20 = GeorgRinger\News\DataProcessing\AddNewsToMenuProcessor
 * 20.menus = breadcrumbMenu,specialMenu
 */
class AddNewsToMenuProcessor implements DataProcessorInterface
{

    /**
     * @param ContentObjectRenderer $cObj
     * @param array $contentObjectConfiguration
     * @param array $processorConfiguration
     * @param array $processedData
     * @return array
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData): array
    {
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
     *
     * @param array $newsRecord
     * @param array $menu
     *
     * @return void
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
            'isNews' => true
        ];
    }

    /**
     * Get the news record including possible translations
     *
     * @return array
     */
    protected function getNewsRecord(): array
    {
        $newsId = 0;
        $vars = GeneralUtility::_GET('tx_news_pi1');
        if (isset($vars['news'])) {
            $newsId = (int)$vars['news'];
        }

        if ($newsId) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_news_domain_model_news');
            $row = $queryBuilder
                ->select('*')
                ->from('tx_news_domain_model_news')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($newsId, \PDO::PARAM_INT))
                )
                ->execute()
                ->fetch();

            if ($row) {
                $row = $this->getTsfe()->sys_page->getRecordOverlay('tx_news_domain_model_news', $row, $this->getCurrentLanguage());
            }

            if (is_array($row) && !empty($row)) {
                return $row;
            }
        }
        return [];
    }

    /**
     * Get current language
     *
     * @return int
     */
    protected function getCurrentLanguage(): int
    {
        $languageId = 0;
        $context = GeneralUtility::makeInstance(Context::class);
        try {
            $languageId = $context->getPropertyFromAspect('language', 'contentId');
        } catch (AspectNotFoundException $e) {
            // do nothing
        }

        return (int)$languageId;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTsfe(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
