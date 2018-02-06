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
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * Userfunc to render alternative label for media elements
 */
class ItemsProcFunc
{

    /** @var TemplateLayout $templateLayoutsUtility */
    protected $templateLayoutsUtility;

    public function __construct()
    {
        $this->templateLayoutsUtility = GeneralUtility::makeInstance(TemplateLayout::class);
    }

    /**
     * Itemsproc function to extend the selection of templateLayouts in the plugin
     *
     * @param array &$config configuration array
     */
    public function user_templateLayout(array &$config)
    {
        $pageId = 0;
        $currentColPos = null;
        if (ExtensionManagementUtility::isLoaded('compatibility6')) {
            if (StringUtility::beginsWith($config['row']['uid'], 'NEW')) {
                $getVars = GeneralUtility::_GET('edit');
                if (is_array($getVars) && isset($getVars['tt_content']) && is_array($getVars['tt_content'])) {
                    $keys = array_keys($getVars['tt_content']);
                    $firstKey = (int)$keys[0];
                    if ($firstKey > 0) {
                        $pageId = $firstKey;
                    } else {
                        $row = $this->getContentElementRow(abs($firstKey));
                        $pageId = $row['pid'];
                    }
                }
            } else {
                $row = $this->getContentElementRow($config['row']['uid']);
                $pageId = $row['pid'];
            }
            if (isset($config['row']['colPos'])) {
                $currentColPos = $config['row']['colPos'];
            }
        } else {
            $currentColPos = $config['flexParentDatabaseRow']['colPos'];
            $pageId = $this->getPageId($config['flexParentDatabaseRow']['pid']);
        }

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
     * Reduce the template layouts by the once which are not allowed in given colPos
     * @param array $templateLayouts
     * @param int $currentColPos
     * @return array
     */
    protected function reduceTemplateLayouts($templateLayouts, $currentColPos)
    {
        $currentColPos = (int)$currentColPos;
        $restrictions = [];
        $allLayouts = [];
        foreach ($templateLayouts as $key => $layout) {
            if (is_array($layout[0])) {
                if (isset($layout[0]['allowedColPos']) && StringUtility::endsWith($layout[1], '.')) {
                    $layoutKey = substr($layout[1], 0, -1);
                    $restrictions[$layoutKey] = GeneralUtility::intExplode(',', $layout[0]['allowedColPos'], true);
                }
            } else {
                $allLayouts[$layout[1]] = $layout;
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
     */
    public function user_orderBy(array &$config)
    {
        $row = $this->getContentElementRow($config['row']['uid']);

        // check if the record has been saved once
        if (is_array($row) && !empty($row['pi_flexform'])) {
            $flexformConfig = GeneralUtility::xml2array($row['pi_flexform']);

            // check if there is a flexform configuration
            if (isset($flexformConfig['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'])) {
                $selectedActionList = $flexformConfig['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];
                // check for selected action
                if (GeneralUtility::isFirstPartOfStr($selectedActionList, 'Category')) {
                    $newItems = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByCategory'];
                } elseif (GeneralUtility::isFirstPartOfStr($selectedActionList, 'Tag')) {
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
    protected function removeNonValidOrderFields(array &$config, $tableName)
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
     */
    public function user_switchableControllerActions(array &$config)
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
     */
    private function removeActionFromList(array &$config, $action)
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
    public function user_categoryOverlay()
    {
        $html = '';

        $languages = $this->getAllLanguages();
        // if any language is available
        if (count($languages) > 0) {
            $html = '<select name="data[newsoverlay]" id="field_newsoverlay" class="form-control">
						<option value="0">' . htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:lang/locallang_general.xlf:LGL.default_value')) . '</option>';

            foreach ($languages as $language) {
                $selected = ((int)$GLOBALS['BE_USER']->uc['newsoverlay'] === (int)$language['uid']) ? ' selected="selected" ' : '';
                $html .= '<option ' . $selected . 'value="' . $language['uid'] . '">' . htmlspecialchars($language['title']) . '</option>';
            }

            $html .= '</select>';
        } else {
            $html .= htmlspecialchars($this->getLanguageService()->sL(
                'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:usersettings.no-languages-available')
            );
        }

        return $html;
    }

    /**
     * Get all languages
     *
     * @return array
     */
    protected function getAllLanguages()
    {
        if (class_exists(ConnectionPool::class)) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('sys_language');
            return $queryBuilder->select('*')
                ->from('sys_language')
                ->orderBy('sorting')
                ->execute()
                ->fetchAll();
        } else {
            return $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
                '*',
                'sys_language',
                '1=1 ' . BackendUtilityCore::deleteClause('sys_language')
            );
        }
    }

    /**
     * Get tt_content record
     *
     * @param int $uid
     * @return array
     */
    protected function getContentElementRow($uid)
    {
        return BackendUtilityCore::getRecord('tt_content', $uid);
    }

    /**
     * Get page id, if negative, then it is a "after record"
     *
     * @param int $pid
     * @return int
     */
    protected function getPageId($pid)
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
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
