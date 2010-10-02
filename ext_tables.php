<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

	// alternative labels for news & category records
t3lib_div::requireOnce(t3lib_extMgm::extPath($_EXTKEY) . 'Resources/Private/Backend/class.user_tx_news2_labelFunc.php');
	// category tree
t3lib_div::requireOnce(t3lib_extMgm::extPath($_EXTKEY) . 'Resources/Private/Backend/class.tx_news2_treeView.php');


t3lib_extMgm::allowTableOnStandardPages('tx_news2_domain_model_category');

$TCA['tx_news2_domain_model_category'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_category',
		'label'     => 'title',
		'label_alt' => 'parentcategory',
		'label_alt_force' => 1,
		'label_userFunc' => 'user_tx_news2_labelFunc->getUserLabelCategory',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca/tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_news2_domain_model_category.gif',
		'treeParentField' => 'parentcategory',
	),
);

t3lib_extMgm::allowTableOnStandardPages('tx_news2_domain_model_news');



$TCA['tx_news2_domain_model_news'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news',
		'label'     => 'title',
		'label_alt' => 'category',
		'label_alt_force' => 1,
		'label_userFunc' => 'user_tx_news2_labelFunc->getUserLabelNews',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',
		'typeicon_column' => 'type',
		'typeicons' => array (
			'1' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_news2_domain_model_news_article.gif',
			'2' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_news2_domain_model_news_external.gif',
		),
		'dividers2tabs' => TRUE,
		'useColumnsForDefaultValues' => 'type',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca/tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_news2_domain_model_news.gif',
	),
);

t3lib_extMgm::allowTableOnStandardPages('tx_news2_domain_model_media');

$TCA['tx_news2_domain_model_media'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media',
		'label'     => 'title',
		'label_alt' => 'type',
		'label_alt_force' => 1,
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',
		'dividers2tabs' => TRUE,
		'default_sortby' => 'ORDER BY sorting',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca/tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_news2_domain_model_media.gif',
	),
);

/***************
 * Plugin
 */
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
		'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pi1_title'
);


$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,recursive,select_key';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_news.xml');


/***************
 * Default TypoScript
 */
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'News v2');

/***************
 * Wizard pi1
 */
if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses'][$pluginSignature . '_wizicon'] =
		t3lib_extMgm::extPath($_EXTKEY) . 'Resources/Private/Backend/class.tx_' . $_EXTKEY . '_wizicon.php';
}
?>