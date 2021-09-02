<?php

defined('TYPO3_MODE') or die();

$boot = static function (): void {
    // Add seo sitemap fields
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('seo')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
            'tx_news_domain_model_news',
            [
                'no_index' => [
                    'exclude' => true,
                    'l10n_mode' => 'exclude',
                    'onChange' => 'reload',
                    'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.no_index_formlabel',
                    'config' => [
                        'type' => 'check',
                        'renderType' => 'checkboxToggle',
                        'items' => [
                            [
                                '0' => '',
                                '1' => '',
                                'invertStateDisplay' => true
                            ]
                        ]
                    ]
                ],
                'no_follow' => [
                    'exclude' => true,
                    'l10n_mode' => 'exclude',
                    'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.no_follow_formlabel',
                    'config' => [
                        'type' => 'check',
                        'renderType' => 'checkboxToggle',
                        'items' => [
                            [
                                '0' => '',
                                '1' => '',
                                'invertStateDisplay' => true
                            ]
                        ]
                    ]
                ],
                'canonical_link' => [
                    'exclude' => true,
                    'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.canonical_link',
                    'displayCond' => 'FIELD:no_index:=:0',
                    'config' => [
                        'type' => 'input',
                        'renderType' => 'inputLink',
                        'size' => 50,
                        'max' => 1024,
                        'eval' => 'trim',
                        'fieldControl' => [
                            'linkPopup' => [
                                'options' => [
                                    'title' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.canonical_link',
                                    'blindLinkFields' => 'class,target,title',
                                    'blindLinkOptions' => 'mail,folder,file,telephone'
                                ],
                            ],
                        ],
                        'softref' => 'typolink'
                    ]
                ],
                'sitemap_changefreq' => [
                    'config' => [
                        'items' => [
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.none', ''],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.always', 'always'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.hourly', 'hourly'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.daily', 'daily'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.weekly', 'weekly'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.monthly', 'monthly'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.yearly', 'yearly'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.never', 'never']
                        ],
                        'renderType' => 'selectSingle',
                        'type' => 'select'
                    ],
                    'exclude' => true,
                    'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq'
                ],
                'sitemap_priority' => [
                    'config' => [
                        'items' => [
                            ['0.0', '0.0'],
                            ['0.1', '0.1'],
                            ['0.2', '0.2'],
                            ['0.3', '0.3'],
                            ['0.4', '0.4'],
                            ['0.5', '0.5'],
                            ['0.6', '0.6'],
                            ['0.7', '0.7'],
                            ['0.8', '0.8'],
                            ['0.9', '0.9'],
                            ['1.0', '1.0']
                        ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                        'default' => '0.5',
                    ],
                    'exclude' => true,
                    'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_priority'
                ]
            ]
        );

        $GLOBALS['TCA']['tx_news_domain_model_news']['palettes']['robots'] = [
            'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.robots',
            'showitem' => 'no_index;LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.no_index_formlabel, no_follow;LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.no_follow_formlabel',
        ];
        $GLOBALS['TCA']['tx_news_domain_model_news']['palettes']['canonical'] = [
            'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.canonical',
            'showitem' => 'canonical_link',
        ];
        $GLOBALS['TCA']['tx_news_domain_model_news']['palettes']['sitemap'] = [
            'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.sitemap',
            'showitem' => 'sitemap_changefreq,sitemap_priority'
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tx_news_domain_model_news',
            '--palette--;;robots,--palette--;;canonical,--palette--;;sitemap',
            '',
            'after:alternative_title'
        );
    }
};

$boot();
unset($boot);
