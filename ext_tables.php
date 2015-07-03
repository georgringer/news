<?php
defined('TYPO3_MODE') or die();

$boot = function($packageKey) {
	// The following calls are targeted for BE but might be needed in FE editing

	// CSH - context sensitive help
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_news_domain_model_news', 'EXT:news/Resources/Private/Language/locallang_csh_news.xlf');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_news_domain_model_media', 'EXT:news/Resources/Private/Language/locallang_csh_media.xlf');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_news_domain_model_file', 'EXT:news/Resources/Private/Language/locallang_csh_file.xlf');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_news_domain_model_link', 'EXT:news/Resources/Private/Language/locallang_csh_link.xlf');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_news_domain_model_tag', 'EXT:news/Resources/Private/Language/locallang_csh_tag.xlf');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tt_content.pi_flexform.news_pi1.list', 'EXT:news/Resources/Private/Language/locallang_csh_flexforms.xlf');

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_news');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_media');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_file');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_link');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_tag');

	// Extension manager configuration
	$configuration = \GeorgRinger\News\Utility\EmConfiguration::getSettings();

	/***************
	 * News icon in page tree
	 */
	unset($GLOBALS['ICON_TYPES']['news']);
	\TYPO3\CMS\Backend\Sprite\SpriteManager::addTcaTypeIcon('pages', 'contains-news', '../typo3conf/ext/news/Resources/Public/Icons/folder.gif');

	if (TYPO3_MODE === 'BE') {

		$addNewsToModuleSelection = TRUE;
		foreach ($GLOBALS['TCA']['pages']['columns']['module']['config']['items'] as $item) {
			if ($item[1] === 'news') {
				$addNewsToModuleSelection = FALSE;
				continue;
			}
		}
		if ($addNewsToModuleSelection) {
			$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = array(
				0 => 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:news-folder',
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
			'label'			=> 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:usersettings.overlay',
			'type'			=> 'select',
			'itemsProcFunc'	=> 'GeorgRinger\\News\\Hooks\\ItemsProcFunc->user_categoryOverlay',
		);
		$GLOBALS['TYPO3_USER_SETTINGS']['showitem'] .= ',
			--div--;LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:pi1_title,newsoverlay';

		// Add tables to livesearch (e.g. "#news:fo" or "#newscat:fo")
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['news'] = 'tx_news_domain_model_news';
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['newstag'] = 'tx_news_domain_model_tag';


		/* ===========================================================================
			Register BE-Modules
		=========================================================================== */
		if ($configuration->getShowImporter()) {
			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
				'GeorgRinger.news',
				'web',
				'tx_news_m1',
				'',
				array(
					'Import' => 'index, runJob, jobInfo',
				),
				array(
					'access' => 'user,group',
					'icon' => 'EXT:news/Resources/Public/Icons/' .
						(\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.0') ? 'module_import.png' : 'import_module.gif'),
					'labels' => 'LLL:EXT:news/Resources/Private/Language/locallang_mod.xlf',
				)
			);
		}


		/* ===========================================================================
			Register BE-Module for Administration
		=========================================================================== */
		if ($configuration->getShowAdministrationModule()) {
			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
				'GeorgRinger.news',
				'web',
				'tx_news_m2',
				'',
				array(
					'Administration' => 'index,newNews,newCategory,newTag,newsPidListing',
				),
				array(
					'access' => 'user,group',
					'icon' => 'EXT:news/Resources/Public/Icons/' .
						(\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.0') ? 'module_administration.png' : 'folder.gif'),
					'labels' => 'LLL:EXT:news/Resources/Private/Language/locallang_modadministration.xlf',
				)
			);
		}

		/* ===========================================================================
			Ajax call to save tags
		=========================================================================== */
		if (\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.3')) {
			$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['News::createTag'] = array(
				'callbackMethod' => 'GeorgRinger\\News\\Hooks\\SuggestReceiverCall->createTag',
				'csrfTokenCheck' => FALSE
			);
		} else {
			$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['News::createTag'] = 'typo3conf/ext/news/Classes/Hooks/SuggestReceiverCall.php:GeorgRinger\\News\\Hooks\\SuggestReceiverCall->createTag';
		}
	}

	/* ===========================================================================
		Default configuration
	=========================================================================== */
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByCategory'] = 'uid,title,tstamp,sorting';
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews'] = 'tstamp,datetime,crdate,title' . ($configuration->getManualSorting() ? ',sorting' : '');
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByTag'] = 'tstamp,crdate,title';
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['list'] = $configuration->getRemoveListActionFromFlexforms();
};

$boot($_EXTKEY);
unset($boot);