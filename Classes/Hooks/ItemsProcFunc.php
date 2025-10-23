<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Hooks;

use GeorgRinger\News\Utility\TemplateLayout;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Userfunc to render alternative label for media elements
 */
class ItemsProcFunc
{
    protected TemplateLayout $templateLayoutsUtility;

    public function __construct(
        TemplateLayout $templateLayout
    ) {
        $this->templateLayoutsUtility = $templateLayout;
    }

    /**
     * Itemsproc function to extend the selection of templateLayouts in the plugin
     *
     * @param array &$config configuration array
     */
    public function user_templateLayout(array &$config): void
    {
        $pageId = 0;

        $currentColPos = $config['flexParentDatabaseRow']['colPos'] ?? null;
        if ($currentColPos === null) {
            return;
        }
        $pageId = $this->getPageId($config['flexParentDatabaseRow']['pid']);

        if ($pageId > 0) {
            $templateLayouts = $this->templateLayoutsUtility->getAvailableTemplateLayouts($pageId);

            $templateLayouts = $this->reduceTemplateLayouts($templateLayouts, $currentColPos);
            foreach ($templateLayouts as $identifier => $label) {
                $config['items'][] = [
                    'label' => $this->getLanguageService()->sL($label),
                    'value' => $identifier,
                ];
            }
        }
    }

    /**
     * Reduce the template layouts by the ones that are not allowed in given colPos
     *
     * @param array $templateLayouts
     * @param int $currentColPos
     */
    protected function reduceTemplateLayouts($templateLayouts, $currentColPos): array
    {
        $finalLayouts = [];
        $currentColPos = (int)$currentColPos;
        foreach ($templateLayouts as $key => $configuration) {
            if (isset($configuration['allowedColPos']) && !GeneralUtility::inList($configuration['allowedColPos'], (string)$currentColPos)) {
                continue;
            }
            $finalLayouts[$key] = $configuration['label'];
        }

        return $finalLayouts;
    }

    /**
     * Modifies the select box of orderBy-options as a category menu
     * needs different ones then a news action
     *
     * @param array &$config configuration array
     */
    public function user_orderBy(array &$config): void
    {
        $row = $this->getContentElementRow($config['row']['uid']);

        // check if the record has been saved once
        if (is_array($row) && !empty($row['pi_flexform'])) {
            $flexformConfig = GeneralUtility::xml2array($row['pi_flexform']);

            // check if there is a flexform configuration
            if (isset($flexformConfig['data']['sDEF']['lDEF'])) {
                $selectedPlugin = strtolower($row['CType']) ?? '';
                // check for selected plugin
                if ($selectedPlugin === 'news_categorylist') {
                    $newItems = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByCategory'];
                } elseif ($selectedPlugin === 'news_taglist') {
                    $this->removeNonValidOrderFields($config, 'tx_news_domain_model_tag');
                    $newItems = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByTag'];
                } else {
                    $newItems = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews'];
                }
            }
        }

        // if a override configuration is found
        if (!empty($newItems)) {
            // remove default configuration
            $config['items'] = [];
            // empty default line
            array_push($config['items'], ['', '']);

            $newItemArray = GeneralUtility::trimExplode(',', $newItems, true);
            $languageKey = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:flexforms_general.orderBy.';
            foreach ($newItemArray as $item) {
                // label: if empty, key (=field) is used
                $label = $this->getLanguageService()->sL($languageKey . $item);
                if (empty($label)) {
                    $label = $item;
                }
                array_push($config['items'], [htmlspecialchars($label), $item]);
            }
        }
    }

    /**
     * Remove not valid fields from ordering
     *
     * @param array $config tca items
     * @param string $tableName table name
     */
    protected function removeNonValidOrderFields(array &$config, $tableName): void
    {
        $allowedFields = array_keys($GLOBALS['TCA'][$tableName]['columns']);

        foreach ($config['items'] as $key => $item) {
            if ($item[1] != '' && !in_array($item[1], $allowedFields)) {
                unset($config['items'][$key]);
            }
        }
    }

    /**
     * Get all languages
     */
    protected function getAllLanguages(): array
    {
        $siteLanguages = [];
        foreach (GeneralUtility::makeInstance(SiteFinder::class)->getAllSites() as $site) {
            foreach ($site->getAllLanguages() as $languageId => $language) {
                if (!isset($siteLanguages[$languageId])) {
                    $siteLanguages[$languageId] = [
                        'uid' => $languageId,
                        'title' => $language->getTitle(),
                    ];
                }
            }
        }
        return $siteLanguages;
    }

    /**
     * Get tt_content record
     *
     * @param int $uid
     */
    protected function getContentElementRow($uid): ?array
    {
        return BackendUtilityCore::getRecord('tt_content', $uid);
    }

    /**
     * Get page id, if negative, then it is a "after record"
     *
     * @param int $pid
     */
    protected function getPageId($pid): int
    {
        $pid = (int)$pid;

        if ($pid > 0) {
            return $pid;
        }

        $row = BackendUtilityCore::getRecord('tt_content', abs($pid), 'uid,pid');
        return $row['pid'];
    }

    /**
     * Returns LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
