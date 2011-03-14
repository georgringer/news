<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

	// Extension manager configuration
$configurationArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
	// Alternative labels for news & category records
t3lib_div::requireOnce(t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Hooks/Labels.php');
	// Add additional media types like DAM
t3lib_div::requireOnce(t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Hooks/ItemsProcFunc.php');
	// CSH - context sensitive help
t3lib_extMgm::addLLrefForTCAdescr(
		'tx_news2_domain_model_news', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_news.xml');
t3lib_extMgm::addLLrefForTCAdescr(
		'tx_news2_domain_model_category', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_category.xml');
t3lib_extMgm::addLLrefForTCAdescr(
		'tx_news2_domain_model_media', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_media.xml');
t3lib_extMgm::addLLrefForTCAdescr(
		'tx_news2_domain_model_file', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_file.xml');
t3lib_extMgm::addLLrefForTCAdescr(
		'tx_news2_domain_model_link', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_link.xml');
t3lib_extMgm::addLLrefForTCAdescr(
		'tt_content.pi_flexform.news2_pi1.list', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_flexforms.xml');

t3lib_extMgm::allowTableOnStandardPages('tx_news2_domain_model_news');

$TCA['tx_news2_domain_model_news'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news',
		'label'     => 'title',
		'label_alt' => 'categories',
		'label_alt_force' => 1,
		'label_userFunc' => 'tx_News2_Hooks_Labels->getUserLabelNews',
		'prependAtCopy' => $configurationArray['prependAtCopy'] ? 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy' : '',
		'hideAtCopy' => TRUE,
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => TRUE,
		'origUid' => 't3_origuid',
		'editlock' => 'editlock',
		'type' => 'type',
		'typeicon_column' => 'type',
		'typeicons' => array (
			'1' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/news2_domain_model_news_article.gif',
			'2' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/news2_domain_model_news_external.gif',
		),
		'dividers2tabs' => TRUE,
		'useColumnsForDefaultValues' => 'type',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',
		'sortby' => ($configurationArray['manualSorting'] == 1 ? 'sorting' : ''),
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca/news.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/news2_domain_model_news.gif',
	),
);

t3lib_extMgm::allowTableOnStandardPages('tx_news2_domain_model_category');

$TCA['tx_news2_domain_model_category'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_category',
		'label'     => 'title',
		'label_alt' => 'parentcategory,sys_language_uid',
		'label_alt_force' => 1,
		'label_userFunc' => 'tx_News2_Hooks_Labels->getUserLabelCategory',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca/category.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/news2_domain_model_category.gif',
		'treeParentField' => 'parentcategory',
	),
);

t3lib_extMgm::allowTableOnStandardPages('tx_news2_domain_model_media');

$TCA['tx_news2_domain_model_media'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media',
		'label'     => 'caption',
		'label_alt' => 'type, showinpreview',
		'label_alt_force' => 1,
		'label_userFunc' => 'tx_News2_Hooks_Labels->getUserLabelMedia',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'dividers2tabs' => TRUE,
		'default_sortby' => 'ORDER BY sorting',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca/media.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/news2_domain_model_media.gif',
		'hideTable'			=> (boolean)$configurationArray['hideMediaTable']
	),
);

t3lib_extMgm::allowTableOnStandardPages('tx_news2_domain_model_media');

$TCA['tx_news2_domain_model_file'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_file',
		'label'     => 'title',
		'label_alt' => 'file',
		'label_alt_force' => 1,
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'dividers2tabs' => TRUE,
		'default_sortby' => 'ORDER BY sorting',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca/file.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/news2_domain_model_file.gif',
		'hideTable'			=> (boolean)$configurationArray['hideFileTable']
	),
);

t3lib_extMgm::allowTableOnStandardPages('tx_news2_domain_model_file');

$TCA['tx_news2_domain_model_link'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_link',
		'label'     => 'title',
		'label_alt' => 'uri',
		'label_alt_force' => 1,
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'dividers2tabs' => TRUE,
		'default_sortby' => 'ORDER BY sorting',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca/link.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/news2_domain_model_link.gif',
		'hideTable'			=> (boolean)$configurationArray['hideFileTable']
	),
);

t3lib_extMgm::allowTableOnStandardPages('tx_news2_domain_model_link');

/***************
 * Plugin
 */
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
		'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_be.xml:pi1_title'
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
		t3lib_extMgm::extPath($_EXTKEY) . 'Resources/Private/Php/class.' . $_EXTKEY . '_wizicon.php';
}

/***************
 * News icon in page tree
 */
if (TYPO3_MODE=='BE') {
	unset($ICON_TYPES['news']);
	t3lib_SpriteManager::addTcaTypeIcon('pages', 'contains-news', '../typo3conf/ext/news2/Resources/Public/Icons/folder.gif');
}


/***************
 * Show news table in page module
 */
if (!empty($configurationArray['pageModuleFieldsNews'])) {
	$addTableItems = t3lib_div::trimExplode(';', $configurationArray['pageModuleFieldsNews'], TRUE);
	foreach ($addTableItems as $item) {
		$split = t3lib_div::trimExplode('=', $item, TRUE);
		$fList = $fTitle = '';
		if (count($split) == 2) {
			$fTitle = $split[0];
			$fList = $split[1];
		} else {
			$fList = $split[0];
		}
		$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_news2_domain_model_news'][] = array(
			'MENU' => $fTitle,
			'fList' => $fList,
			'icon' => TRUE,
		);
	}

}

if (!empty($configurationArray['pageModuleFieldsCategory'])) {
	$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_news2_domain_model_category'][0] = array(
		'fList' => htmlspecialchars($configurationArray['pageModuleFieldsCategory']),
		'icon' => TRUE
	);
}


	// extend user settings
$GLOBALS['TYPO3_USER_SETTINGS']['columns']['news2overlay'] = array(
	'label'			=> 'LLL:EXT:news2/Resources/Private/Language/locallang_be.xml:usersettings.overlay',
	'type'			=> 'select',
	'itemsProcFunc'	=> 'tx_News2_Hooks_ItemsProcFunc->user_categoryOverlay',
);
$GLOBALS['TYPO3_USER_SETTINGS']['showitem'] .= ',
	--div--;LLL:EXT:news2/Resources/Private/Language/locallang_be.xml:pi1_title,news2overlay';



/* ===========================================================================
 	Register BE-Modules
=========================================================================== */
if (TYPO3_MODE == 'BE') {
	Tx_Extbase_Utility_Extension::registerModule(
		$_EXTKEY,
		'web',
		'tx_news2_m1',
		'',
		array(
			'Import' => 'index, runJob, jobInfo',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/import_module.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml',
		)
	);

	Tx_News2_Utility_ImportJob::register('Tx_News2_Jobs_TTNewsNewsImportJob', 'Import tt_news news records', '');
	Tx_News2_Utility_ImportJob::register('Tx_News2_Jobs_TTNewsCategoryImportJob', 'Import tt_news category records', '');
}

/* ===========================================================================
 	Default configuration
=========================================================================== */
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['orderByCategory'] = 'uid,title,tstamp,sorting';
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['orderByNews'] = 'tstamp,datetime,crdate,title';

?>