<?php
defined('TYPO3_MODE') or die();

$newSysFileReferenceColumns = [
    'showinpreview' => [
        'exclude' => true,
        'label' => 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinviews',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinviews.0', 0, ''],
                ['LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinviews.1', 1, ''],
                ['LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinviews.2', 2, ''],
            ],
            'default' => 0,
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newSysFileReferenceColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('sys_file_reference', 'newsPalette', 'showinpreview');
