<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_news_domain_model_tag',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY title',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'typeicon_classes' => [
            'default' => 'ext-news-tag'
        ],
        'searchFields' => 'uid,title',
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
                'type' => 'passthrough',
            ]
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'title' => [
            'exclude' => 0,
            'label' => $ll . 'tx_news_domain_model_tag.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,unique,trim',
            ]
        ],
        'seo_headline' => [
            'exclude' => 1,
            'label' => $ll . 'tx_news_domain_model_tag.seo.seo_headline',
            'config' => [
                'type' => 'input',
            ],
        ],
        'seo_title' => [
            'exclude' => 1,
            'label' => $ll . 'tx_news_domain_model_tag.seo.seo_title',
            'config' => [
                'type' => 'input',
            ],
        ],
        'seo_description' => [
            'exclude' => 1,
            'label' => $ll . 'tx_news_domain_model_tag.seo.seo_description',
            'config' => [
                'type' => 'text',
            ],
        ],
        'seo_text' => [
            'exclude' => 1,
            'label' => $ll . 'tx_news_domain_model_tag.seo.seo_text',
            'config' => [
                'type' => 'text',
            ],
            'defaultExtras' => 'richtext:rte_transform[mode=ts_css]',
        ],
    ],
    'types' => [
        0 => [
            'showitem' => 'title, --palette--;;paletteCore,
            --div--;' . $ll . 'tx_news_domain_model_tag.tabs.seo, seo_title, seo_description, seo_headline, seo_text'
        ]
    ],
    'palettes' => [
        'paletteCore' => [
            'showitem' => 'hidden,',
            'canNotCollapse' => true
        ],
    ]
];
