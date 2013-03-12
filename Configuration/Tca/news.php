<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xml:';

// Extension manager configuration
$configuration = Tx_News_Utility_EmConfiguration::getSettings();

$TCA['tx_news_domain_model_news'] = array(
	'ctrl' => $TCA['tx_news_domain_model_news']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'cruser_id,pid,sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,teaser,bodytext,datetime,archive,author,author_email,categories,related,type,keywords,media,internalurl,externalurl,istopnews,related_files,related_links,content_elements,tags,path_segment,alternative_title'
	),
	'feInterface' => $TCA['tx_news_domain_model_news']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:sys_language_uid_formlabel',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
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
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
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
		'is_dummy_record' => array(
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
			'label' => 'crdate',
			'config' => array(
				'type' => 'passthrough',
			)
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:starttime_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 8,
				'max' => 20,
				'eval' => 'date',
				'default' => 0,
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:endtime_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 8,
				'max' => 20,
				'eval' => 'date',
				'default' => 0,
			)
		),
		'fe_group' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config' => array(
				'type' => 'select',
				'size' => 5,
				'maxitems' => 20,
				'items' => array(
					array(
						'LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login',
						-1,
					),
					array(
						'LLL:EXT:lang/locallang_general.xml:LGL.any_login',
						-2,
					),
					array(
						'LLL:EXT:lang/locallang_general.xml:LGL.usergroups',
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
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:header_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'required',
			)
		),
		'alternative_title' => array(
			'exclude' => 0,
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
				'cols' => 30,
				'rows' => 5,
			)
		),
		'bodytext' => array(
			'exclude' => 0,
			'l10n_mode' => 'noCopy',
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:bodytext_formlabel',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
				'wizards' => array(
					'_PADDING' => 2,
					'RTE' => array(
						'notNewRecords' => 1,
						'RTEonly' => 1,
						'type' => 'script',
						'title' => 'Full screen Rich Text Editing',
						'icon' => 'wizard_rte2.gif',
						'script' => 'wizard_rte.php',
					),
				),
			)
		),
		'rte_disabled' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:rte_enabled',
			'config' => array(
				'type' => 'check',
				'showIfRTE' => 1,
				'items' => array(
					'1' => array(
						'0' => 'LLL:EXT:cms/locallang_ttc.xml:rte_enabled.I.0'
					)
				)
			)
		),
		'datetime' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_news.datetime',
			'config' => array(
				'type' => 'input',
				'size' => 12,
				'max' => 20,
				'eval' => 'datetime,required',
				'default' => mktime(date('H'), date('i'), 0, date('m'), date('d'), date('Y'))
			)
		),
		'archive' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_news.archive',
			'config' => array(
				'type' => 'input',
				'size' => 8,
				'max' => 20,
				'eval' => $configuration->getArchiveDate(),
				'default' => 0
			)
		),
		'author' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xml:pages.author_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'author_email' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xml:pages.author_email_formlabel',
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
					'dataProvider' => 'Tx_News_TreeProvider_DatabaseTreeDataProvider',
					'parentField' => 'parentcategory',
					'appearance' => array(
						'showHeader' => TRUE,
						'allowRecursiveMode' => TRUE,
					),
				),
				'MM' => 'tx_news_domain_model_news_category_mm',
				'foreign_table' => 'tx_news_domain_model_category',
				'foreign_table_where' => ' AND (tx_news_domain_model_category.sys_language_uid = 0 OR tx_news_domain_model_category.l10n_parent = 0) ORDER BY tx_news_domain_model_category.sorting',
				'size' => 10,
				'autoSizeMax' => 20,
				'minitems' => 0,
				'maxitems' => 20,
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
				'maxitems' => 10,
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
				'maxitems' => 10,
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
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xml:pages.doktype_formlabel',
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
			'label' => $TCA['pages']['columns']['keywords']['label'],
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'media' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_news_domain_model_news.media',

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
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xml:pages.palettes.links',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 1,
				'maxitems' => 1,
				'minitems' => 1,
				'show_thumbs' => 1,
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest',
					),
				),
			)
		),
		'externalurl' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xml:pages.doktype.I.8',
			'config' => array(
				'type' => 'input',
				'size' => 50,
				'eval' => 'required'
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
			'label' => 'LLL:EXT:lang/locallang_tca.xml:editlock',
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
				'MM' => 'tx_news_domain_model_news_ttcontent_mm',
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
				'maxitems' => 20,
				'wizards' => array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'suggest' => array(
						'type' => 'suggest',
						'default' => array(
							'receiverClass' => 'Tx_News_Hooks_SuggestReceiver'
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
						'script' => 'wizard_list.php',
					),
					'edit' => array(
						'type' => 'popup',
						'title' => $ll . 'tx_news_domain_model_news.tags.edit',
						'script' => 'wizard_edit.php',
						'popup_onlyOpenIfSelected' => 1,
						'icon' => 'edit2.gif',
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
					),
				),
			),
		),
		'path_segment' => array(
			'exclude' => 0,
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
					title;;paletteCore,;;;;2-2-2, teaser;;paletteNavtitle,;;;;3-3-3,author;;paletteAuthor,datetime;;paletteArchive,
					bodytext;;;richtext::rte_transform[flag=rte_disabled|mode=ts_css],
					rte_disabled;LLL:EXT:cms/locallang_ttc.xml:rte_enabled_formlabel,
					content_elements,

				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;paletteAccess,

				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.options,categories,tags,keywords,
				--div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,media,related_files,related_links,related,related_from,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.extended,'
		),
		// internal url
		'1' => array(
			'showitem' => 'l10n_parent, l10n_diffsource,
					title;;paletteCore,;;;;2-2-2, teaser;;paletteNavtitle,;;;;3-3-3,author;;paletteAuthor,datetime;;paletteArchive,internalurl,

				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;paletteAccess,

				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.options,categories,tags,keywords,
				--div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,media,related_files,related_links,related,related_from,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.extended,'
		),
		// external url
		'2' => array(
			'showitem' => 'l10n_parent, l10n_diffsource,
					title;;paletteCore,;;;;2-2-2, teaser;;paletteNavtitle,;;;;3-3-3,author;;paletteAuthor,datetime;;paletteArchive,externalurl,

				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;paletteAccess,

				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.options,categories,tags,keywords,
				--div--;' . $ll . 'tx_news_domain_model_news.tabs.relations,media,related_files,related_links,related,related_from,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.extended,'
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
			'showitem' => 'starttime;LLL:EXT:cms/locallang_ttc.xml:starttime_formlabel,
					endtime;LLL:EXT:cms/locallang_ttc.xml:endtime_formlabel,
					--linebreak--, fe_group;LLL:EXT:cms/locallang_ttc.xml:fe_group_formlabel,
					--linebreak--,editlock,',
			'canNotCollapse' => TRUE,
		),
	)
);

