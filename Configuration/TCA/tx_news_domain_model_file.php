<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_news_domain_model_file',
        'descriptionColumn' => 'description',
        'label' => 'title',
        'label_alt' => 'file',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'dividers2tabs' => true,
        'default_sortby' => 'ORDER BY sorting',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'fe_group' => 'fe_group',
        ],
        'iconfile' => 'EXT:news/Resources/Public/Icons/news_domain_model_file.gif',
        'hideTable' => true,
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,title,description,file'
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
        'sys_language_uid' => [
            'exclude' => 1,
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
                'default' => 0,
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_news_domain_model_file',
                'foreign_table_where' => 'AND tx_news_domain_model_file.pid=###CURRENT_PID### AND tx_news_domain_model_file.sys_language_uid IN (-1,0)',
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
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
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $ll . 'tx_news_domain_model_file.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ]
        ],
        'description' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $ll . 'tx_news_domain_model_file.description',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
            ]
        ],
        'file' => [
            'exclude' => 0,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $ll . 'tx_news_domain_model_file.file',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'internal_type' => 'file',
                'allowed' => '',
                'disallowed' => 'php,php3',
                'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
                'uploadfolder' => 'uploads/tx_news',
                'show_thumbs' => 1,
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ]
        ],
        'fe_group' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 20,
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.hide_at_login', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.any_login', -2],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.usergroups', '--div--',],
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
            ],
        ],
    ],
    'types' => [
        0 => [
            'showitem' => 'file, --palette--;;palettteCore,title, --palette--;;palettteDescription,fe_group'
        ]
    ],
    'palettes' => [
        'paletteCore' => [
            'showitem' => 'hidden,sys_language_uid, l10n_parent, l10n_diffsource,',
            'canNotCollapse' => true
        ],
        'palettteDescription' => [
            'showitem' => 'description',
            'canNotCollapse' => false
        ]
    ]
];

