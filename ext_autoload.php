<?php
$extensionClassesPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news') . 'Classes/';
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news') . 'Classes/Cache/ClassCacheBuilder.php');

$default = array();

/** @var \GeorgRinger\News\Cache\ClassCacheBuilder $classCacheBuilder */
$classCacheBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('GeorgRinger\\News\\Cache\\ClassCacheBuilder');
$mergedClasses = array_merge($default, $classCacheBuilder->build());
return $mergedClasses;
