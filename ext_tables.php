<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

	// Extension manager configuration
$configuration = Tx_News_Utility_EmConfiguration::getSettings();
	// Alternative labels for news & category records
\TYPO3\CMS\Core\Utility\GeneralUtility::requireOnce(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Hooks/Labels.php');
	// Add additional media types like DAM
\TYPO3\CMS\Core\Utility\GeneralUtility::requireOnce(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Hooks/ItemsProcFunc.php');
	// CSH - context sensitive help
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
		'tx_news_domain_model_news', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_news.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
		'tx_news_domain_model_category', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_category.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
		'tx_news_domain_model_media', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_media.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
		'tx_news_domain_model_file', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_file.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
		'tx_news_domain_model_link', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_link.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
		'tx_news_domain_model_tag', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_tag.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
		'tt_content.pi_flexform.news_pi1.list', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_flexforms.xml');

t3lib_extMgm::allowTableOnStandardPages('tx_news_domain_model_news');
t3lib_extMgm::allowTableOnStandardPages('tx_news_domain_model_category');
t3lib_extMgm::allowTableOnStandardPages('tx_news_domain_model_media');
t3lib_extMgm::allowTableOnStandardPages('tx_news_domain_model_file');
t3lib_extMgm::allowTableOnStandardPages('tx_news_domain_model_link');
t3lib_extMgm::allowTableOnStandardPages('tx_news_domain_model_tag');


/***************
 * Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi1',
		'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_be.xml:pi1_title'
);


$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_news.xml');


/***************
 * Default TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'News');

/***************
 * Wizard pi1
 */
if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses'][$pluginSignature . '_wizicon'] =
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Resources/Private/Php/class.' . $_EXTKEY . '_wizicon.php';
}

/***************
 * News icon in page tree
 */
if (TYPO3_MODE == 'BE') {
	unset($ICON_TYPES['news']);
	\TYPO3\CMS\Backend\Sprite\SpriteManager::addTcaTypeIcon('pages', 'contains-news', '../typo3conf/ext/news/Resources/Public/Icons/folder.gif');

	$addNewsToModuleSelection = TRUE;
	foreach ($GLOBALS['TCA']['pages']['columns']['module']['config']['items'] as $item) {
		if ($item[1] === 'news') {
			$addNewsToModuleSelection = FALSE;
			continue;
		}
	}
	if ($addNewsToModuleSelection) {
		$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = array(
			0 => 'LLL:EXT:news/Resources/Private/Language/locallang_be.xml:news-folder',
			1 => 'news',
			2 => '../typo3conf/ext/news/Resources/Public/Icons/folder.gif'
		);
	}
}


/***************
 * Show news table in page module
 */
if ($configuration->getPageModuleFieldsNews()) {
	$addTableItems = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(';', $configuration->getPageModuleFieldsNews(), TRUE);
	foreach ($addTableItems as $item) {
		$split = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('=', $item, TRUE);
		$fList = $fTitle = '';
		if (count($split) == 2) {
			$fTitle = $split[0];
			$fList = $split[1];
		} else {
			$fList = $split[0];
		}
		$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_news_domain_model_news'][] = array(
			'MENU' => $fTitle,
			'fList' => $fList,
			'icon' => TRUE,
		);
	}

}

if ($configuration->getPageModuleFieldsCategory()) {
	$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_news_domain_model_category'][0] = array(
		'fList' => htmlspecialchars($configuration->getPageModuleFieldsCategory()),
		'icon' => TRUE
	);
}


	// extend user settings
$GLOBALS['TYPO3_USER_SETTINGS']['columns']['newsoverlay'] = array(
	'label'			=> 'LLL:EXT:news/Resources/Private/Language/locallang_be.xml:usersettings.overlay',
	'type'			=> 'select',
	'itemsProcFunc'	=> 'Tx_News_Hooks_ItemsProcFunc->user_categoryOverlay',
);
$GLOBALS['TYPO3_USER_SETTINGS']['showitem'] .= ',
	--div--;LLL:EXT:news/Resources/Private/Language/locallang_be.xml:pi1_title,newsoverlay';

