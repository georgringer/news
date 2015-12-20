<?php
defined('TYPO3_MODE') or die();

/***************
 * Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'news',
    'Pi1',
    'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:pi1_title'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['news_pi1'] = 'recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['news_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('news_pi1',
    'FILE:EXT:news/Configuration/FlexForms/flexform_news.xml');

// TypoScript
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript', 'News');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Sitemap', 'News Sitemap');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb', 'News Styles Twitter Bootstrap');
