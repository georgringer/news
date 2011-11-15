<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'News' => 'list,detail,dateMenu,seachForm,searchResult',
	),
	array(
		'News' => 'seachForm,searchResult',
	)
);

	// Page module hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info'][$_EXTKEY . '_pi1'][$_EXTKEY] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/CmsLayout.php:Tx_News_Hooks_CmsLayout->getExtensionSummary';

	// Preview of news records
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY. '/Classes/Hooks/Tcemain.php:Tx_News_Hooks_Tcemain';

	// Modify flexform values
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY. '/Classes/Hooks/T3libBefunc.php:Tx_News_Hooks_T3libBefunc';


	// Hide HTML by default as it is a possible security risk
t3lib_extMgm::addPageTSConfig('TCEFORM.tx_news_domain_model_media.type.removeItems = 2');

/* ===========================================================================
	Custom cache, done with the caching framework
=========================================================================== */
if (FALSE) {
	$cachingTableName = 'news_categorycache';
	if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName] = array();
	}
	// Define string frontend as default frontend, this must be set with TYPO3 4.5 and below
	// and overrides the default variable frontend of 4.6
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['frontend'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations'][$cachingTableName]['frontend'] = 't3lib_cache_frontend_StringFrontend';
	}
}

?>