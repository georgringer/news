<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$boot = function($packageKey) {
	// The following calls are targeted for BE but might be needed in FE editing

	// CSH - context sensitive help
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_news_domain_model_news', 'EXT:' . $packageKey . '/Resources/Private/Language/locallang_csh_news.xml');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_news_domain_model_media', 'EXT:' . $packageKey . '/Resources/Private/Language/locallang_csh_media.xml');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_news_domain_model_file', 'EXT:' . $packageKey . '/Resources/Private/Language/locallang_csh_file.xml');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_news_domain_model_link', 'EXT:' . $packageKey . '/Resources/Private/Language/locallang_csh_link.xml');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_news_domain_model_tag', 'EXT:' . $packageKey . '/Resources/Private/Language/locallang_csh_tag.xml');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tt_content.pi_flexform.news_pi1.list', 'EXT:' . $packageKey . '/Resources/Private/Language/locallang_csh_flexforms.xml');

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_news');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_media');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_file');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_link');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_tag');

	// Extension manager configuration
	$configuration = Tx_News_Utility_EmConfiguration::getSettings();

	/***************
	 * News icon in page tree
	 */
	unset($GLOBALS['ICON_TYPES']['news']);
	\TYPO3\CMS\Backend\Sprite\SpriteManager::addTcaTypeIcon('pages', 'contains-news', '../typo3conf/ext/news/Resources/Public/Icons/folder.gif');

	if (TYPO3_MODE == 'BE') {
		$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($packageKey);
		$pluginSignature = strtolower($extensionName) . '_pi1';

		/***************
		 * Wizard pi1
		 */
		$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses'][$pluginSignature . '_wizicon'] =
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($packageKey) . 'Resources/Private/Php/class.' . $packageKey . '_wizicon.php';

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

		/***************
		 * Show news table in page module
		 */
		if ($configuration->getPageModuleFieldsNews()) {
			$addTableItems = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(';', $configuration->getPageModuleFieldsNews(), TRUE);
			foreach ($addTableItems as $item) {
				$split = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('=', $item, TRUE);
				if (count($split) == 2) {
					$fTitle = $split[0];
					$fList = $split[1];
				} else {
					$fTitle = '';
					$fList = $split[0];
				}
				$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cms']['db_layout']['addTables']['tx_news_domain_model_news'][] = array(
					'MENU' => $fTitle,
					'fList' => $fList,
					'icon' => TRUE,
				);
			}
		}

		if ($configuration->getPageModuleFieldsCategory()) {
			$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cms']['db_layout']['addTables']['sys_category'][0] = array(
				'fList' => htmlspecialchars($configuration->getPageModuleFieldsCategory()),
				'icon' => TRUE
			);
		}

		// Extend user settings
		$GLOBALS['TYPO3_USER_SETTINGS']['columns']['newsoverlay'] = array(
			'label'			=> 'LLL:EXT:news/Resources/Private/Language/locallang_be.xml:usersettings.overlay',
			'type'			=> 'select',
			'itemsProcFunc'	=> 'Tx_News_Hooks_ItemsProcFunc->user_categoryOverlay',
		);
		$GLOBALS['TYPO3_USER_SETTINGS']['showitem'] .= ',
			--div--;LLL:EXT:news/Resources/Private/Language/locallang_be.xml:pi1_title,newsoverlay';

		// Add tables to livesearch (e.g. "#news:fo" or "#newscat:fo")
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['news'] = 'tx_news_domain_model_news';
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['newstag'] = 'tx_news_domain_model_tag';


		/* ===========================================================================
			Register BE-Modules
		=========================================================================== */
		if ($configuration->getShowImporter()) {
			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
				$packageKey,
				'web',
				'tx_news_m1',
				'',
				array(
					'Import' => 'index, runJob, jobInfo',
				),
				array(
					'access' => 'user,group',
					'icon'   => 'EXT:' . $packageKey . '/Resources/Public/Icons/import_module.gif',
					'labels' => 'LLL:EXT:' . $packageKey . '/Resources/Private/Language/locallang_mod.xml',
				)
			);

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
		if ($configuration->getShowAdministrationModule()) {
			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
				$packageKey,
				'web',
				'tx_news_m2',
				'',
				array(
					'Administration' => 'index,newNews,newCategory,newTag,newsPidListing',
				),
				array(
					'access' => 'user,group',
					'icon'   => 'EXT:' . $packageKey . '/Resources/Public/Icons/folder.gif',
					'labels' => 'LLL:EXT:' . $packageKey . '/Resources/Private/Language/locallang_modadministration.xml',
				)
			);
		}

		/* ===========================================================================
			Ajax call to save tags
		=========================================================================== */
		$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['News::createTag'] = 'typo3conf/ext/news/Classes/Hooks/SuggestReceiverCall.php:Tx_News_Hooks_SuggestReceiverCall->createTag';
	}

	/* ===========================================================================
		Default configuration
	=========================================================================== */
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByCategory'] = 'uid,title,tstamp,sorting';
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews'] = 'tstamp,datetime,crdate,title';
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByTag'] = 'tstamp,crdate,title';
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['list'] = $configuration->getRemoveListActionFromFlexforms();
};

$boot($_EXTKEY);
unset($boot);