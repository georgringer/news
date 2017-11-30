<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_news_domain_model_tag',
        'descriptionColumn' => 'notes',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'default_sortby' => 'ORDER BY title',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'typeicon_classes' => [
            'default' => 'ext-news-tag'
        ],
        'searchFields' => 'uid,title'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden,title'
    ],
    'columns' => [
        'pid' => [
            'label' => 'pid',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0]
                ],
                'foreign_table' => 'tx_news_domain_model_tag',
                'foreign_table_where' => 'AND tx_news_domain_model_tag.pid=###CURRENT_PID### AND tx_news_domain_model_tag.sys_language_uid IN (-1,0)',
                'default' => 0
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'title' => [
            'exclude' => false,
            'label' => $ll . 'tx_news_domain_model_tag.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,trim'
            ]
        ],
        'seo_headline' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_tag.seo.seo_headline',
            'config' => [
                'type' => 'input'
            ]
        ],
        'seo_title' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_tag.seo.seo_title',
            'config' => [
                'type' => 'input'
            ]
        ],
        'seo_description' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_tag.seo.seo_description',
            'config' => [
                'type' => 'text'
            ]
        ],
        'seo_text' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_tag.seo.seo_text',
            'config' => [
                'type' => 'text'
            ],
            'defaultExtras' => 'richtext:rte_transform[mode=ts_css]'
        ],
        'notes' => [
            'label' => $ll . 'notes',
            'config' => [
                'type' => 'text',
                'rows' => 10,
                'cols' => 48
            ]
        ]
    ],
    'types' => [
        0 => [
            'showitem' => 'title, --palette--;;paletteCore,
            --div--;' . $ll . 'tx_news_domain_model_tag.tabs.seo, seo_title, seo_description, seo_headline, seo_text,
            --div--;' . $ll . 'notes,otes,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.extended,'
        ]
    ],
    'palettes' => [
        'paletteCore' => [
            'showitem' => 'hidden,sys_language_uid,l10n_parent,l10n_diffsource,'
        ]
    ]
];
