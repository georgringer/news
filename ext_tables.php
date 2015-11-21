<?php
defined('TYPO3_MODE') or die();

$boot = function () {

    // CSH - context sensitive help
    foreach (['news', 'media', 'file', 'link', 'tag'] as $table) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_' . $table);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_news_domain_model_' . $table, 'EXT:news/Resources/Private/Language/locallang_csh_' . $table . '.xlf');
    }

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tt_content.pi_flexform.news_pi1.list', 'EXT:news/Resources/Private/Language/locallang_csh_flexforms.xlf');

    // Extension manager configuration
    $configuration = \GeorgRinger\News\Utility\EmConfiguration::getSettings();

    if (TYPO3_MODE === 'BE') {
        // Override news icon
        $GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
            0 => 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:news-folder',
            1 => 'news',
            2 => 'apps-pagetree-folder-contains-news'
        ];

        /***************
         * Show news table in page module
         */
        if ($configuration->getPageModuleFieldsNews()) {
            $addTableItems = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(';',
                $configuration->getPageModuleFieldsNews(), true);
            foreach ($addTableItems as $item) {
                $split = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('=', $item, true);
                if (count($split) == 2) {
                    $fTitle = $split[0];
                    $fList = $split[1];
                } else {
                    $fTitle = '';
                    $fList = $split[0];
                }
                $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cms']['db_layout']['addTables']['tx_news_domain_model_news'][] = [
                    'MENU' => $fTitle,
                    'fList' => $fList,
                    'icon' => true,
                ];
            }
        }

        if ($configuration->getPageModuleFieldsCategory()) {
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cms']['db_layout']['addTables']['sys_category'][0] = [
                'fList' => htmlspecialchars($configuration->getPageModuleFieldsCategory()),
                'icon' => true
            ];
        }

        // Extend user settings
        $GLOBALS['TYPO3_USER_SETTINGS']['columns']['newsoverlay'] = [
            'label' => 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:usersettings.overlay',
            'type' => 'select',
            'itemsProcFunc' => \GeorgRinger\News\Hooks\ItemsProcFunc::class . '->user_categoryOverlay',
        ];
        $GLOBALS['TYPO3_USER_SETTINGS']['showitem'] .= ',
			--div--;LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:pi1_title,newsoverlay';

        // Add tables to livesearch (e.g. "#news:fo" or "#newscat:fo")
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['news'] = 'tx_news_domain_model_news';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['newstag'] = 'tx_news_domain_model_tag';

        /* ===========================================================================
            Register BE-Modules
        =========================================================================== */
        if ($configuration->getShowImporter()) {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'GeorgRinger.news',
                'web',
                'tx_news_m1',
                '',
                ['Import' => 'index, runJob, jobInfo'],
                [
                    'access' => 'user,group',
                    'icon' => 'EXT:news/Resources/Public/Icons/module_import.svg',
                    'labels' => 'LLL:EXT:news/Resources/Private/Language/locallang_mod.xlf',
                ]
            );
        }

        /* ===========================================================================
            Register BE-Module for Administration
        =========================================================================== */
        if ($configuration->getShowAdministrationModule()) {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'GeorgRinger.news',
                'web',
                'tx_news_m2',
                '',
                ['Administration' => 'index,newNews,newCategory,newTag,newsPidListing'],
                [
                    'access' => 'user,group',
                    'icon' => 'EXT:news/Resources/Public/Icons/module_administration.svg',
                    'labels' => 'LLL:EXT:news/Resources/Private/Language/locallang_modadministration.xlf',
                ]
            );
        }

        /* ===========================================================================
            Ajax call to save tags
        =========================================================================== */
        $GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['News::createTag'] = [
            'callbackMethod' => \GeorgRinger\News\Hooks\SuggestReceiverCall::class . '->createTag',
            'csrfTokenCheck' => false
        ];
    }

    /* ===========================================================================
        Default configuration
    =========================================================================== */
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByCategory'] = 'uid,title,tstamp,sorting';
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews'] = 'tstamp,datetime,crdate,title' . ($configuration->getManualSorting() ? ',sorting' : '');
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByTag'] = 'tstamp,crdate,title';
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['list'] = $configuration->getRemoveListActionFromFlexforms();

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'news',
        'Configuration/TSconfig/Page/news_only.txt',
        'EXT:news :: Restrict pages to news records');

};

$boot();
unset($boot);