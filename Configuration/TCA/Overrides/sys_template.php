<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die;

ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript', 'News');
ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/SeoSitemap', 'News Sitemap (tx_seo)');
ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb', 'News Styles Twitter Bootstrap');
ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb5', 'News Styles Twitter Bootstrap V5');
