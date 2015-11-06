<?php
defined('TYPO3_MODE') or die();

/**
 * Add extra field showinpreview and some special news controls to sys_file_reference record
 */
$newSysFileReferenceColumns = [
    'showinpreview' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinpreview',
        'config' => [
            'type' => 'check',
            'default' => 0
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newSysFileReferenceColumns);

// add special news palette
$GLOBALS['TCA']['sys_file_reference']['palettes']['newsPalette'] = [
    'showitem' => 'showinpreview',
    'canNotCollapse' => true
];
