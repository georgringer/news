<?php

use GeorgRinger\News\Backend\FormEngine\SlugPrefix;
use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die;

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

$configuration = GeneralUtility::makeInstance(EmConfiguration::class);

$tx_news_domain_model_news = [
    'ctrl' => [
        'title' => $ll . 'tx_news_domain_model_news',
        'descriptionColumn' => 'notes',
        'label' => 'title',
        'prependAtCopy' => $configuration->getPrependAtCopy() ? 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.prependAtCopy' : '',
        'hideAtCopy' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
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
        'translationSource' => 'l10n_source',
        'default_sortby' => 'datetime DESC',
        'sortby' => ($configuration->getManualSorting() ? 'sorting' : ''),
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group',
        ],
        'iconfile' => 'EXT:news/Resources/Public/Icons/news_domain_model_news.svg',
        'searchFields' => 'uid,title',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_news_domain_model_news',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
                'default' => 0,
            ],
        ],
        'l10n_source' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => '',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],

        'pid' => [
            'label' => 'pid',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'datetime',
            ],
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'datetime',
            ],
        ],
        'sorting' => [
            'label' => 'sorting',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel',
            'config' => [
                'type' => 'datetime',
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
            'config' => [
                'type' => 'datetime',
            ],
        ],
        'fe_group' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 20,
                'items' => [
                    ['label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hide_at_login', 'value' => -1],
                    ['label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.any_login', 'value' => -2],
                    ['label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.usergroups', 'value' => '--div--'],
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
            ],
        ],
        'title' => [
            'exclude' => false,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 60,
                'max' => 255,
                'required' => true,
            ],
        ],
        'alternative_title' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_news.alternative_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'teaser' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_news.teaser',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
                'enableRichtext' => $configuration->getRteForTeaser(),
            ],
        ],
        'bodytext' => [
            'exclude' => false,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
                'softref' => 'typolink_tag,email[subst],url',
                'enableRichtext' => true,
            ],
        ],
        'datetime' => [
            'exclude' => false,
            'label' => $ll . 'tx_news_domain_model_news.datetime',
            'config' => [
                'type' => 'datetime',
                'required' => $configuration->getDateTimeRequired(),
            ],
        ],
        'archive' => [
            'exclude' => true,
            'l10n_mode' => 'copy',
            'label' => $ll . 'tx_news_domain_model_news.archive',
            'config' => [
                'type' => 'datetime',
                'format' => $configuration->getArchiveDate(),
            ],
        ],
        'author' => [
            'exclude' => true,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.author_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'author_email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.author_email_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'categories' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_news.categories',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'treeConfig' => [
                    //                    'dataProvider' => \GeorgRinger\News\TreeProvider\DatabaseTreeDataProvider::class,
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
                'size' => 30,
                'minitems' => 0,
                'maxitems' => 99,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'related' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_news.related',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_news_domain_model_news',
                'foreign_table' => 'tx_news_domain_model_news',
                'MM_opposite_field' => 'related_from',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_news_domain_model_news_related_mm',
                'suggestOptions' => [
                    'default' => [
                        'suggestOptions' => true,
                        'addWhere' => ' AND tx_news_domain_model_news.uid != ###THIS_UID###',
                    ],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'related_from' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_news.related_from',
            'config' => [
                'type' => 'group',
                'foreign_table' => 'tx_news_domain_model_news',
                'allowed' => 'tx_news_domain_model_news',
                'size' => 5,
                'maxitems' => 100,
                'MM' => 'tx_news_domain_model_news_related_mm',
                'readOnly' => 1,
            ],
        ],
        'related_links' => [
            'exclude' => true,
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
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'levelLinksPosition' => 'bottom',
                    'useSortable' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'showSynchronizationLink' => true,
                    'enabledControls' => [
                        'info' => false,
                    ],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'type' => [
            'exclude' => false,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.doktype_formlabel',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' =>  [
                    ['label' => $ll . 'tx_news_domain_model_news.type.I.0', 'value' => 0, 'icon' => 'ext-news-type-default'],
                    ['label' => $ll . 'tx_news_domain_model_news.type.I.1', 'value' => 1, 'icon' => 'ext-news-type-internal'],
                    ['label' => $ll . 'tx_news_domain_model_news.type.I.2', 'value' => 2, 'icon' => 'ext-news-type-external'],
                ],
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
        'keywords' => [
            'exclude' => true,
            'label' => $GLOBALS['TCA']['pages']['columns']['keywords']['label'],
            'config' => [
                'type' => 'text',
                'placeholder' => $ll . 'tx_news_domain_model_news.keywords.placeholder',
                'cols' => 30,
                'rows' => 5,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'internalurl' => [
            'exclude' => false,
            'label' => $ll . 'tx_news_domain_model_news.type.I.1',
            'config' => [
                'type' => 'link',
                'required' => true,
            ],
        ],
        'externalurl' => [
            'exclude' => false,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.doktype.I.8',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'required' => true,
                'softref' => 'typolink',
            ],
        ],
        'istopnews' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_news.istopnews',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],
        'editlock' => [
            'displayCond' => 'HIDE_FOR_NON_ADMINS',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:editlock',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'content_elements' => [
            'exclude' => true,
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
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'levelLinksPosition' => 'bottom',
                    'useSortable' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'showSynchronizationLink' => true,
                    'enabledControls' => [
                        'info' => false,
                    ],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'tags' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_news.tags',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'MM' => 'tx_news_domain_model_news_tag_mm',
                'foreign_table' => 'tx_news_domain_model_tag',
                'foreign_table_where' => ' AND (tx_news_domain_model_tag.sys_language_uid IN (-1,0) OR tx_news_domain_model_tag.l10n_parent = 0) ORDER BY tx_news_domain_model_tag.title',
                'size' => 10,
                'maxitems' => 99,
                'suggestOptions' => [
                    'default' => [
                        'minimumCharacters' => 2,
                        'searchWholePhrase' => true,
                    ],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'path_segment' => [
            'label' => $ll . 'tx_news_domain_model_news.path_segment',
            'displayCond' => 'VERSION:IS:false',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['title'],
                    'replacements' => [
                        '/' => '-',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => $configuration->getSlugBehaviour(),
                'default' => '',
                'appearance' => [
                    'prefix' => SlugPrefix::class . '->getPrefix',
                ],
            ],
        ],
        'import_id' => [
            'label' => $ll . 'tx_news_domain_model_news.import_id',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'import_source' => [
            'label' => $ll . 'tx_news_domain_model_news.import_source',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'fal_media' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_news.fal_media',
            'config' => [
                'type' => 'file',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'appearance' => [
                    'createNewRelationLinkTitle' => $ll . 'tx_news_domain_model_news.fal_media.add',
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'showSynchronizationLink' => true,
                ],
                'overrideChildTca' => [
                    'types' => [
                        File::FILETYPE_UNKNOWN => [
                            'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette',
                        ],
                        File::FILETYPE_TEXT => [
                            'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette',
                        ],
                        File::FILETYPE_IMAGE => [
                            'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette',
                        ],
                        File::FILETYPE_AUDIO => [
                            'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
                                    --palette--;;audioOverlayPalette,
                                    --palette--;;filePalette',
                        ],
                        File::FILETYPE_VIDEO => [
                            'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
                                    --palette--;;videoOverlayPalette,
                                    --palette--;;filePalette',
                        ],
                        File::FILETYPE_APPLICATION => [
                            'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette',
                        ],
                    ],
                ],
                'allowed' => 'common-media-types',
            ],
        ],
        'fal_related_files' => [
            'exclude' => true,
            'label' => $ll . 'tx_news_domain_model_news.fal_related_files',
            'config' => [
                'type' => 'file',
                'appearance' => [
                    'createNewRelationLinkTitle' => $ll . 'tx_news_domain_model_news.fal_related_files.add',
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'showSynchronizationLink' => true,
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'inline' => [
                    'inlineOnlineMediaAddButtonStyle' => 'display:none',
                ],
            ],
        ],
        'notes' => [
            'label' => $ll . 'notes',
            'config' => [
                'type' => 'text',
                'rows' => 10,
                'cols' => 48,
            ],
        ],
    ],
    'types' => [
        // default news
        '0' => [
            'showitem' => '
                    --palette--;;paletteCore,title,--palette--;;paletteSlug,teaser,
                    --palette--;;paletteDate,
                    bodytext,
                ' . ($configuration->getContentElementRelation() ? ('--div--;' . $ll . 'tx_news_domain_model_news.content_elements,
                    content_elements,') : '') . '
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.media,
                    fal_media,fal_related_files,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,
                    related,related_from,
                    related_links,tags,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.editorial;paletteAuthor,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.metatags;metatags,
                    --palette--;' . $ll . 'tx_news_domain_model_news.palettes.alternativeTitles;alternativeTitles,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;paletteLanguage,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;paletteHidden,
                    --palette--;;paletteAccess,
                --div--;' . $ll . 'notes,
                    notes,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.extended,',
        ],
        // internal url
        '1' => [
            'showitem' => '
                    --palette--;;paletteCore,title,--palette--;;paletteSlug,teaser,
                    internalurl,
                    --palette--;;paletteDate,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.media,
                    fal_media,fal_related_files,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,
                    related,related_from,
                    related_links,tags,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.editorial;paletteAuthor,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.metatags;metatags,
                    --palette--;' . $ll . 'tx_news_domain_model_news.palettes.alternativeTitles;alternativeTitles,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;paletteLanguage,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;paletteHidden,
                    --palette--;;paletteAccess,
                --div--;' . $ll . 'notes,
                    notes,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.extended,',
        ],
        // external url
        '2' => [
            'showitem' => '
                    --palette--;;paletteCore,title,--palette--;;paletteSlug,teaser,
                    externalurl,
                    --palette--;;paletteDate,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.media,
                    fal_media,fal_related_files,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,
                    related,related_from,
                    related_links,tags,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.editorial;paletteAuthor,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.metatags;metatags,
                    --palette--;' . $ll . 'tx_news_domain_model_news.palettes.alternativeTitles;alternativeTitles,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;paletteLanguage,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;paletteHidden,
                    --palette--;;paletteAccess,
                --div--;' . $ll . 'notes,
                    notes,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.extended,',
        ],
    ],
    'palettes' => [
        'paletteAuthor' => [
            'showitem' => 'author,author_email,',
        ],
        'paletteDate' => [
            'label' => $ll . 'tx_news_domain_model_news.palettes.dates',
            'showitem' => 'datetime,archive,',
        ],
        'paletteCore' => [
            'showitem' => 'type,istopnews,',
        ],
        'metatags' => [
            'showitem' => 'keywords,description,',
        ],
        'alternativeTitles' => [
            'showitem' => 'alternative_title',
        ],

        'paletteHidden' => [
            'showitem' => '
                hidden
            ',
        ],
        'paletteAccess' => [
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access',
            'showitem' => '
                starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,
                endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel,
                --linebreak--,
                fe_group;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:fe_group_formlabel,
                --linebreak--,editlock
            ',
        ],
        'paletteLanguage' => [
            'showitem' => 'sys_language_uid,l10n_parent,',
        ],
        'paletteSlug' => [
            'showitem' => '
                path_segment
            ',
        ],
    ],
];

// category restriction based on settings in extension manager
$categoryRestrictionSetting = $configuration->getCategoryRestriction();
if ($categoryRestrictionSetting) {
    $categoryRestriction = match ($categoryRestrictionSetting) {
        'current_pid' => ' AND sys_category.pid=###CURRENT_PID### ',
        'siteroot' => ' AND sys_category.pid IN (###SITEROOT###) ',
        'page_tsconfig' => ' AND sys_category.pid IN (###PAGE_TSCONFIG_IDLIST###) ',
        default => '',
    };

    // prepend category restriction at the beginning of foreign_table_where
    if (!empty($categoryRestriction)) {
        $tx_news_domain_model_news['columns']['categories']['config']['foreign_table_where'] = $categoryRestriction .
            $tx_news_domain_model_news['columns']['categories']['config']['foreign_table_where'];
    }
}

return $tx_news_domain_model_news;
