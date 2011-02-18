<?php
/**
 * @version $Id$
 */

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'News' => 'list, detail, search, searchResult, dateMenu',
	),
	array(
		'News' => 'search, searchResult',
	)
);

	// Page module hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info'][$_EXTKEY . '_pi1'][$_EXTKEY] =
	'EXT:' . $_EXTKEY . '/Resources/Private/Backend/class.tx_' . $_EXTKEY . '_cms_layout.php:tx_' . $_EXTKEY . '_cms_layout->getExtensionSummary';

	// Preview of news2 records
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY. '/Classes/Hooks/Tcemain.php:tx_News2_Hooks_Tcemain';

	// Modify flexform values
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY. '/Classes/Hooks/T3libBefunc.php:tx_News2_Hooks_T3libBefunc';


?>