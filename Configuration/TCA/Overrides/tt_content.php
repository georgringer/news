<?php

defined('TYPO3') or die;

$pluginConfig = ['pi1', 'news_list_sticky', 'news_detail', 'news_date_menu', 'news_search_form', 'news_search_result', 'news_selected_list', 'category_list', 'tag_list'];
foreach ($pluginConfig as $pluginName) {
    $pluginNameForLabel = $pluginName === 'pi1' ? 'news_list' : $pluginName;
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'news',
        \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($pluginName),
        'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:plugin.' . $pluginNameForLabel . '.title',
        null,
        'news'
    );

    $contentTypeName = 'news_' . str_replace('_', '', $pluginName);
    $flexformFileName = in_array($pluginNameForLabel, ['news_search_result', 'news_list_sticky'], true) ? 'news_list' : $pluginNameForLabel;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:news/Configuration/FlexForms/flexform_' . $flexformFileName . '.xml',
        $contentTypeName
    );
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$contentTypeName] = 'ext-news-plugin-' . str_replace('_', '-', $pluginNameForLabel);

    $GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['previewRenderer'] = \GeorgRinger\News\Hooks\PluginPreviewRenderer::class;
    $GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,
            pi_flexform,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    ';
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_news_domain_model_news');

foreach (['crdate', 'tstamp'] as $fakeField) {
    if (!isset($GLOBALS['TCA']['tt_content']['columns'][$fakeField])) {
        $GLOBALS['TCA']['tt_content']['columns'][$fakeField] = [
            'label' => $fakeField,
            'config' => [
                'type' => 'passthrough',
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
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $newFields);
