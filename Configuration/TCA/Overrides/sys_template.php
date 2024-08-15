<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die;

$typo3Version = GeneralUtility::makeInstance(Typo3Version::class);

// Check if the current major version is 12
if ($typo3Version->getMajorVersion() <= 12) {
    ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript', 'News');
    ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Sitemap', 'News Sitemap (deprecated)');
    ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/SeoSitemap', 'News Sitemap (tx_seo)');
    ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb', 'News Styles Twitter Bootstrap');
    ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb5', 'News Styles Twitter Bootstrap V5');
} else {
    ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript', 'News (deprecated, use Site Sets)');
    ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Sitemap', 'News Sitemap (deprecated)');
    ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/SeoSitemap', 'News Sitemap (deprecated, use Site Sets)');
    ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb', 'News Styles Twitter Bootstrap (deprecated, use Site Sets)');
    ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb5', 'News Styles Twitter Bootstrap V5 (deprecated, use Site Sets)');
}
