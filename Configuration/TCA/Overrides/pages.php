<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die;

// Override news icon
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    'label' => 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:news-folder',
    'value' => 'news',
    'icon' => 'apps-pagetree-folder-contains-news',
];
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    'label' => 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:news-page',
    'value' => 'newsplugins',
    'icon' => 'apps-pagetree-page-contains-news',
];
$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-newsplugins'] = 'apps-pagetree-page-contains-news';

ExtensionManagementUtility::registerPageTSConfigFile(
    'news',
    'Configuration/TSconfig/Page/news_only.tsconfig',
    'EXT:news :: Restrict pages to news records'
);
