<?php

defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title'                    => $ll.'tx_news_domain_model_link',
        'descriptionColumn'        => 'description',
        'label'                    => 'title',
        'label_alt'                => 'uri',
        'label_alt_force'          => 1,
        'tstamp'                   => 'tstamp',
        'crdate'                   => 'crdate',
        'cruser_id'                => 'cruser_id',
        'type'                     => 'type',
        'languageField'            => 'sys_language_uid',
        'transOrigPointerField'    => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'versioningWS'             => true,
        'origUid'                  => 't3_origuid',
        'dividers2tabs'            => true,
        'default_sortby'           => 'ORDER BY sorting',
        'sortby'                   => 'sorting',
        'delete'                   => 'deleted',
        'enablecolumns'            => [
            'disabled' => 'hidden',
        ],
        'iconfile'  => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news').'Resources/Public/Icons/news_domain_model_link.gif',
        'hideTable' => true,
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden,title,description,uri',
    ],
    'columns' => [
        'pid' => [
            'label'  => 'pid',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'crdate' => [
            'label'  => 'crdate',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'tstamp' => [
            'label'  => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'sys_language_uid' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config'  => [
                'type'                => 'select',
                'foreign_table'       => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items'               => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0],
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude'     => 1,
            'label'       => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config'      => [
                'type'  => 'select',
                'items' => [
                    ['', 0],
                ],
                'foreign_table'       => 'tx_news_domain_model_link',
                'foreign_table_where' => 'AND tx_news_domain_model_link.pid=###CURRENT_PID### AND tx_news_domain_model_link.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type'    => 'passthrough',
                'default' => '',
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config'  => [
                'type'    => 'check',
                'default' => 0,
            ],
        ],
        'title' => [
            'exclude'   => 0,
            'l10n_mode' => 'mergeIfNotBlank',
            'label'     => $ll.'tx_news_domain_model_link.title',
            'config'    => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'description' => [
            'exclude'   => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label'     => $ll.'tx_news_domain_model_link.description',
            'config'    => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
            ],
        ],
        'uri' => [
            'exclude'   => 0,
            'l10n_mode' => 'mergeIfNotBlank',
            'label'     => $ll.'tx_news_domain_model_link.uri',
            'config'    => [
                'type'        => 'input',
                'placeholder' => $ll.'tx_news_domain_model_link.uri.placeholder',
                'size'        => 30,
                'eval'        => 'trim,required',
                'softref'     => 'news_externalurl',
                'wizards'     => [
                    '_PADDING' => 2,
                    'link'     => [
                        'type'   => 'popup',
                        'title'  => 'Link',
                        'icon'   => 'link_popup.gif',
                        'module' => [
                            'name'          => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard',
                            ],
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
                    ],
                ],
            ],
        ],
    ],
    'types' => [
        0 => [
            'showitem' => 'uri;;paletteCore,title;;paletteDescription,',
        ],
    ],
    'palettes' => [
        'paletteCore' => [
            'showitem'       => 'hidden,sys_language_uid,l10n_parent, l10n_diffsource,',
            'canNotCollapse' => true,
        ],
        'paletteDescription' => [
            'showitem'       => 'description',
            'canNotCollapse' => false,
        ],
    ],
];
