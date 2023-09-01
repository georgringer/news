<?php

defined('TYPO3') or die;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript', 'News');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Sitemap', 'News Sitemap (deprecated)');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/SeoSitemap', 'News Sitemap (tx_seo)');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb', 'News Styles Twitter Bootstrap');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb5', 'News Styles Twitter Bootstrap V5');
