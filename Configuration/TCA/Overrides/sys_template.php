<?php

defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript', 'News');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Sitemap', 'News Sitemap');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb', 'News Styles Twitter Bootstrap');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('news', 'Configuration/TypoScript/Styles/Twb5', 'News Styles Twitter Bootstrap V5');
