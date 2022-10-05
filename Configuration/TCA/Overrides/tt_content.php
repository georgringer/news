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
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'news_pi1',
    'FILE:EXT:news/Configuration/FlexForms/flexform_news.xml'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_news_domain_model_news');

foreach (['crdate', 'tstamp'] as $fakeField) {
    if (!isset($GLOBALS['TCA']['tt_content']['columns'][$fakeField])) {
        $GLOBALS['TCA']['tt_content']['columns'][$fakeField] = [
            'label' => $fakeField,
            'config' => [
                'type' => 'passthrough',
            ]
        ];
    }
}

$newFields = [
    'tx_news_related_news' => [
        'label' => 'tx_news_related_news',
        'config' => [
            'type' => 'passthrough'
        ]
    ]
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $newFields);
