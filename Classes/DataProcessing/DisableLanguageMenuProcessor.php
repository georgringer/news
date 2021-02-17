<?php

declare(strict_types=1);

namespace GeorgRinger\News\DataProcessing;

use GeorgRinger\News\Seo\NewsAvailability;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Disable language item on a detail page if the news is not translated
 *
 * 20 = GeorgRinger\News\DataProcessing\DisableLanguageMenuProcessor
 * 20.menus = languageMenu
 */
class DisableLanguageMenuProcessor implements DataProcessorInterface
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
        $newsId = $this->getNewsId();
        if ($newsId === 0) {
            return $processedData;
        }
        $menus = GeneralUtility::trimExplode(',', $processorConfiguration['menus'], true);
        foreach ($menus as $menu) {
            if (isset($processedData[$menu])) {
                $this->handleMenu($newsId, $processedData[$menu]);
            }
        }
        return $processedData;
    }

    /**
     * @param int $newsId
     * @param array $menu
     */
    protected function handleMenu(int $newsId, array &$menu): void
    {
        $newsAvailability = GeneralUtility::makeInstance(NewsAvailability::class);
        foreach ($menu as &$item) {
            if (!$item['available']) {
                continue;
            }
            try {
                $availability = $newsAvailability->check((int)$item['languageId'], $newsId);
                if (!$availability) {
                    $item['available'] = false;
                    $item['availableReason'] = 'news';
                }
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * @return int
     */
    protected function getNewsId(): int
    {
        $newsId = 0;
        /** @var PageArguments $pageArguments */
        $pageArguments = $this->getRequest()->getAttribute('routing');
        if (isset($pageArguments->getRouteArguments()['tx_news_pi1']['news'])) {
            $newsId = (int)$pageArguments->getRouteArguments()['tx_news_pi1']['news'];
        } elseif (isset($this->getRequest()->getQueryParams()['tx_news_pi1']['news'])) {
            $newsId = (int)$this->getRequest()->getQueryParams()['tx_news_pi1']['news'];
        }

        return $newsId;
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
