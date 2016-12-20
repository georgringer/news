<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

// Extension manager configuration
$configuration = \GeorgRinger\News\Utility\EmConfiguration::getSettings();

$teaserRteConfiguration = $configuration->getRteForTeaser() ? 'richtext:rte_transform[mode=ts_css]' : '';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_news_domain_model_news');

$tx_news_domain_model_news = [
    'ctrl' => [
        'title' => $ll . 'tx_news_domain_model_news',
        'descriptionColumn' => 'tag',
        'label' => 'title',
        'prependAtCopy' => $configuration->getPrependAtCopy() ? 'LLL:EXT:lang/locallang_general.xlf:LGL.prependAtCopy' : '',
        'hideAtCopy' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'editlock' => 'editlock',
        'type' => 'type',
        'typeicon_column' => 'type',
        'typeicon_classes' => [
            'default' => 'ext-news-type-default',
            '1' => 'ext-news-type-internal',
            '2' => 'ext-news-type-external',
        ],
        'useColumnsForDefaultValues' => 'type',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'default_sortby' => 'ORDER BY datetime DESC',
        'sortby' => ($configuration->getManualSorting() ? 'sorting' : ''),
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group',
        ],
        'iconfile' => 'EXT:news/Resources/Public/Icons/news_domain_model_news.gif',
        'searchFields' => 'uid,title',
    ],
    'interface' => [
        'showRecordFieldList' => 'cruser_id,pid,sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,teaser,bodytext,datetime,archive,author,author_email,categories,related,type,keywords,media,internalurl,externalurl,istopnews,related_files,related_links,content_elements,tags,path_segment,alternative_title,fal_related_files'
    ],
    'columns' => [
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
                'foreign_table' => 'tx_news_domain_model_news',
                'foreign_table_where' => 'AND tx_news_domain_model_news.pid=###CURRENT_PID### AND tx_news_domain_model_news.sys_language_uid IN (-1,0)',
                'showIconTable' => false
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
        'cruser_id' => [
            'label' => 'cruser_id',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
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
        'sorting' => [
            'label' => 'sorting',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 16,
                'max' => 20,
                'eval' => 'datetime',
                'default' => 0,
            ]
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 16,
                'max' => 20,
                'eval' => 'datetime',
                'default' => 0,
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
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.hide_at_login',
                        -1,
                    ],
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.any_login',
                        -2,
                    ],
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.usergroups',
                        '--div--',
                    ],
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
            ],
        ],
        'title' => [
            'exclude' => 0,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 60,
                'eval' => 'required',
            ]
        ],
        'alternative_title' => [
            'exclude' => 1,
            'label' => $ll . 'tx_news_domain_model_news.alternative_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ]
        ],
        'teaser' => [
            'exclude' => 1,
            'l10n_mode' => 'noCopy',
            'label' => $ll . 'tx_news_domain_model_news.teaser',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ]
        ],
        'bodytext' => [
            'exclude' => 0,
            'l10n_mode' => 'noCopy',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
                'softref' => 'rtehtmlarea_images,typolink_tag,images,email[subst],url',
                'wizards' => [
                    '_PADDING' => 2,
                    'RTE' => [
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'type' => 'script',
                        'title' => 'Full screen Rich Text Editing',
                        'icon' => 'actions-wizard-rte',
                        'module' => [
                            'name' => 'wizard_rte',
                        ],
                    ],
                ],
            ]
        ],
        'datetime' => [
            'exclude' => 0,
            'label' => $ll . 'tx_news_domain_model_news.datetime',
            'config' => [
                'type' => 'input',
                'size' => 16,
                'eval' => 'datetime' . ($configuration->getDateTimeRequired() ? ',required' : ''),
            ]
        ],
        'archive' => [
            'exclude' => 1,
            'l10n_mode' => 'copy',
            'label' => $ll . 'tx_news_domain_model_news.archive',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => $configuration->getArchiveDate(),
                'default' => 0
            ]
        ],
        'author' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.author_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ]
        ],
        'author_email' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.author_email_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ]
        ],
        'categories' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $ll . 'tx_news_domain_model_news.categories',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'treeConfig' => [
                    'dataProvider' => \GeorgRinger\News\TreeProvider\DatabaseTreeDataProvider::class,
                    'parentField' => 'parent',
                    'appearance' => [
                        'showHeader' => true,
                        'expandAll' => true,
                        'maxLevels' => 99,
                    ],
                ],
                'MM' => 'sys_category_record_mm',
                'MM_match_fields' => [
                    'fieldname' => 'categories',
                    'tablenames' => 'tx_news_domain_model_news',
                ],
                'MM_opposite_field' => 'items',
                'foreign_table' => 'sys_category',
                'foreign_table_where' => ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0) ORDER BY sys_category.sorting',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 99,
            ]
        ],
        'related' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $ll . 'tx_news_domain_model_news.related',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_news_domain_model_news',
                'foreign_table' => 'tx_news_domain_model_news',
                'MM_opposite_field' => 'related_from',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_news_domain_model_news_related_mm',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'searchWholePhrase' => true,
                            'addWhere' => ' AND tx_news_domain_model_news.uid != ###THIS_UID###'
                        ]
                    ],
                ],
            ]
        ],
        'related_from' => [
            'exclude' => 1,
            'label' => $ll . 'tx_news_domain_model_news.related_from',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'foreign_table' => 'tx_news_domain_model_news',
                'allowed' => 'tx_news_domain_model_news',
                'size' => 5,
                'maxitems' => 100,
                'MM' => 'tx_news_domain_model_news_related_mm',
                'readOnly' => 1,
            ]
        ],
        'related_links' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $ll . 'tx_news_domain_model_news.related_links',
            'config' => [
                'type' => 'inline',
                'allowed' => 'tx_news_domain_model_link',
                'foreign_table' => 'tx_news_domain_model_link',
                'foreign_sortby' => 'sorting',
                'foreign_field' => 'parent',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'bottom',
                    'useSortable' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showRemovedLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'showSynchronizationLink' => 1,
                    'enabledControls' => [
                        'info' => false,
                    ]
                ]
            ]
        ],
        'type' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.doktype_formlabel',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$ll . 'tx_news_domain_model_news.type.I.0', 0, 'ext-news-type-default'],
                    [$ll . 'tx_news_domain_model_news.type.I.1', 1, 'ext-news-type-internal'],
                    [$ll . 'tx_news_domain_model_news.type.I.2', 2, 'ext-news-type-external'],
                ],
                'showIconTable' => true,
                'size' => 1,
                'maxitems' => 1,
            ]
        ],
        'keywords' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $GLOBALS['TCA']['pages']['columns']['keywords']['label'],
            'config' => [
                'type' => 'text',
                'placeholder' => $ll . 'tx_news_domain_model_news.keywords.placeholder',
                'cols' => 30,
                'rows' => 5,
            ]
        ],
        'description' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.description_formlabel',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
            ]
        ],
        'internalurl' => [
            'exclude' => 0,
            'label' => $ll . 'tx_news_domain_model_news.type.I.1',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'max' => '255',
                'eval' => 'trim,required',
                'wizards' => [
                    '_PADDING' => 2,
                    'link' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link_formlabel',
                        'icon' => 'actions-wizard-link',
                        'module' => [
                            'name' => 'wizard_link',
                        ],
                        'JSopenParams' => 'height=600,width=800,status=0,menubar=0,scrollbars=1'
                    ]
                ],
                'softref' => 'typolink'
            ]
        ],
        'externalurl' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.doktype.I.8',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'required',
                'softref' => 'typolink'
            ]
        ],
        'istopnews' => [
            'exclude' => 1,
            'label' => $ll . 'tx_news_domain_model_news.istopnews',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'editlock' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_tca.xlf:editlock',
            'config' => [
                'type' => 'check'
            ]
        ],
        'content_elements' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $ll . 'tx_news_domain_model_news.content_elements',
            'config' => [
                'type' => 'inline',
                'allowed' => 'tt_content',
                'foreign_table' => 'tt_content',
                'foreign_sortby' => 'sorting',
                'foreign_field' => 'tx_news_related_news',
                'minitems' => 0,
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'bottom',
                    'useSortable' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showRemovedLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'showSynchronizationLink' => 1,
                    'enabledControls' => [
                        'info' => false,
                    ]
                ]
            ]
        ],
        'tags' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $ll . 'tx_news_domain_model_news.tags',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_news_domain_model_tag',
                'MM' => 'tx_news_domain_model_news_tag_mm',
                'foreign_table' => 'tx_news_domain_model_tag',
                'foreign_table_where' => 'ORDER BY tx_news_domain_model_tag.title',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 99,
                'wizards' => [
                    '_PADDING' => 2,
                    '_VERTICAL' => 1,
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'searchWholePhrase' => true,
                            'receiverClass' => \GeorgRinger\News\Hooks\SuggestReceiver::class
                        ],
                    ],
                    'list' => [
                        'type' => 'script',
                        'title' => $ll . 'tx_news_domain_model_news.tags.list',
                        'icon' => 'actions-system-list-open',
                        'params' => [
                            'table' => 'tx_news_domain_model_tag',
                            'pid' => $configuration->getTagPid(),
                        ],
                        'module' => [
                            'name' => 'wizard_list',
                        ],
                    ],
                    'edit' => [
                        'type' => 'popup',
                        'title' => $ll . 'tx_news_domain_model_news.tags.edit',
                        'module' => [
                            'name' => 'wizard_edit',
                        ],
                        'popup_onlyOpenIfSelected' => 1,
                        'icon' => 'actions-open',
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                    ],
                ],
            ],
        ],
        'path_segment' => [
            'exclude' => 1,
            'label' => $ll . 'tx_news_domain_model_news.path_segment',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,alphanum_x,lower,unique',
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
        'fal_media' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $ll . 'tx_news_domain_model_news.fal_media',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'fal_media',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => $ll . 'tx_news_domain_model_news.fal_media.add',
                        'showPossibleLocalizationRecords' => 1,
                        'showRemovedLocalizationRecords' => 1,
                        'showAllLocalizationLink' => 1,
                        'showSynchronizationLink' => 1
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'fal_media',
                        'tablenames' => 'tx_news_domain_model_news',
                        'table_local' => 'sys_file',
                    ],
                    // custom configuration for displaying fields in the overlay/reference table
                    // to use the newsPalette and imageoverlayPalette instead of the basicoverlayPalette
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
                        ]
                    ]
                ],
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext']
            )
        ],
        'fal_related_files' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => '' . $ll . 'tx_news_domain_model_news.fal_related_files',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'fal_related_files',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => $ll . 'tx_news_domain_model_news.fal_related_files.add',
                        'showPossibleLocalizationRecords' => 1,
                        'showRemovedLocalizationRecords' => 1,
                        'showAllLocalizationLink' => 1,
                        'showSynchronizationLink' => 1
                    ],
                    'inline' => [
                        'inlineOnlineMediaAddButtonStyle' => 'display:none'
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'fal_related_files',
                        'tablenames' => 'tx_news_domain_model_news',
                        'table_local' => 'sys_file',
                    ],
                ]
            )
        ]
    ],
    'types' => [
        // default news
        '0' => [
            'columnsOverrides' => [
                'bodytext' => [
                    'defaultExtras' => 'richtext:rte_transform[mode=ts_css]'
                ],
                'teaser' => [
                    'defaultExtras' => $teaserRteConfiguration
                ],
            ],
            'showitem' => 'l10n_parent, l10n_diffsource,
					title,--palette--;;paletteCore,teaser,
					--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.editorial;paletteAuthor,
					--palette--;;paletteDate,
					bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:rte_enabled_formlabel,
                --div--;' . $ll . 'tx_news_domain_model_news.content_elements,content_elements,

				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
					--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;paletteAccess,

				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.options,categories,tags,
				--div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,fal_media,fal_related_files,related_links,related,related_from,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
					--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.metatags;metatags,
					--palette--;' . $ll . 'tx_news_domain_model_news.palettes.alternativeTitles;alternativeTitles,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.extended,'
        ],
        // internal url
        '1' => [
            'columnsOverrides' => [
                'bodytext' => [
                    'defaultExtras' => 'richtext:rte_transform[mode=ts_css]'
                ],
                'teaser' => [
                    'defaultExtras' => $teaserRteConfiguration
                ],
            ],
            'showitem' => 'l10n_parent, l10n_diffsource,
					title,--palette--;;paletteCore, teaser,
					--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.editorial;paletteAuthor,
					--palette--;;paletteDate,
					internalurl,

				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
					--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;paletteAccess,

				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.options,categories,tags,
				--div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,fal_media,fal_related_files,related_links,related,related_from,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
					--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.metatags;metatags,
					--palette--;' . $ll . 'tx_news_domain_model_news.palettes.alternativeTitles;alternativeTitles,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.extended,'
        ],
        // external url
        '2' => [
            'columnsOverrides' => [
                'bodytext' => [
                    'defaultExtras' => 'richtext:rte_transform[mode=ts_css]'
                ],
                'teaser' => [
                    'defaultExtras' => $teaserRteConfiguration
                ],
            ],
            'showitem' => 'l10n_parent, l10n_diffsource,
					title,--palette--;;paletteCore, teaser,
					--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.editorial;paletteAuthor,
					--palette--;;paletteDate,
					externalurl,

				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
					--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;paletteAccess,

				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.options,categories,tags,
				--div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,fal_media,fal_related_files,related_links,related,related_from,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
					--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.metatags;metatags,
					--palette--;' . $ll . 'tx_news_domain_model_news.palettes.alternativeTitles;alternativeTitles,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.extended,'
        ],
    ],
    'palettes' => [
        'paletteAuthor' => [
            'showitem' => 'author,author_email,',
            'canNotCollapse' => true
        ],
        'paletteDate' => [
            'showitem' => 'datetime,archive,',
            'canNotCollapse' => true
        ],
        'paletteArchive' => [
            'showitem' => 'archive,',
            'canNotCollapse' => true
        ],
        'paletteCore' => [
            'showitem' => 'istopnews, type, sys_language_uid, hidden,',
            'canNotCollapse' => false
        ],
        'paletteNavtitle' => [
            'showitem' => 'alternative_title,path_segment',
            'canNotCollapse' => false
        ],
        'paletteAccess' => [
            'showitem' => 'starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,
					endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel,
					--linebreak--, fe_group;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:fe_group_formlabel,
					--linebreak--,editlock,',
            'canNotCollapse' => true,
        ],
        'metatags' => [
            'showitem' => 'keywords,--linebreak--,description,',
            'canNotCollapse' => 1
        ],
        'alternativeTitles' => [
            'showitem' => 'alternative_title,--linebreak--,path_segment',
            'canNotCollapse' => 1
        ],
    ]
];

// category restriction based on settings in extension manager
$categoryRestrictionSetting = $configuration->getCategoryRestriction();
if ($categoryRestrictionSetting) {
    $categoryRestriction = '';
    switch ($categoryRestrictionSetting) {
        case 'current_pid':
            $categoryRestriction = ' AND sys_category.pid=###CURRENT_PID### ';
            break;
        case 'siteroot':
            $categoryRestriction = ' AND sys_category.pid IN (###SITEROOT###) ';
            break;
        case 'page_tsconfig':
            $categoryRestriction = ' AND sys_category.pid IN (###PAGE_TSCONFIG_IDLIST###) ';
            break;
        default:
            $categoryRestriction = '';
    }

    // prepend category restriction at the beginning of foreign_table_where
    if (!empty($categoryRestriction)) {
        $tx_news_domain_model_news['columns']['categories']['config']['foreign_table_where'] = $categoryRestriction .
            $tx_news_domain_model_news['columns']['categories']['config']['foreign_table_where'];
    }
}

if (!$configuration->getContentElementRelation()) {
    unset($tx_news_domain_model_news['columns']['content_elements']);
}

return $tx_news_domain_model_news;