// Add tables to livesearch (e.g. "#news:fo" or "#newscat:fo")
$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['news'] = 'tx_news_domain_model_news';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['newscat'] = 'tx_news_domain_model_category';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['newstag'] = 'tx_news_domain_model_tag';

/* ===========================================================================
 	Register BE-Modules
=========================================================================== */
if (TYPO3_MODE === 'BE' && $configuration->getShowImporter()) {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		$_EXTKEY,
		'web',
		'tx_news_m1',
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

		// show tt_news importer only if tt_news is installed
	if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_news')) {
		Tx_News_Utility_ImportJob::register(
			'Tx_News_Jobs_TTNewsNewsImportJob',
			'LLL:EXT:news/Resources/Private/Language/locallang_be.xml:ttnews_importer_title',
			'');
		Tx_News_Utility_ImportJob::register(
			'Tx_News_Jobs_TTNewsCategoryImportJob',
			'LLL:EXT:news/Resources/Private/Language/locallang_be.xml:ttnewscategory_importer_title',
			'');
	}

	if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('t3blog')) {
		Tx_News_Utility_ImportJob::register(
			'Tx_News_Jobs_T3BlogNewsImportJob',
			'LLL:EXT:news/Resources/Private/Language/locallang_be.xml:t3blog_importer_title',
			'');
		Tx_News_Utility_ImportJob::register(
			'Tx_News_Jobs_T3BlogCategoryImportJob',
			'LLL:EXT:news/Resources/Private/Language/locallang_be.xml:t3blogcategory_importer_title',
			'');
	}
}


/* ===========================================================================
 	Register BE-Module for Administration
=========================================================================== */
if (TYPO3_MODE === 'BE' && $configuration->getShowAdministrationModule()) {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		$_EXTKEY,
		'web',
		'tx_news_m2',
		'',
		array(
			'Administration' => 'index,newNews,newCategory,newTag,newsPidListing',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/folder.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_modadministration.xml',
		)
	);
}

/* ===========================================================================
 	Ajax call to save tags
=========================================================================== */
if (TYPO3_MODE == 'BE') {
	$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['News::createTag'] = 'typo3conf/ext/news/Classes/Hooks/SuggestReceiverCall.php:Tx_News_Hooks_SuggestReceiverCall->createTag';
}

/* ===========================================================================
 	Default configuration
=========================================================================== */
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByCategory'] = 'uid,title,tstamp,sorting';
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews'] = 'tstamp,datetime,crdate,title';
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByTag'] = 'tstamp,crdate,title';
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['list'] = $configuration->getRemoveListActionFromFlexforms();

/* ===========================================================================
 	Extend be_user/be_groups table by a category restriction
=========================================================================== */
if (version_compare(TYPO3_branch, '6.0', '>=') || \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tcatreeprovider')) {
	$tempColumns = array(
		'tx_news_categorymounts' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:be_user.tx_news_categorymounts',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_news_domain_model_category',
				'foreign_table_where' => ' AND (tx_news_domain_model_category.sys_language_uid = 0 OR tx_news_domain_model_category.l10n_parent = 0) ORDER BY tx_news_domain_model_category.sorting',
				'renderMode' => 'tree',
				'subType' => 'db',
				'treeConfig' => array(
					'parentField' => 'parentcategory',
					'appearance' => array(
						'expandAll' => TRUE,
						'showHeader' => FALSE,
						'maxLevels' => 99,
					),
				),
				'size' => 10,
				'autoSizeMax' => 20,
				'minitems' => 0,
				'maxitems' => 99
			)
		)
	);

	if (version_compare(TYPO3_branch, '6.1', '<')) {
		\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('be_groups');
	}
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_groups', $tempColumns, 1);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('be_groups', 'tx_news_categorymounts;;;;1-1-1');

	if (version_compare(TYPO3_branch, '6.1', '<')) {
		\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('be_users');
	}
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_users', $tempColumns, 1);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('be_users', 'tx_news_categorymounts;;;;1-1-1');
}
