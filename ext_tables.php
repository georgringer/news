<?php

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$boot = static function (): void {
    $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
    if ($versionInformation->getMajorVersion() < 12) {
        // CSH - context sensitive help
        foreach (['news', 'tag', 'link'] as $table) {
            // @extensionScannerIgnoreLine
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_news_domain_model_' . $table);

            // @extensionScannerIgnoreLine
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
                'tx_news_domain_model_' . $table,
                'EXT:news/Resources/Private/Language/locallang_csh_' . $table . '.xlf'
            );
        }

        // @extensionScannerIgnoreLine
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tt_content.pi_flexform.news_pi1.list',
            'EXT:news/Resources/Private/Language/locallang_csh_flexforms.xlf'
        );

        // @extensionScannerIgnoreLine
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'sys_file_reference',
            'EXT:news/Resources/Private/Language/locallang_csh_sys_file_reference.xlf'
        );
    }

    $configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\GeorgRinger\News\Domain\Model\Dto\EmConfiguration::class);

    // Extend user settings
    $GLOBALS['TYPO3_USER_SETTINGS']['columns']['newsoverlay'] = [
        'label' => 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:usersettings.overlay',
        'type' => 'select',
        'itemsProcFunc' => \GeorgRinger\News\Hooks\ItemsProcFunc::class . '->user_categoryOverlay',
    ];
    if (!isset($GLOBALS['TYPO3_USER_SETTINGS']['showitem'])) {
        $GLOBALS['TYPO3_USER_SETTINGS']['showitem'] = '';
    }
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToUserSettings('--div--;LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:pi1_title,newsoverlay');

    // Add tables to livesearch (e.g. "#news:fo" or "#newscat:fo")
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['news'] = 'tx_news_domain_model_news';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['newstag'] = 'tx_news_domain_model_tag';

    /* ===========================================================================
        Register BE-Module for Administration
    =========================================================================== */
    if ($configuration->getShowAdministrationModule()) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'News',
            'web',
            'administration',
            '',
            [\GeorgRinger\News\Controller\AdministrationController::class => 'index,newNews,newCategory,newTag,newsPidListing,donate'],
            [
                'access' => 'user,group',
                'icon' => 'EXT:news/Resources/Public/Icons/module_administration.svg',
                'labels' => 'LLL:EXT:news/Resources/Private/Language/locallang_modadministration.xlf',
                'navigationComponentId' => $configuration->getHidePageTreeForAdministrationModule() ? '' : 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
                'inheritNavigationComponentFromMainModule' => false,
                'path' => '/module/web/NewsAdministration/',
            ]
        );
    }

    /* ===========================================================================
        Default configuration
    =========================================================================== */
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByCategory'] = 'uid,title,tstamp,sorting';
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews'] = 'tstamp,datetime,crdate,title' . ($configuration->getManualSorting() ? ',sorting' : '');
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByTag'] = 'tstamp,crdate,title';
};

$boot();
unset($boot);
