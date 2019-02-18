<?php
defined('TYPO3_MODE') or die();

if (version_compare(TYPO3_branch, '9.5', '>=')) {
    $newTagColumns['slug'] = [
        'exclude' => true,
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
        'displayCond' => 'USER:' . \TYPO3\CMS\Core\Compatibility\PseudoSiteTcaDisplayCondition::class . '->isInPseudoSite:pages:false',
        'config' => [
            'type' => 'slug',
            'size' => 50,
            'generatorOptions' => [
                'fields' => ['title'],
                'replacements' => [
                    '/' => '-'
                ],
            ],
            'fallbackCharacter' => '-',
            'eval' => 'uniqueInSite',
            'default' => ''
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_tag', $newTagColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_tag', 'slug', '',
        'after:title');
}
