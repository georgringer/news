<?php

defined('TYPO3') or die();

$boot = static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'News',
        'Pi1',
        [
            \GeorgRinger\News\Controller\NewsController::class => 'list,detail',
        ],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'News',
        'NewsListSticky',
        [
            \GeorgRinger\News\Controller\NewsController::class => 'list',
        ],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'News',
        'NewsDetail',
        [
            \GeorgRinger\News\Controller\NewsController::class => 'detail',
        ],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'News',
        'NewsSelectedList',
        [
            \GeorgRinger\News\Controller\NewsController::class => 'selectedList',
        ],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'News',
        'NewsDateMenu',
        [
            \GeorgRinger\News\Controller\NewsController::class => 'dateMenu',
        ],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'News',
        'NewsSearchForm',
        [
            \GeorgRinger\News\Controller\NewsController::class => 'searchForm',
        ],
        [
            \GeorgRinger\News\Controller\NewsController::class => 'searchForm',
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'News',
        'NewsSearchResult',
        [
            \GeorgRinger\News\Controller\NewsController::class => 'searchResult',
        ],
        [
            \GeorgRinger\News\Controller\NewsController::class => 'searchResult',
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'News',
        'CategoryList',
        [
            \GeorgRinger\News\Controller\CategoryController::class => 'list',
        ],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'News',
        'TagList',
        [
            \GeorgRinger\News\Controller\TagController::class => 'list',
        ],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // Page module hook
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['news' . '_pi1']['news'] =
        \GeorgRinger\News\Hooks\PageLayoutView::class . '->getExtensionSummary';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['news_clearcache'] =
        \GeorgRinger\News\Hooks\DataHandlerHook::class . '->clearCachePostProc';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['moveRecordClass']['news_clearcache'] =
        \GeorgRinger\News\Hooks\DataHandlerHook::class;

    // Edit restriction for news records
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['news'] =
        \GeorgRinger\News\Hooks\DataHandlerHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['news'] =
        \GeorgRinger\News\Hooks\DataHandlerHook::class;

    // Modify flexform values
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools::class]['flexParsing']['news']
        = \GeorgRinger\News\Hooks\FlexformHook::class;

//    // Modify flexform fields since core 8.5 via formEngine: Inject a data provider between TcaFlexPrepare and TcaFlexProcess
//    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\GeorgRinger\News\Backend\FormDataProvider\NewsFlexFormManipulation::class] = [
//        'depends' => [
//            \TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexPrepare::class,
//        ],
//        'before' => [
//            \TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexProcess::class,
//        ],
//    ];

    // Hide content elements in list module & filter in administration module
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList::class]['modifyQuery']['ext:news']
        = \GeorgRinger\News\Hooks\Backend\RecordListQueryHook::class;

    // Hide content elements in page module
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Backend\View\PageLayoutView::class]['modifyQuery']['ext:news'] = \GeorgRinger\News\Hooks\Backend\PageViewQueryHook::class;

    // Inline records hook
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms_inline.php']['tceformsInlineHook']['news'] =
        \GeorgRinger\News\Hooks\InlineElementHook::class;

    // Xclass InlineRecordContainer
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\Form\Container\InlineRecordContainer::class] = [
        'className' => \GeorgRinger\News\Xclass\InlineRecordContainerForNews::class,
    ];

    /* ===========================================================================
        Custom cache, done with the caching framework
    =========================================================================== */
    if (empty($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news_category'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news_category'] = [];
    }
    // Define string frontend as default frontend, this must be set with TYPO3 4.5 and below
    // and overrides the default variable frontend of 4.6
    if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news_category']['frontend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news_category']['frontend'] = \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class;
    }

    /* ===========================================================================
        Add TSconfig
    =========================================================================== */
    // For linkvalidator
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('linkvalidator')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('@import \'EXT:news/Configuration/TSconfig/Page/mod.linkvalidator.tsconfig\'');
    }
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('guide')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('@import \'EXT:news/Configuration/TSconfig/Tours/AdministrationModule.tsconfig\'');
    }

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
    @import \'EXT:news/Configuration/TSconfig/ContentElementWizard.tsconfig\'
    @import \'EXT:news/Configuration/TSconfig/Administration.tsconfig\'
    ');

    /* ===========================================================================
        Hooks
    =========================================================================== */
    // Register cache frontend for proxy class generation
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class,
        'groups' => [
            'all',
            'system',
        ],
        'options' => [
            'defaultLifetime' => 0,
        ],
    ];

    if (class_exists(\GeorgRinger\News\Utility\ClassLoader::class)) {
        \GeorgRinger\News\Utility\ClassLoader::registerAutoloader();
    }
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['ext:news'] =
        \GeorgRinger\News\Utility\ClassCacheManager::class . '->reBuild';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\GeorgRinger\News\Backend\FormDataProvider\NewsRowInitializeNew::class] = [
        'depends' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew::class,
        ],
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['realurlAliasNewsSlug'] = \GeorgRinger\News\Updates\RealurlAliasNewsSlugUpdater::class; // Recommended before 'newsSlug'
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['newsSlug'] = \GeorgRinger\News\Updates\NewsSlugUpdater::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['sysCategorySlugs'] = \GeorgRinger\News\Updates\PopulateCategorySlugs::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['txNewsTagSlugs'] = \GeorgRinger\News\Updates\PopulateTagSlugs::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['txNewsRelatedLinkIntegerDefault'] = \GeorgRinger\News\Updates\RelatedLinkIntegerDefault::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['txNewsTitleFieldDefault'] = \GeorgRinger\News\Updates\TitleFieldDefault::class;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(trim('
    config.pageTitleProviders {
        news {
            provider = GeorgRinger\News\Seo\NewsTitleProvider
            before = altPageTitle,record,seo
        }
    }
'));

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('seo')) {
        $GLOBALS['TYPO3_CONF_VARS']['FE']['additionalCanonicalizedUrlParameters'] = array_merge(
            $GLOBALS['TYPO3_CONF_VARS']['FE']['additionalCanonicalizedUrlParameters'] ?? [],
            [
                'tx_news_pi1[news]',
                'tx_news_pi1[overwriteDemand][tags]',
                'tx_news_pi1[overwriteDemand][categories]',
                'tx_news_pi1[overwriteDemand][year]',
                'tx_news_pi1[overwriteDemand][month]',
                'tx_news_pi1[overwriteDemand][day]',
            ]
        );
    }
};

$boot();
unset($boot);
