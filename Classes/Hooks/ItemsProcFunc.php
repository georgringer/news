<?php

namespace GeorgRinger\News\Hooks;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Utility\TemplateLayout;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Userfunc to render alternative label for media elements
 */
class ItemsProcFunc
{

    /** @var TemplateLayout $templateLayoutsUtility */
    protected $templateLayoutsUtility;

    /**
     * ItemsProcFunc constructor.
     * @param TemplateLayout $templateLayout
     */
    public function __construct(
        TemplateLayout $templateLayout
    ) {
        $this->templateLayoutsUtility = $templateLayout;
    }

    /**
     * Itemsproc function to extend the selection of templateLayouts in the plugin
     *
     * @param array &$config configuration array
     *
     * @return void
     */
    public function user_templateLayout(array &$config): void
    {
        $pageId = 0;

        $currentColPos = $config['flexParentDatabaseRow']['colPos'];
        $pageId = $this->getPageId($config['flexParentDatabaseRow']['pid']);

        if ($pageId > 0) {
            $templateLayouts = $this->templateLayoutsUtility->getAvailableTemplateLayouts($pageId);

            $templateLayouts = $this->reduceTemplateLayouts($templateLayouts, $currentColPos);
            foreach ($templateLayouts as $layout) {
                $additionalLayout = [
                    htmlspecialchars($this->getLanguageService()->sL($layout[0])),
                    $layout[1]
                ];
                array_push($config['items'], $additionalLayout);
            }
        }
    }

    /**
     * Reduce the template layouts by the ones that are not allowed in given colPos
     *
     * @param array $templateLayouts
     * @param int $currentColPos
     * @return array
     */
    protected function reduceTemplateLayouts($templateLayouts, $currentColPos): array
    {
        $currentColPos = (int)$currentColPos;
        $restrictions = [];
        $allLayouts = [];
        foreach ($templateLayouts as $key => $layout) {
            if (is_array($layout[0])) {
                if (isset($layout[0]['allowedColPos']) && str_ends_with((string)$layout[1], '.')) {
                    $layoutKey = substr($layout[1], 0, -1);
                    $restrictions[$layoutKey] = GeneralUtility::intExplode(',', $layout[0]['allowedColPos'], true);
                }
            } else {
                $allLayouts[$key] = $layout;
            }
        }
        if (!empty($restrictions)) {
            foreach ($restrictions as $restrictedIdentifier => $restrictedColPosList) {
                if (!in_array($currentColPos, $restrictedColPosList, true)) {
                    unset($allLayouts[$restrictedIdentifier]);
                }
            }
        }

        return $allLayouts;
    }

    /**
     * Modifies the select box of orderBy-options as a category menu
     * needs different ones then a news action
     *
     * @param array &$config configuration array
     *
     * @return void
     */
    public function user_orderBy(array &$config): void
    {
        $row = $this->getContentElementRow($config['row']['uid']);

        // check if the record has been saved once
        if (is_array($row) && !empty($row['pi_flexform'])) {
            $flexformConfig = GeneralUtility::xml2array($row['pi_flexform']);

            // check if there is a flexform configuration
            if (isset($flexformConfig['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'])) {
                $selectedActionList = $flexformConfig['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'] ?? '';
                // check for selected action
                if (str_starts_with($selectedActionList, 'Category')) {
                    $newItems = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByCategory'];
                } elseif (str_starts_with($selectedActionList, 'Tag')) {
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
     *
     * @return void
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
     * Modifies the selectbox of available actions
     *
     * @param array &$config
     *
     * @return void
     */
    public function user_switchableControllerActions(array &$config): void
    {
        if (!empty($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['list'])) {
            $configuration = (int)$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['list'];
            switch ($configuration) {
                case 1:
                    $this->removeActionFromList($config, 'News->list');
                    break;
                case 2:
                    $this->removeActionFromList($config, 'News->list;News->detail');
                    break;
                default:
            }
        }

        // Add additional actions
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems'])
            && is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems'])
        ) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems'] as $key => $label) {
                array_push($config['items'], [$this->getLanguageService()->sL($label), $key, '']);
            }
        }
    }

    /**
     * Remove given action from switchableControllerActions
     *
     * @param array $config available items
     * @param string $action action to be removed
     *
     * @return void
     */
    private function removeActionFromList(array &$config, $action): void
    {
        foreach ($config['items'] as $key => $item) {
            if ($item[1] === $action) {
                unset($config['items'][$key]);
                continue;
            }
        }
    }

    /**
     * Generate a select box of languages to choose an overlay
     *
     * @return string select box
     */
    public function user_categoryOverlay(): string
    {
        $html = '';

        $languages = $this->getAllLanguages();
        // if any language is available
        if (count($languages) > 0) {
            $html = '<select name="data[newsoverlay]" id="field_newsoverlay" class="form-control">';

            if (!isset($languages[0])) {
                $html .= '<option value="0">' . htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value')) . '</option>';
            }

            foreach ($languages as $language) {
                $selected = ((int)($GLOBALS['BE_USER']->uc['newsoverlay'] ?? 0) === (int)$language['uid']) ? ' selected="selected" ' : '';
                $html .= '<option ' . $selected . 'value="' . $language['uid'] . '">' . htmlspecialchars($language['title']) . '</option>';
            }

            $html .= '</select>';
        } else {
            $html .= htmlspecialchars(
                $this->getLanguageService()->sL(
                    'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:usersettings.no-languages-available'
                )
            );
        }

        return $html;
    }

    /**
     * Get all languages
     *
     * @return array
     */
    protected function getAllLanguages(): array
    {
        $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
        if ($versionInformation->getMajorVersion() === 10) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('sys_language');
            return $queryBuilder->select('*')
                ->from('sys_language')
                ->orderBy('sorting')
                ->execute()
                ->fetchAll();
        }
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
     *
     * @return array|null
     */
    protected function getContentElementRow($uid): ?array
    {
        return BackendUtilityCore::getRecord('tt_content', $uid);
    }

    /**
     * Get page id, if negative, then it is a "after record"
     *
     * @param int $pid
     * @return int
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
     *
     * @return \TYPO3\CMS\Core\Localization\LanguageService
     */
    protected function getLanguageService(): \TYPO3\CMS\Core\Localization\LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
