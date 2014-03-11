<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

	// Extension manager configuration
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news') . 'Classes/Utility/EmConfiguration.php');
$configuration = Tx_News_Utility_EmConfiguration::getSettings();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$_EXTKEY,
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
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info'][$_EXTKEY . '_pi1'][$_EXTKEY] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/CmsLayout.php:Tx_News_Hooks_CmsLayout->getExtensionSummary';

	// Preview of news records
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/Tcemain.php:Tx_News_Hooks_Tcemain';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][$_EXTKEY . '_classcache'] =
	'EXT:' . $_EXTKEY . '/Classes/Cache/ClassCacheBuilder.php:Tx_News_Cache_ClassCacheBuilder->build';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][$_EXTKEY . '_clearcache'] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/Tcemain.php:Tx_News_Hooks_Tcemain->clearCachePostProc';


	// Tceforms: Rendering of fields
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/Tceforms.php:Tx_News_Hooks_Tceforms';

	// Modify flexform values
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/T3libBefunc.php:Tx_News_Hooks_T3libBefunc';

	// Inline records hook
if ($configuration->getUseFal()) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms_inline.php']['tceformsInlineHook'][$_EXTKEY] =
		'EXT:' . $_EXTKEY . '/Classes/Hooks/InlineElementHook.php:Tx_News_InlineElementHook';
}

/* ===========================================================================
	Custom cache, done with the caching framework
=========================================================================== */
$cachingTableName = 'news_categorycache';
if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName])) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName] = array();
}
// Define string frontend as default frontend, this must be set with TYPO3 4.5 and below
// and overrides the default variable frontend of 4.6
if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['frontend'])) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['frontend'] = 'TYPO3\CMS\Core\Cache\Frontend\StringFrontend';
}


	// Class cache
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['class_cache'])) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['class_cache'] = array(
		'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\FileBackend',
		'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\PhpFrontend',
	);
}

/* ===========================================================================
	Add soft reference parser
=========================================================================== */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['softRefParser']['news_externalurl'] = 'EXT:' . $_EXTKEY . '/Classes/Database/SoftReferenceIndex.php:&Tx_News_Database_SoftReferenceIndex';

/* ===========================================================================
	Add TSconfig
=========================================================================== */
	// For linkvalidator
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('linkvalidator')) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/TSconfig/Page/mod.linkvalidator.txt">');
}

/* ===========================================================================
	Hooks
=========================================================================== */
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$_EXTKEY] =
		'EXT:' . $_EXTKEY . '/Classes/Hooks/RealUrlAutoConfiguration.php:Tx_News_Hooks_RealUrlAutoConfiguration->addNewsConfig';
}
