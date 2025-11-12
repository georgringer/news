<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TemplateLayout utility class
 */
class TemplateLayout implements SingletonInterface
{
    /**
     * Get available template layouts for a certain page
     */
    public function getAvailableTemplateLayouts(int $pageUid): array
    {
        $templateLayouts = [];
        // Add TsConfig values
        foreach ($this->getTemplateLayoutsFromTsConfig($pageUid) as $templateKey => $titleOrConfiguration) {
            if (is_string($titleOrConfiguration) && str_starts_with($titleOrConfiguration, '--div--')) {
                $optGroupParts = GeneralUtility::trimExplode(',', $titleOrConfiguration, true, 2);
                $titleOrConfiguration = $optGroupParts[1];
                $templateKey = $optGroupParts[0];
            } elseif (is_array($titleOrConfiguration) && str_ends_with($templateKey, '.')) {
                $templateKey = substr($templateKey, 0, -1);
                if (isset($titleOrConfiguration['allowedColPos'])) {
                    $templateLayouts[$templateKey]['allowedColPos'] = $titleOrConfiguration['allowedColPos'];
                }
                continue;
            }
            $templateLayouts[$templateKey] = ['label' => $titleOrConfiguration, 'key' => $templateKey];
        }

        return $templateLayouts;
    }

    /**
     * Get template layouts defined in TsConfig
     */
    protected function getTemplateLayoutsFromTsConfig(int $pageUid): array
    {
        $templateLayouts = [];
        $pagesTsConfig = BackendUtility::getPagesTSconfig($pageUid);
        if (isset($pagesTsConfig['tx_news.']['templateLayouts.']) && is_array($pagesTsConfig['tx_news.']['templateLayouts.'])) {
            $templateLayouts = $pagesTsConfig['tx_news.']['templateLayouts.'];
        }
        return $templateLayouts;
    }
}
