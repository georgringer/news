<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

// Extension manager configuration
$configuration = \GeorgRinger\News\Utility\EmConfiguration::getSettings();

$tx_news_domain_model_media = [
    'ctrl' => [
        'title' => $ll . 'tx_news_domain_model_media',
        'descriptionColumn' => 'description',
        'label' => 'caption',
        'label_alt' => 'type, showinpreview',
        'label_alt_force' => 1,
        'formattedLabel_userFunc' => \GeorgRinger\News\Hooks\Labels::class . '->getUserLabelMedia',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'type' => 'type',
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
        ],
        'iconfile' => 'EXT:news/Resources/Public/Icons/news_domain_model_media.gif',
        'hideTable' => true,
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,title,media,type,video,showInPreview, width, height, description'
    ],
    'columns' => [
        'pid' => [
            'label' => 'pid',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'sorting' => [
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
                'foreign_table' => 'tx_news_domain_model_media',
                'foreign_table_where' => 'AND tx_news_domain_model_media.pid=###CURRENT_PID### AND tx_news_domain_model_media.sys_language_uid IN (-1,0)',
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
        'caption' => [
            'exclude' => 1,
            'l10n_mode' => 'noCopy',
            'label' => $ll . 'tx_news_domain_model_media.caption',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ]
        ],
        'title' => [
            'exclude' => 1,
            'l10n_mode' => 'noCopy',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:image_titleText',
            'config' => [
                'type' => 'input',
                'size' => 20,
            ]
        ],
        'alt' => [
            'exclude' => 1,
            'l10n_mode' => 'noCopy',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:image_altText',
            'config' => [
                'type' => 'input',
                'size' => 20,
            ]
        ],
        'image' => [
            'exclude' => 0,
            'l10n_mode' => 'copy',
            'label' => $ll . 'tx_news_domain_model_media.media',
            'config' => [
                'type' => 'group',
                'internal_type' => 'file',
                'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
                'uploadfolder' => 'uploads/tx_news',
                'show_thumbs' => 1,
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ]
        ],
        'multimedia' => [
            'exclude' => 0,
            'l10n_mode' => 'copy',
            'label' => $ll . 'tx_news_domain_model_media.multimedia',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'softref' => 'typolink',
                'wizards' => [
                    '_PADDING' => 2,
                    'link' => [
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'module' => [
                            'name' => 'wizard_link',
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],
        'showinpreview' => [
            'exclude' => 1,
            'label' => $ll . 'tx_news_domain_model_media.showinpreview',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'type' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        $ll . 'tx_news_domain_model_media.type.I.0',
                        '0',
                        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news') . 'Resources/Public/Icons/media_type_image.png'
                    ],
                    [
                        $ll . 'tx_news_domain_model_media.type.I.1',
                        '1',
                        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news') . 'Resources/Public/Icons/media_type_multimedia.png'
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
            ]
        ],
        'width' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imagewidth_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 3,
                'eval' => 'int',
            ]
        ],
        'height' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageheight_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 3,
                'eval' => 'int',
            ]
        ],
        'copyright' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $ll . 'tx_news_domain_model_media.copyright',
            'config' => [
                'type' => 'input',
                'size' => 20,
            ]
        ],
        'description' => [
            'exclude' => 0,
            'l10n_mode' => 'noCopy',
            'label' => $ll . 'tx_news_domain_model_file.description',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
                'wizards' => [
                    '_PADDING' => 2,
                    'RTE' => [
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'type' => 'script',
                        'title' => 'Full screen Rich Text Editing',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                        'module' => [
                            'name' => 'wizard_rte',
                        ],
                    ],
                ],
            ]
        ],
    ],
    'types' => [
        // Image
        '0' => ['showitem' => '--palette--;;paletteCore,image, --palette--;;paletteWidthHeight,caption,title, --palette--;;paletteAlt,copyright,description'],
        // Multimedia (Video & Audio)
        '1' => ['showitem' => '--palette--;;paletteCore,multimedia,caption,copyright,description,'],
    ],
    'palettes' => [
        'paletteWidthHeight' => [
            'showitem' => 'width,height,',
            'canNotCollapse' => true
        ],
        'paletteCore' => [
            'showitem' => 'type,showinpreview, hidden,sys_language_uid, l10n_parent, l10n_diffsource,',
            'canNotCollapse' => true
        ],
        'paletteAlt' => [
            'showitem' => 'alt',
            'canNotCollapse' => false
        ],
    ]
];

// Hide image type when FAL + Multimedia is set
if ($configuration->getUseFal() === 3) {
    unset($tx_news_domain_model_media['columns']['type']['config']['items'][0]);
    $tx_news_domain_model_media['columns']['type']['config']['default'] = '1';
}

// Hide RTE description field
if (!$configuration->getShowMediaDescriptionField()) {
    unset($tx_news_domain_model_media['columns']['description']);
}

return $tx_news_domain_model_media;