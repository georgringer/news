<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$boot = function($packageKey) {
	// Extension manager configuration
	$configuration = Tx_News_Utility_EmConfiguration::getSettings();

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
		'Tx_News_Hooks_CmsLayout->getExtensionSummary';

	// Preview of news records
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$packageKey] =
		'Tx_News_Hooks_Tcemain';

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][$packageKey . '_clearcache'] =
		'Tx_News_Hooks_Tcemain->clearCachePostProc';


	// Tceforms: Rendering of fields
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][$packageKey] =
		'Tx_News_Hooks_Tceforms';

	// Modify flexform values
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][$packageKey] =
		'Tx_News_Hooks_T3libBefunc';

	// Inline records hook
	if ($configuration->getUseFal()) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms_inline.php']['tceformsInlineHook'][$packageKey] =
			'Tx_News_Hooks_InlineElementHook';
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
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['softRefParser']['news_externalurl'] = '&Tx_News_Database_SoftReferenceIndex';

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
			'Tx_News_Hooks_RealUrlAutoConfiguration->addNewsConfig';
	}
};

$boot($_EXTKEY);
unset($boot);