<?php
defined('TYPO3_MODE') or die();

/**
 * Add extra field showinpreview and some special news controls to sys_file_reference record
 */
$newSysFileReferenceColumns = [
    'showinpreview' => [
        'exclude' => true,
        'label' => 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinpreview',
        'config' => [
            'type' => 'select',
            'items' => [
                ['LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinpreview.0', 0],
                ['LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinpreview.1', 1],
                ['LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinpreview.2', 2],
            ]
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newSysFileReferenceColumns);

// add special news palette
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('sys_file_reference', 'newsPalette', 'showinpreview');
