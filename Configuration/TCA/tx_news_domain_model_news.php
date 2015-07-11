<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

// Extension manager configuration
$configuration = \GeorgRinger\News\Utility\EmConfiguration::getSettings();

$teaserRteConfiguration = $configuration->getRteForTeaser() ? ';;;richtext::rte_transform[flag=rte_disabled|mode=ts_css]' : '';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_news_domain_model_news');

$tx_news_domain_model_news = array(
	'ctrl' => array(
		'title' => $ll . 'tx_news_domain_model_news',
		'descriptionColumn' => 'tag',
		'label' => 'title',
		'prependAtCopy' => $configuration->getPrependAtCopy() ? 'LLL:EXT:lang/locallang_general.xlf:LGL.prependAtCopy' : '',
		'hideAtCopy' => TRUE,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => TRUE,
		'origUid' => 't3_origuid',
		'editlock' => 'editlock',
		'type' => 'type',
		'typeicon_column' => 'type',
		'typeicons' => array(
			'1' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news') . 'Resources/Public/Icons/news_domain_model_news_internal.gif',
			'2' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news') . 'Resources/Public/Icons/news_domain_model_news_external.gif',
		),
		'dividers2tabs' => TRUE,
		'useColumnsForDefaultValues' => 'type',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY datetime DESC',
		'sortby' => ($configuration->getManualSorting() ? 'sorting' : ''),
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news') . 'Resources/Public/Icons/news_domain_model_news.gif',
		'searchFields' => 'uid,title',
		'requestUpdate' => 'rte_disabled',
	),
	'interface' => array(
		'showRecordFieldList' => 'cruser_id,pid,sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,teaser,bodytext,datetime,archive,author,author_email,categories,related,type,keywords,media,internalurl,externalurl,istopnews,related_files,related_links,content_elements,tags,path_segment,alternative_title,fal_related_files'
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:sys_language_uid_formlabel',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_news_domain_model_news',
				'foreign_table_where' => 'AND tx_news_domain_model_news.pid=###CURRENT_PID### AND tx_news_domain_model_news.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'cruser_id' => array(
			'label' => 'cruser_id',
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'pid' => array(
			'label' => 'pid',
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'crdate' => array(
			'label' => 'crdate',
			'config' => array(
				'type' => 'passthrough',
			)
		),
		'tstamp' => array(
			'label' => 'tstamp',
			'config' => array(
				'type' => 'passthrough',
			)
		),
		'sorting' => array(
			'label' => 'sorting',
			'config' => array(
				'type' => 'passthrough',
			)
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:starttime_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 8,
				'max' => 20,
				'eval' => 'datetime',
				'default' => 0,
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:endtime_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 8,
				'max' => 20,
				'eval' => 'datetime',
				'default' => 0,
			)
		),
		'fe_group' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.fe_group',
			'config' => array(
				'type' => 'select',
				'size' => 5,
				'maxitems' => 20,
				'items' => array(
					array(
						'LLL:EXT:lang/locallang_general.xlf:LGL.hide_at_login',
						-1,
					),
					array(
						'LLL:EXT:lang/locallang_general.xlf:LGL.any_login',
						-2,
					),
					array(
						'LLL:EXT:lang/locallang_general.xlf:LGL.usergroups',
						'--div--',
					),
				),
				'exclusiveKeys' => '-1,-2',
				'foreign_table' => 'fe_groups',
				'foreign_table_where' => 'ORDER BY fe_groups.title',
			),
		),
		'title' => array(
			'exclude' => 0,
			'l10n_mode' => 'prefixLangTitle',
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:header_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 60,
				'eval' => 'required',
			)
		),
		'alternative_title' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_news_domain_model_news.alternative_title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'teaser' => array(
			'exclude' => 1,
			'l10n_mode' => 'noCopy',
			'label' => $ll . 'tx_news_domain_model_news.teaser',
			'config' => array(
				'type' => 'text',
				'cols' => 60,
				'rows' => 5,
			)
		),
		'bodytext' => array(
			'exclude' => 0,
			'l10n_mode' => 'noCopy',
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext_formlabel',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
				'softref' => 'rtehtmlarea_images,typolink_tag,images,email[subst],url',
				'wizards' => array(
					'_PADDING' => 2,
					'RTE' => array(
						'notNewRecords' => 1,
						'RTEonly' => 1,
						'type' => 'script',
						'title' => 'Full screen Rich Text Editing',
						'icon' => 'wizard_rte2.gif',
						'module' => array(
							'name' => 'wizard_rte',
						),
					),
				),
			)
		),
		'rte_disabled' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:rte_enabled',
			'config' => array(
				'type' => 'check',
				'showIfRTE' => 1,
				'items' => array(
					'1' => array(
						'0' => 'LLL:EXT:cms/locallang_ttc.xlf:rte_enabled.I.0'
					)
				)
			)
		),
		'datetime' => array(
			'exclude' => 0,
			'label' => $ll . 'tx_news_domain_model_news.datetime',
			'config' => array(
				'type' => 'input',
				'size' => 12,
				'max' => 20,
				'eval' => 'datetime,required',
			)
		),
		'archive' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_news.archive',
			'config' => array(
				'type' => 'input',
				'placeholder' => $ll . 'tx_news_domain_model_news.archive.placeholder',
				'size' => 30,
				'max' => 20,
				'eval' => $configuration->getArchiveDate(),
				'default' => 0
			)
		),
		'author' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xlf:pages.author_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'author_email' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xlf:pages.author_email_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'categories' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_news.categories',
			'config' => array(
				'type' => 'select',
				'renderMode' => 'tree',
				'treeConfig' => array(
					'dataProvider' => 'GeorgRinger\\News\\TreeProvider\\DatabaseTreeDataProvider',
					'parentField' => 'parent',
					'appearance' => array(
						'showHeader' => TRUE,
						'allowRecursiveMode' => TRUE,
						'expandAll' => TRUE,
						'maxLevels' => 99,
					),
				),
				'MM' => 'sys_category_record_mm',
				'MM_match_fields' => array(
					'fieldname' => 'categories',
					'tablenames' => 'tx_news_domain_model_news',
				),
				'MM_opposite_field' => 'items',
				'foreign_table' => 'sys_category',
				'foreign_table_where' => ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0) ORDER BY sys_category.sorting',
				'size' => 10,
				'autoSizeMax' => 20,
				'minitems' => 0,
				'maxitems' => 99,
			)
		),
		'related' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_news.related',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_news_domain_model_news',
				'foreign_table' => 'tx_news_domain_model_news',
				'MM_opposite_field' => 'related_from',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 100,
				'MM' => 'tx_news_domain_model_news_related_mm',
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest',
					),
				),
			)
		),
		'related_from' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_news_domain_model_news.related_from',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'foreign_table' => 'tx_news_domain_model_news',
				'allowed' => 'tx_news_domain_model_news',
				'size' => 5,
				'maxitems' => 100,
				'MM' => 'tx_news_domain_model_news_related_mm',
				'readOnly' => 1,
			)
		),
		'related_files' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_news.related_files',
			'config' => array(
				'type' => 'inline',
				'allowed' => 'tx_news_domain_model_file',
				'foreign_table' => 'tx_news_domain_model_file',
				'foreign_sortby' => 'sorting',
				'foreign_field' => 'parent',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 100,
				'appearance' => array(
					'collapseAll' => 1,
					'expandSingle' => 1,
					'levelLinksPosition' => 'bottom',
					'useSortable' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showRemovedLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1,
					'showSynchronizationLink' => 1,
					'enabledControls' => array(
						'info' => FALSE,
					)
				)
			)
		),
		'related_links' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_news.related_links',
			'config' => array(
				'type' => 'inline',
				'allowed' => 'tx_news_domain_model_link',
				'foreign_table' => 'tx_news_domain_model_link',
				'foreign_sortby' => 'sorting',
				'foreign_field' => 'parent',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 100,
				'appearance' => array(
					'collapseAll' => 1,
					'expandSingle' => 1,
					'levelLinksPosition' => 'bottom',
					'useSortable' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showRemovedLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1,
					'showSynchronizationLink' => 1,
					'enabledControls' => array(
						'info' => FALSE,
					)
				)
			)
		),
		'type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cms/locallang_tca.xlf:pages.doktype_formlabel',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array($ll . 'tx_news_domain_model_news.type.I.0', 0),
					array($ll . 'tx_news_domain_model_news.type.I.1', 1),
					array($ll . 'tx_news_domain_model_news.type.I.2', 2),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'keywords' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $GLOBALS['TCA']['pages']['columns']['keywords']['label'],
			'config' => array(
				'type' => 'text',
				'placeholder' => $ll . 'tx_news_domain_model_news.keywords.placeholder',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'description' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xlf:pages.description_formlabel',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'media' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_news_domain_model_news.media',
			'l10n_mode' => 'mergeIfNotBlank',
			'config' => array(
				'type' => 'inline',
				'foreign_sortby' => 'sorting',
				'foreign_table' => 'tx_news_domain_model_media',
				'foreign_field' => 'parent',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 99,
				'appearance' => array(
					'collapseAll' => 1,
					'expandSingle' => 1,
					'levelLinksPosition' => 'bottom',
					'useSortable' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showRemovedLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1,
					'showSynchronizationLink' => 1,
					'enabledControls' => array(
						'info' => FALSE,
					)
				)
			)
		),
		'internalurl' => array(
			'exclude' => 0,
			'label' => $ll . 'tx_news_domain_model_news.type.I.1',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'max' => '255',
				'eval' => 'trim,required',
				'wizards' => array(
					'_PADDING' => 2,
					'link' => array(
						'type' => 'popup',
						'title' => 'LLL:EXT:cms/locallang_ttc.xlf:header_link_formlabel',
						'icon' => 'link_popup.gif',
						'module' => array(
							'name' => 'wizard_element_browser',
							'urlParameters' => array(
								'mode' => 'wizard'
							)
						),
						'JSopenParams' => 'height=600,width=800,status=0,menubar=0,scrollbars=1'
					)
				),
				'softref' => 'typolink'
			)
		),
		'externalurl' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cms/locallang_tca.xlf:pages.doktype.I.8',
			'config' => array(
				'type' => 'input',
				'size' => 50,
				'eval' => 'required',
				'softref' => 'news_externalurl'
			)
		),
		'istopnews' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_news_domain_model_news.istopnews',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'editlock' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_tca.xlf:editlock',
			'config' => array(
				'type' => 'check'
			)
		),
		'content_elements' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_news.content_elements',
			'config' => array(
				'type' => 'inline',
				'allowed' => 'tt_content',
				'foreign_table' => 'tt_content',
				'foreign_sortby' => 'sorting',
				'foreign_field' => 'tx_news_related_news',
				'minitems' => 0,
				'maxitems' => 99,
				'appearance' => array(
					'collapseAll' => 1,
					'expandSingle' => 1,
					'levelLinksPosition' => 'bottom',
					'useSortable' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showRemovedLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1,
					'showSynchronizationLink' => 1,
					'enabledControls' => array(
						'info' => FALSE,
					)
				)
			)
		),
		'tags' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_news.tags',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_news_domain_model_tag',
				'MM' => 'tx_news_domain_model_news_tag_mm',
				'foreign_table' => 'tx_news_domain_model_tag',
				'foreign_table_where' => 'ORDER BY tx_news_domain_model_tag.title',
				'size' => 10,
				'autoSizeMax' => 20,
				'minitems' => 0,
				'maxitems' => 99,
				'wizards' => array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'suggest' => array(
						'type' => 'suggest',
						'default' => array(
							'receiverClass' => 'GeorgRinger\\News\\Hooks\\SuggestReceiver' . (\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.3') ? '7' : '')
						),
					),
					'list' => array(
						'type' => 'script',
						'title' => $ll . 'tx_news_domain_model_news.tags.list',
						'icon' => 'list.gif',
						'params' => array(
							'table' => 'tx_news_domain_model_tag',
							'pid' => $configuration->getTagPid(),
						),
						'module' => array(
							'name' => 'wizard_list',
						),
					),
					'edit' => array(
						'type' => 'popup',
						'title' => $ll . 'tx_news_domain_model_news.tags.edit',
						'module' => array(
							'name' => 'wizard_edit',
						),
						'popup_onlyOpenIfSelected' => 1,
						'icon' => 'edit2.gif',
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
					),
				),
			),
		),
		'path_segment' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_news_domain_model_news.path_segment',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'nospace,alphanum_x,lower,unique',
			)
		),
		'import_id' => array(
			'label' => $ll . 'tx_news_domain_model_news.import_id',
			'config' => array(
				'type' => 'passthrough'
			)
		),

		'import_source' => array(
			'label' => $ll . 'tx_news_domain_model_news.import_source',
			'config' => array(
				'type' => 'passthrough'
			)
		)
	),
	'types' => array(
		// default news
		'0' => array(
			'showitem' => 'l10n_parent, l10n_diffsource,
					title;;paletteCore,teaser' . $teaserRteConfiguration . ',author;;paletteAuthor,datetime;;paletteArchive,
					bodytext;;;richtext::rte_transform[flag=rte_disabled|mode=ts_css],
					rte_disabled;LLL:EXT:cms/locallang_ttc.xlf:rte_enabled_formlabel,
					content_elements,

				--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.access;paletteAccess,

				--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.options,categories,tags,
				--div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,media,related_files,related_links,related,related_from,
				--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.metadata,
					--palette--;LLL:EXT:cms/locallang_tca.xlf:pages.palettes.metatags;metatags,
					--palette--;' . $ll . 'tx_news_domain_model_news.palettes.alternativeTitles;alternativeTitles,
				--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.extended,'
		),
		// internal url
		'1' => array(
			'showitem' => 'l10n_parent, l10n_diffsource,
					title;;paletteCore, teaser' . $teaserRteConfiguration . ',author;;paletteAuthor,datetime;;paletteArchive,internalurl,

				--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.access;paletteAccess,

				--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.options,categories,tags,
				--div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,media,related_files,related_links,related,related_from,
				--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.metadata,
					--palette--;LLL:EXT:cms/locallang_tca.xlf:pages.palettes.metatags;metatags,
					--palette--;' . $ll . 'tx_news_domain_model_news.palettes.alternativeTitles;alternativeTitles,
				--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.extended,'
		),
		// external url
		'2' => array(
			'showitem' => 'l10n_parent, l10n_diffsource,
					title;;paletteCore, teaser' . $teaserRteConfiguration . ',author;;paletteAuthor,datetime;;paletteArchive,externalurl,

				--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.access;paletteAccess,

				--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.options,categories,tags,
				--div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,media,related_files,related_links,related,related_from,
				--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.metadata,
					--palette--;LLL:EXT:cms/locallang_tca.xlf:pages.palettes.metatags;metatags,
					--palette--;' . $ll . 'tx_news_domain_model_news.palettes.alternativeTitles;alternativeTitles,
				--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.extended,'
		),
	),
	'palettes' => array(
		'paletteAuthor' => array(
			'showitem' => 'author_email,',
			'canNotCollapse' => TRUE
		),
		'paletteArchive' => array(
			'showitem' => 'archive,',
			'canNotCollapse' => TRUE
		),
		'paletteCore' => array(
			'showitem' => 'istopnews, type, sys_language_uid, hidden,',
			'canNotCollapse' => FALSE
		),
		'paletteNavtitle' => array(
			'showitem' => 'alternative_title,path_segment',
			'canNotCollapse' => FALSE
		),
		'paletteAccess' => array(
			'showitem' => 'starttime;LLL:EXT:cms/locallang_ttc.xlf:starttime_formlabel,
					endtime;LLL:EXT:cms/locallang_ttc.xlf:endtime_formlabel,
					--linebreak--, fe_group;LLL:EXT:cms/locallang_ttc.xlf:fe_group_formlabel,
					--linebreak--,editlock,',
			'canNotCollapse' => TRUE,
		),
		'metatags' => array(
			'showitem' => 'keywords,--linebreak--,description,',
			'canNotCollapse' => 1
		),
		'alternativeTitles' => array(
			'showitem' => 'alternative_title,--linebreak--,path_segment',
			'canNotCollapse' => 1
		),
	)
);

