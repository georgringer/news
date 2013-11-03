<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

	// Extension manager configuration
require_once(t3lib_extMgm::extPath('news') . 'Classes/Utility/EmConfiguration.php');
$configuration = Tx_News_Utility_EmConfiguration::getSettings();

Tx_Extbase_Utility_Extension::configurePlugin(
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
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['frontend'] = 't3lib_cache_frontend_StringFrontend';
}

if (Tx_News_Utility_Compatibility::convertVersionNumberToInteger(TYPO3_version) < '4006000') {
	// Define database backend as backend for 4.5 and below (default in 4.6)
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['backend'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['backend'] = 't3lib_cache_backend_DbBackend';
	}
	// Define data and tags table for 4.5 and below (obsolete in 4.6)
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['options'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['options'] = array();
	}
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['options']['cacheTable'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['options']['cacheTable'] = 'cf_news_categorycache';
	}
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['options']['tagsTable'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['options']['tagsTable'] = 'cf_news_categorycache';
	}
}

	// Class cache
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['class_cache'])) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['class_cache'] = array(
		'backend' => 't3lib_cache_backend_FileBackend',
		'frontend' => 't3lib_cache_frontend_PhpFrontend',
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
if (t3lib_extMgm::isLoaded('linkvalidator')) {
	t3lib_extMgm::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/TSconfig/Page/mod.linkvalidator.txt">');
}

/* ===========================================================================
	Hooks
=========================================================================== */
if (t3lib_extMgm::isLoaded('realurl')) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$_EXTKEY] =
		'EXT:' . $_EXTKEY . '/Classes/Hooks/RealUrlAutoConfiguration.php:Tx_News_Hooks_RealUrlAutoConfiguration->addNewsConfig';
}
