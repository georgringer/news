<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'News' => 'list,detail,searchForm,searchResult,dateMenu',
	),
	array(
		'News' => 'searchForm,searchResult',
	)
);

//	// Page module hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info'][$_EXTKEY . '_pi1'][$_EXTKEY] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/CmsLayout.php:tx_News2_Hooks_CmsLayout->getExtensionSummary';

	// Preview of news2 records
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY. '/Classes/Hooks/Tcemain.php:tx_News2_Hooks_Tcemain';

	// Modify flexform values
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY. '/Classes/Hooks/T3libBefunc.php:tx_News2_Hooks_T3libBefunc';

	// Dynamic content element
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY. '/Classes/Hooks/Tceforms.php:tx_News2_Hooks_Tceforms';

?>