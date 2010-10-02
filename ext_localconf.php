<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
    $_EXTKEY,
    'Pi1',
    array(
        'News' => 'index,list,latest,detail,search,searchResult,archiveMenu',
        'Category' => 'list',
    ),
	array(
        'News' => 'search,searchResult',
    )
);


	// Page module hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info'][$_EXTKEY . '_pi1'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Resources/Private/Backend/class.tx_' . $_EXTKEY . '_cms_layout.php:tx_' . $_EXTKEY . '_cms_layout->getExtensionSummary';

	// Hide media element as it is only used inside IRRE
t3lib_extMgm::addUserTSConfig('mod.web_list.hideTables := addToList(tx_news2_domain_model_media)');
?>