<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$boot = function($packageKey) {
	// Extension manager configuration
	$configuration = \GeorgRinger\News\Utility\EmConfiguration::getSettings();

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		$packageKey,
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
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info'][$packageKey . '_pi1'][$packageKey] =
		'GeorgRinger\\News\\Hooks\\CmsLayout->getExtensionSummary';

	// Preview of news records
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$packageKey] =
		'GeorgRinger\\News\\Hooks\\Tcemain';

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][$packageKey . '_clearcache'] =
		'GeorgRinger\\News\\Hooks\\Tcemain->clearCachePostProc';

	// Edit restriction for news records
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][$packageKey] =
		'GeorgRinger\\News\\Hooks\\Tcemain';

	// Tceforms: Rendering of fields
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][$packageKey] =
		'GeorgRinger\\News\\Hooks\\Tceforms';

	// Tceforms: Rendering of the whole Tceform
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][] =
		'GeorgRinger\\News\\Hooks\\Tceforms';

	// Modify flexform values
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][$packageKey] =
		'GeorgRinger\\News\\Hooks\\T3libBefunc';

	// Inline records hook
	if ($configuration->getUseFal()) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms_inline.php']['tceformsInlineHook'][$packageKey] =
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
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $packageKey . '/Configuration/TSconfig/Page/mod.linkvalidator.txt">');
	}

	/* ===========================================================================
		Hooks
	=========================================================================== */
	if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$packageKey] =
			'GeorgRinger\\News\\Hooks\\RealUrlAutoConfiguration->addNewsConfig';
	}

	/* ===========================================================================
		Update scripts
	=========================================================================== */
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['news_fal'] = 'GeorgRinger\\News\\Updates\\FalUpdateWizard';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['news_mm'] = 'GeorgRinger\\News\\Updates\\TtContentRelation';

};

$boot($_EXTKEY);
unset($boot);