if (\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.3')) {
	unset($tx_news_domain_model_news['columns']['rte_disabled']);
}

// category restriction based on settings in extension manager
$categoryRestrictionSetting = $configuration->getCategoryRestriction();
if ($categoryRestrictionSetting) {
	$categoryRestriction = '';
	switch ($categoryRestrictionSetting) {
		case 'current_pid':
			$categoryRestriction = ' AND sys_category.pid=###CURRENT_PID### ';
			break;
		case 'storage_pid':
			$categoryRestriction = ' AND sys_category.pid=###STORAGE_PID### ';
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
	if (!empty ($categoryRestriction)) {
		$tx_news_domain_model_news['columns']['categories']['config']['foreign_table_where'] = $categoryRestriction .
			$tx_news_domain_model_news['columns']['categories']['config']['foreign_table_where'];
	}
}

if ($configuration->getUseFal()) {

	$tx_news_domain_model_news['columns']['fal_media'] = array(
		'exclude' => 1,
		'l10n_mode' => 'mergeIfNotBlank',
		'label' => $ll . 'tx_news_domain_model_news.fal_media',
		'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'fal_media',
				array(
					'appearance' => array(
						'createNewRelationLinkTitle' => $ll . 'tx_news_domain_model_news.fal_media.add',
						'showPossibleLocalizationRecords' => 1,
						'showRemovedLocalizationRecords' => 1,
						'showAllLocalizationLink' => 1,
						'showSynchronizationLink' => 1
					),
					'foreign_match_fields' => array(
						'fieldname' => 'fal_media',
						'tablenames' => 'tx_news_domain_model_news',
						'table_local' => 'sys_file',
					),
					// custom configuration for displaying fields in the overlay/reference table
					// to use the newsPalette and imageoverlayPalette instead of the basicoverlayPalette
					'foreign_types' => array(
						'0' => array(
							'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
						),
						\TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => array(
							'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
						),
						\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => array(
							'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
						),
						\TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => array(
							'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
						),
						\TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => array(
							'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
						),
						\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => array(
							'showitem' => '
						--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;newsPalette,
						--palette--;;imageoverlayPalette,
						--palette--;;filePalette'
						)
					)
				),
				$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] . ',flv,mp4'
			)
	);
	$tx_news_domain_model_news['columns']['fal_related_files'] = array(
		'exclude' => 1,
		'l10n_mode' => 'mergeIfNotBlank',
		'label' => '' . $ll . 'tx_news_domain_model_news.fal_related_files',
		'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'fal_related_files',
				array(
					'appearance' => array(
						'createNewRelationLinkTitle' => $ll . 'tx_news_domain_model_news.fal_related_files.add',
						'showPossibleLocalizationRecords' => 1,
						'showRemovedLocalizationRecords' => 1,
						'showAllLocalizationLink' => 1,
						'showSynchronizationLink' => 1
					),
					'foreign_match_fields' => array(
						'fieldname' => 'fal_related_files',
						'tablenames' => 'tx_news_domain_model_news',
						'table_local' => 'sys_file',
					),
				)
			)
	);

	// only use FAL
	if ($configuration->getUseFal() === 1) {
		foreach ($tx_news_domain_model_news['types'] as $key => $config) {
			$tx_news_domain_model_news['types'][$key]['showitem'] = str_replace(array(',media,', ',related_files,'), array(',fal_media,', ',fal_related_files,'), $config['showitem']);
		}
		unset($tx_news_domain_model_news['columns']['media']);
		unset($tx_news_domain_model_news['columns']['related_files']);

		// use FAL and media multimedia
	} elseif ($configuration->getUseFal() === 3) {

		foreach ($tx_news_domain_model_news['types'] as $key => $config) {
			$tx_news_domain_model_news['types'][$key]['showitem'] = str_replace(array(',media,', ',related_files,'), array(',fal_media,media,', ',fal_related_files,'), $config['showitem']);
		}
		unset($tx_news_domain_model_news['columns']['related_files']);

		// change media label
		$tx_news_domain_model_news['columns']['media']['label'] = $ll . 'tx_news_domain_model_media.type.I.1';

		// use both
	} else {
		foreach ($tx_news_domain_model_news['types'] as $key => $config) {
			$tx_news_domain_model_news['types'][$key]['showitem'] = str_replace(array(',media,', ',related_files,'), array(',fal_media,media,', ',fal_related_files,related_files,'), $config['showitem']);
		}
	}
}

if (!$configuration->getContentElementRelation()) {
	unset($tx_news_domain_model_news['columns']['content_elements']);
}

return $tx_news_domain_model_news;