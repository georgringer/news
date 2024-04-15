<?php

defined('TYPO3') or die;

$emConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\GeorgRinger\News\Domain\Model\Dto\EmConfiguration::class);
if ($emConfiguration->isAdvancedMediaPreview()) {
    $fieldConfig = [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'items' => [
            ['label' => 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinviews.0', 'value' => 0],
            ['label' => 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinviews.1', 'value' => 1],
            ['label' => 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinviews.2', 'value' => 2],
        ],
        'default' => 0,
    ];
} else {
    $fieldConfig = [
        'type' => 'check',
        'default' => 0,
    ];
}

$newSysFileReferenceColumns = [
    'showinpreview' => [
        'exclude' => true,
        'label' => 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinviews',
        'config' => $fieldConfig,
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newSysFileReferenceColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('sys_file_reference', 'newsPalette', 'showinpreview');
