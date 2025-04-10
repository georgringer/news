<?php

use GeorgRinger\News\Hooks\PluginPreviewRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die;

$pluginConfig = ['pi1', 'news_list_sticky', 'news_detail', 'news_selected_list', 'news_date_menu', 'category_list', 'news_search_form', 'news_search_result', 'tag_list'];
foreach ($pluginConfig as $pluginName) {
    $pluginNameForLabel = $pluginName === 'pi1' ? 'news_list' : $pluginName;
    $pluginIdentifier = ExtensionUtility::registerPlugin(
        'news',
        GeneralUtility::underscoredToUpperCamelCase($pluginName),
        'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:plugin.' . $pluginNameForLabel . '.title',
        'ext-news-plugin-' . str_replace('_', '-', $pluginNameForLabel),
        'news',
        'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:plugin.' . $pluginNameForLabel . '.description',
    );

    $contentTypeName = 'news_' . str_replace('_', '', $pluginName);
    $flexformFileName = in_array($pluginNameForLabel, ['news_search_result', 'news_list_sticky'], true) ? 'news_list' : $pluginNameForLabel;

    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:news/Configuration/FlexForms/flexform_' . $flexformFileName . '.xml',
        $pluginIdentifier
    );
    // Add the FlexForm to the show item list
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
        $pluginIdentifier,
        'after:palette:headers'
    );

    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$pluginIdentifier] = 'ext-news-plugin-' . str_replace('_', '-', $pluginNameForLabel);
    $GLOBALS['TCA']['tt_content']['types'][$pluginIdentifier]['previewRenderer'] = PluginPreviewRenderer::class;
}

$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['itemGroups']['news'] = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:pi1_title';

ExtensionManagementUtility::addToInsertRecords('tx_news_domain_model_news');

foreach (['crdate', 'tstamp'] as $fakeField) {
    if (!isset($GLOBALS['TCA']['tt_content']['columns'][$fakeField])) {
        $GLOBALS['TCA']['tt_content']['columns'][$fakeField] = [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.' . $fakeField,
            'config' => [
                'type' => 'datetime',
            ],
        ];
    }
}

$newFields = [
    'tx_news_related_news' => [
        'label' => 'tx_news_related_news',
        'config' => [
            'type' => 'passthrough',
        ],
    ],
];
ExtensionManagementUtility::addTCAcolumns('tt_content', $newFields);
