<?php

declare(strict_types=1);

namespace GeorgRinger\News\Backend\FormEngine;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;
use TYPO3\CMS\Core\Utility\MathUtility;

class SlugPrefix
{
    public function getPrefix(array $parameters): string
    {
        $row = $parameters['row'];
        $pagesTsConfig = BackendUtility::getPagesTSconfig($row['pid']);
        $configuration = $pagesTsConfig['tx_news.']['slugPrefix'] ?? '';
        if ($configuration === 'none' || $configuration === '') {
            return '';
        }
        if ($configuration === 'default') {
            return $this->getPrefixForSite($parameters['site'], $row['sys_language_uid']);
        }
        if (MathUtility::canBeInterpretedAsInteger($configuration)) {
            $prefix = $this->generateUrl($parameters['site'], (int)$configuration, $row['uid'], $row['sys_language_uid']);
            return $this->stripNewsSegment($prefix, $parameters['row']['path_segment']);
        }

        return '';
    }

    protected function stripNewsSegment(string $url, string $slug): string
    {
        if (strpos($url, '?tx_news_pi1%5Ba') !== false) {
            $url = substr($url, 0, strpos($url, '?tx_news_pi1%5Ba')) . '/[no mapping]';
        } else {
            $url = str_replace($slug, '', $url);
        }

        return $url;
    }

    protected function generateUrl(Site $site, int $pageId, int $recordId, int $languageId): string
    {
        $parameters = [
            '_language' => $languageId,
            'tx_news_pi1' => [
                'action' => 'detail',
                'controller' => 'News',
                'news' => $recordId
            ]
        ];
        return (string)$site->getRouter()->generateUri(
            (string)$pageId,
            $parameters
        );
    }

    /**
     * Render the prefix for the input field.
     *
     * @param SiteInterface $site
     * @param int $languageId
     * @return string
     */
    protected function getPrefixForSite(SiteInterface $site, int $languageId): string
    {
        try {
            $language = ($languageId < 0) ? $site->getDefaultLanguage() : $site->getLanguageById($languageId);
            $base = $language->getBase();
            $prefix = rtrim((string)$base, '/');
            if ($prefix !== '' && empty($base->getScheme()) && $base->getHost() !== '') {
                $prefix = 'http:' . $prefix;
            }
        } catch (\InvalidArgumentException $e) {
            // No site found
            $prefix = '';
        }

        return $prefix;
    }
}
