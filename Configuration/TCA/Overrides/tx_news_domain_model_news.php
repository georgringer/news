<?php
defined('TYPO3_MODE') or die();

if (version_compare(TYPO3_branch, '9.2', '>=')) {
    foreach (['hidden', 'editlock', 'istopnews'] as $field) {
        $GLOBALS['TCA']['tx_news_domain_model_news']['columns'][$field]['config']['renderType'] = 'checkboxToggle';
        $GLOBALS['TCA']['tx_news_domain_model_news']['columns'][$field]['config']['items'] = [
            [
                0 => '',
                1 => '',
            ]
        ];
    }

    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['path_segment']['config'] = [
        'type' => 'slug',
        'size' => 30, 'size' => 50,
        'generatorOptions' => [
            'fields' => ['title']
        ],
        'fallbackCharacter' => '-',
        'eval' => 'uniqueInSite',
        'default' => ''
    ];
}