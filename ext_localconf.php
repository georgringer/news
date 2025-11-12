<?php

use GeorgRinger\News\Backend\FormDataProvider\NewsRowInitializeNew;
use GeorgRinger\News\Controller\CategoryController;
use GeorgRinger\News\Controller\NewsController;
use GeorgRinger\News\Controller\TagController;
use GeorgRinger\News\Hooks\DataHandlerHook;
use GeorgRinger\News\Routing\NewsCategoryMapper;
use GeorgRinger\News\Routing\NewsTagMapper;
use GeorgRinger\News\Routing\NewsTitleMapper;
use GeorgRinger\News\Utility\ClassCacheManager;
use GeorgRinger\News\Utility\ClassLoader;
use GeorgRinger\News\Xclass\ExtensionServiceXclassed;
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew;
use TYPO3\CMS\Core\Cache\Backend\FileBackend;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Service\ExtensionService;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die;

$boot = static function (): void {
    ExtensionUtility::configurePlugin(
        'News',
        'Pi1',
        [
            NewsController::class => 'list,detail',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    ExtensionUtility::configurePlugin(
        'News',
        'NewsListSticky',
        [
            NewsController::class => 'list',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    ExtensionUtility::configurePlugin(
        'News',
        'NewsDetail',
        [
            NewsController::class => 'detail',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    ExtensionUtility::configurePlugin(
        'News',
        'NewsSelectedList',
        [
            NewsController::class => 'selectedList',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    ExtensionUtility::configurePlugin(
        'News',
        'NewsDateMenu',
        [
            NewsController::class => 'dateMenu',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    ExtensionUtility::configurePlugin(
        'News',
        'NewsSearchForm',
        [
            NewsController::class => 'searchForm',
        ],
        [
            NewsController::class => 'searchForm',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    ExtensionUtility::configurePlugin(
        'News',
        'NewsSearchResult',
        [
            NewsController::class => 'searchResult',
        ],
        [
            NewsController::class => 'searchResult',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    ExtensionUtility::configurePlugin(
        'News',
        'CategoryList',
        [
            CategoryController::class => 'list',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    ExtensionUtility::configurePlugin(
        'News',
        'TagList',
        [
            TagController::class => 'list',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['news_clearcache']
        = DataHandlerHook::class . '->clearCachePostProc';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['moveRecordClass']['news_clearcache']
        = DataHandlerHook::class;

    // Edit restriction for news records
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['news']
        = DataHandlerHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['news']
        = DataHandlerHook::class;

    /* ===========================================================================
        Custom cache, done with the caching framework
    =========================================================================== */
    if (empty($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news_category'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news_category'] = [];
    }
    // Define string frontend as default frontend, this must be set with TYPO3 4.5 and below
    // and overrides the default variable frontend of 4.6
    if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news_category']['frontend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news_category']['frontend'] = VariableFrontend::class;
    }

    /* ===========================================================================
        Add TSconfig
    =========================================================================== */
    if ((new Typo3Version())->getMajorVersion() < 13) {
        // @extensionScannerIgnoreLine
        ExtensionManagementUtility::addPageTSConfig('@import \'EXT:news/Configuration/TSconfig/ContentElementWizard.tsconfig\'');

        // For linkvalidator
        if (ExtensionManagementUtility::isLoaded('linkvalidator')) {
            // @extensionScannerIgnoreLine
            ExtensionManagementUtility::addPageTSConfig('@import \'EXT:news/Configuration/TSconfig/Page/mod.linkvalidator.tsconfig\'');
        }
    }

    /* ===========================================================================
        Hooks
    =========================================================================== */
    // Register cache frontend for proxy class generation
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news'] = [
        'frontend' => PhpFrontend::class,
        'backend' => FileBackend::class,
        'groups' => [
            'all',
            'system',
        ],
        'options' => [
            'defaultLifetime' => 0,
        ],
    ];

    if (class_exists(ClassLoader::class)) {
        ClassLoader::registerAutoloader();
    }
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['ext:news']
        = ClassCacheManager::class . '->reBuild';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][NewsRowInitializeNew::class] = [
        'depends' => [
            DatabaseRowInitializeNew::class,
        ],
    ];

    ExtensionManagementUtility::addTypoScriptSetup(trim('
    plugin {
        tx_news_newsliststicky.view.pluginNamespace = tx_news_pi1
        tx_news_newsdetail.view.pluginNamespace = tx_news_pi1
        tx_news_newsselectedlist.view.pluginNamespace = tx_news_pi1
        tx_news_newsdatemenu.view.pluginNamespace = tx_news_pi1
        tx_news_categorylist.view.pluginNamespace = tx_news_pi1
        tx_news_newssearchform.view.pluginNamespace = tx_news_pi1
        tx_news_newssearchresult.view.pluginNamespace = tx_news_pi1
        tx_news_taglist.view.pluginNamespace = tx_news_pi1
    }
    config.pageTitleProviders {
        news {
            provider = GeorgRinger\News\Seo\NewsTitleProvider
            before = record,seo
        }
    }
'));

    if (ExtensionManagementUtility::isLoaded('seo')) {
        $GLOBALS['TYPO3_CONF_VARS']['FE']['additionalCanonicalizedUrlParameters'] = array_merge(
            $GLOBALS['TYPO3_CONF_VARS']['FE']['additionalCanonicalizedUrlParameters'] ?? [],
            [
                'tx_news_pi1[action]',
                'tx_news_pi1[controller]',
                'tx_news_pi1[news]',
                'tx_news_pi1[overwriteDemand][tags]',
                'tx_news_pi1[overwriteDemand][categories]',
                'tx_news_pi1[overwriteDemand][year]',
                'tx_news_pi1[overwriteDemand][month]',
                'tx_news_pi1[overwriteDemand][day]',
            ]
        );
    }
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = '^tx_news_pi1[search][';
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['requireCacheHashPresenceParameters'][] = 'tx_news_pi1[currentPage]';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][ExtensionService::class] = [
        'className' => ExtensionServiceXclassed::class,
    ];

    // Add routing features
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['NewsCategory'] = NewsCategoryMapper::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['NewsTag'] = NewsTagMapper::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['NewsTitle'] = NewsTitleMapper::class;

    $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['news'] = 'EXT:news/Resources/Public/Css/Backend/RecordList.css';
};

$boot();
unset($boot);
