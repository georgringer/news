<?php
defined('TYPO3_MODE') or die();

$boot = function ($packageKey) {
	// Extension manager configuration
	$configuration = \GeorgRinger\News\Utility\EmConfiguration::getSettings();

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		'GeorgRinger.news',
		'Pi1',
		array(
			'News' => 'list,detail,dateMenu,searchForm,searchResult',
			'Category' => 'list',
			'Tag' => 'list',
		),
		array(
			'News' => 'searchForm,searchResult',
		)
	);

	// Page module hook
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['news' . '_pi1']['news'] =
		'GeorgRinger\\News\\Hooks\\PageLayoutView->getExtensionSummary';

	// Preview of news records
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['news'] =
		'GeorgRinger\\News\\Hooks\\DataHandler';

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['news_clearcache'] =
		'GeorgRinger\\News\\Hooks\\DataHandler->clearCachePostProc';

	// Edit restriction for news records
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['news'] =
		'GeorgRinger\\News\\Hooks\\DataHandler';

	// FormEngine: Rendering of fields
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass']['news'] =
		'GeorgRinger\\News\\Hooks\\FormEngine';

	// FormEngine: Rendering of the whole FormEngine
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass']['news'] =
		'GeorgRinger\\News\\Hooks\\FormEngine';

	// Modify flexform values
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass']['news'] =
		'GeorgRinger\\News\\Hooks\\BackendUtility';

	// Inline records hook
	if ($configuration->getUseFal()) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms_inline.php']['tceformsInlineHook']['news'] =
			'GeorgRinger\\News\\Hooks\\InlineElementHook';
	}

	/* ===========================================================================
		Custom cache, done with the caching framework
	=========================================================================== */
	if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_news_category'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_news_category'] = array();
	}
	// Define string frontend as default frontend, this must be set with TYPO3 4.5 and below
	// and overrides the default variable frontend of 4.6
	if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_news_category']['frontend'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_news_category']['frontend'] = 'TYPO3\CMS\Core\Cache\Frontend\StringFrontend';
	}


	/* ===========================================================================
		Add soft reference parser
	=========================================================================== */
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['softRefParser']['news_externalurl'] = 'GeorgRinger\\News\\Database\\SoftReferenceIndex';

	/* ===========================================================================
		Add TSconfig
	=========================================================================== */
		// For linkvalidator
	if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('linkvalidator')) {
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:news/Configuration/TSconfig/Page/mod.linkvalidator.txt">');
	}
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:news/Configuration/TSconfig/ContentElementWizard.txt">');

	/* ===========================================================================
		Hooks
	=========================================================================== */
	if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration']['news'] =
			'GeorgRinger\\News\\Hooks\\RealUrlAutoConfiguration->addNewsConfig';
	}

	/* ===========================================================================
		Update scripts
	=========================================================================== */
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['news_fal'] = 'GeorgRinger\\News\\Updates\\FalUpdateWizard';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['news_mm'] = 'GeorgRinger\\News\\Updates\\TtContentRelation';


	// Register cache frontend for proxy class generation
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news'] = array(
		'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\PhpFrontend',
		'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\FileBackend',
		'groups' => array(
			'all',
			'system',
		),
		'options' => array(
			'defaultLifetime' => 0,
		),
	);
	\GeorgRinger\News\Utility\ClassLoader::registerAutoloader();
};

$boot($_EXTKEY);
unset($boot);