<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

/**
 * Add extra fields to the sys_category record
 */
$newSysCategoryColumns = [
    'pid' => [
        'label' => 'pid',
        'config' => [
            'type' => 'passthrough'
        ]
    ],
    'sorting' => [
        'label' => 'sorting',
        'config' => [
            'type' => 'passthrough'
        ]
    ],
    'crdate' => [
        'label' => 'crdate',
        'config' => [
            'type' => 'passthrough',
        ]
    ],
    'tstamp' => [
        'label' => 'tstamp',
        'config' => [
            'type' => 'passthrough',
        ]
    ],
    'images' => [
        'exclude' => true,
        'l10n_mode' => 'mergeIfNotBlank',
        'label' => $ll . 'tx_news_domain_model_category.image',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            'images',
            [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                    'showPossibleLocalizationRecords' => true,
                    'showRemovedLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'showSynchronizationLink' => true
                ],
                'foreign_match_fields' => [
                    'fieldname' => 'images',
                    'tablenames' => 'sys_category',
                    'table_local' => 'sys_file',
                ],
            ],
            $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
        )
    ],
    'single_pid' => [
        'exclude' => true,
        'l10n_mode' => 'mergeIfNotBlank',
        'label' => $ll . 'tx_news_domain_model_category.single_pid',
        'config' => [
            'type' => 'group',
            'internal_type' => 'db',
            'allowed' => 'pages',
            'size' => 1,
            'maxitems' => 1,
            'show_thumbs' => 1,
            'default' => 0,
            'wizards' => [
                'suggest' => [
                    'type' => 'suggest',
                    'default' => [
                        'searchWholePhrase' => true
                    ]
                ],
            ],
        ]
    ],
    'shortcut' => [
        'exclude' => true,
        'l10n_mode' => 'mergeIfNotBlank',
        'label' => $ll . 'tx_news_domain_model_category.shortcut',
        'config' => [
            'type' => 'group',
            'internal_type' => 'db',
            'allowed' => 'pages',
            'size' => 1,
            'maxitems' => 1,
            'show_thumbs' => true,
            'default' => 0,
            'wizards' => [
                'suggest' => [
                    'type' => 'suggest',
                    'default' => [
                        'searchWholePhrase' => true
                    ]
                ],
            ],
        ]
    ],
    'import_id' => [
        'label' => $ll . 'tx_news_domain_model_news.import_id',
        'config' => [
            'type' => 'passthrough'
        ]
    ],
    'import_source' => [
        'label' => $ll . 'tx_news_domain_model_news.import_source',
        'config' => [
            'type' => 'passthrough'
        ]
    ],
    'seo_headline' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.seo.seo_headline',
        'config' => [
            'type' => 'input',
        ],
    ],
    'seo_title' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.seo.seo_title',
        'config' => [
            'type' => 'input',
        ],
    ],
    'seo_description' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.seo.seo_description',
        'config' => [
            'type' => 'text',
        ],
    ],
    'seo_text' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.seo.seo_text',
        'config' => [
            'type' => 'text',
        ],
        'defaultExtras' => 'richtext:rte_transform[mode=ts_css]',
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category', $newSysCategoryColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.options, images', '', 'before:description');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category', 'single_pid', '',
    'after:description');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category', 'shortcut', '',
    'after:single_pid');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category',
    '--div--;' . $ll . 'tx_news_domain_model_category.tabs.seo, seo_title, seo_description, seo_headline, seo_text', '', 'after:endtime');

$GLOBALS['TCA']['sys_category']['columns']['items']['config']['MM_oppositeUsage']['tx_news_domain_model_news']
    = [0 => 'categories'];

$GLOBALS['TCA']['sys_category']['ctrl']['label_userFunc'] =
    \GeorgRinger\News\Hooks\Labels::class . '->getUserLabelCategory';