// category restriction based on settings in extension manager
$categoryRestrictionSetting = $configuration->getCategoryRestriction();
if ($categoryRestrictionSetting) {
	$categoryRestriction = '';
	switch ($categoryRestrictionSetting) {
		case 'current_pid':
			$categoryRestriction = ' AND tx_news_domain_model_category.pid=###CURRENT_PID### ';
			break;
		case 'storage_pid':
			$categoryRestriction = ' AND tx_news_domain_model_category.pid=###STORAGE_PID### ';
			break;
		case 'siteroot':
			$categoryRestriction = ' AND tx_news_domain_model_category.pid IN (###SITEROOT###) ';
			break;
		case 'page_tsconfig':
			$categoryRestriction = ' AND tx_news_domain_model_category.pid IN (###PAGE_TSCONFIG_IDLIST###) ';
			break;
		default:
			$categoryRestriction = '';
	}

	// prepend category restriction at the beginning of foreign_table_where
	if (!empty ($categoryRestriction)) {
		$TCA['tx_news_domain_model_news']['columns']['categories']['config']['foreign_table_where'] = $categoryRestriction .
			$TCA['tx_news_domain_model_news']['columns']['categories']['config']['foreign_table_where'];
	}
}

if (!$configuration->getContentElementRelation()) {
	unset($TCA['tx_news_domain_model_news']['columns']['content_elements']);
}

?>