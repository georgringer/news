<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$ll = 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:';

	// extension manager configuration
$configurationArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news2']);

$TCA['tx_news2_domain_model_news'] = array(
	'ctrl' => $TCA['tx_news2_domain_model_news']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'cruser_id,pid,sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,teaser,bodytext,datetime,archive,author,author_email,categories,related,type,keywords,media,internalurl,externalurl,istopnews,related_files,related_links'
	),
	'feInterface' => $TCA['tx_news2_domain_model_news']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label'  => 'LLL:EXT:cms/locallang_ttc.xml:sys_language_uid_formlabel',
			'config' => array(
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table'       => 'tx_news2_domain_model_news',
				'foreign_table_where' => 'AND tx_news2_domain_model_news.pid=###CURRENT_PID### AND tx_news2_domain_model_news.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => 0
			)
		),
		'cruser_id' => array(
			'label'   => 'cruser_id',
			'config'  => array(
				'type'    => 'input'
			)
		),
		'pid' => array(
			'label'   => 'pid',
			'config'  => array(
				'type'    => 'input'
			)
		),
		'crdate' => array(
			'l10n_mode' => 'mergeIfNotBlank',
			'label'   => 'crdate',
			'config'  => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'default'  => 0,
			)
		),
		'tstamp' => array(
			'l10n_mode' => 'mergeIfNotBlank',
			'label'   => 'crdate',
			'config'  => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'default'  => 0,
			)
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label'   => 'LLL:EXT:cms/locallang_ttc.xml:starttime_formlabel',
			'config'  => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'default'  => 0,
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label'   => 'LLL:EXT:cms/locallang_ttc.xml:endtime_formlabel',
			'config'  => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'default'  => 0,
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
		'teaser' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_news.teaser',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'bodytext' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:bodytext_formlabel',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
				'wizards' => array(
					'_PADDING' => 2,
					'RTE' => array(
						'notNewRecords' => 1,
						'RTEonly'       => 1,
						'type'          => 'script',
						'title'         => 'Full screen Rich Text Editing|Formatteret redigering i hele vinduet',
						'icon'          => 'wizard_rte2.gif',
						'script'        => 'wizard_rte.php',
					),
				),
			)
		),
		'datetime' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_news.datetime',
			'config' => array(
				'type'     => 'input',
				'size'     => 12,
				'max'      => 20,
				'eval'     => 'datetime,required',
				'default'  => mktime(date('H'), date('i'), 0, date('m'), date('d'), date('Y'))
			)
		),
		'archive' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_news.archive',
			'config' => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'default'  => 0
			)
		),
		'author' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xml:pages.author_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'author_email' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xml:pages.author_email_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'categories' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_news.categories',
			'config' => array(
				'type' => 'select',
				'renderMode' => 'tree',
				'treeConfig' => array(
					'parentField' => 'parentcategory',
					'appearance' => array(
//						'expandAll' => TRUE,
						'showHeader' => TRUE,
						'allowRecursiveMode' => TRUE,
					),
				),
				'MM' => 'tx_news2_domain_model_news_category_mm',
				'foreign_table' => 'tx_news2_domain_model_category',
				'foreign_table_where' => ' AND (tx_news2_domain_model_category.sys_language_uid = 0 OR tx_news2_domain_model_category.l10n_parent = 0)',
				'size' => 10,
				'autoSizeMax' => 20,
				'minitems' => 0,
				'maxitems' => 20,
			)
		),
		'related' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_news.related',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_news2_domain_model_news',
				'foreign_table' => 'tx_news2_domain_model_news',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'MM' => 'tx_news2_domain_model_news_related_mm',
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest',
					),
				),
			)
		),
		'related_files' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_news.related_links',
			'config' => array(
				'type' => 'inline',
				'allowed' => 'tx_news2_domain_model_file',
				'foreign_table' => 'tx_news2_domain_model_file',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
//				'MM' => 'tx_news2_domain_model_news_file_mm',
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
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_news.related_links',
			'config' => array(
				'type' => 'inline',
				'allowed' => 'tx_news2_domain_model_link',
				'foreign_table' => 'tx_news2_domain_model_link',
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
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_tca.xml:pages.doktype_formlabel',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array($ll . 'tx_news2_domain_model_news.type.I.0', 0),
					array($ll . 'tx_news2_domain_model_news.type.I.1', 1),
					array($ll . 'tx_news2_domain_model_news.type.I.2', 2),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'keywords' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $TCA['pages']['columns']['keywords']['label'],
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'media' => array(
			'exclude' => 0,
			'label' => $ll . 'tx_news2_domain_model_news.media',

			'config' => array(
				'type' => 'inline',
				'foreign_sortby' => 'sorting',
				'foreign_table' => 'tx_news2_domain_model_media',
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
				'minitems' => 0,
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
			)
		),
		'istopnews' => array(
			'exclude' => 1,
			'label'   => $ll . 'tx_news2_domain_model_news.istopnews',
			'config'  => array(
				'type'    => 'check',
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
		'import_id' => array(
			'label'   => $ll . 'tx_news2_domain_model_news.import_id',
			'config' => array(
				'type' => 'input'
			)
		),
	),
	'types' => array(
			// default news
		'0' => array(
			'showitem' => 'l10n_parent, l10n_diffsource,
					title;;3,;;;;2-2-2, teaser;;;;3-3-3,author;;2,bodytext;;;richtext::rte_transform[flag=rte_enabled|mode=ts_css],

				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,

				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.options,categories,related, keywords,
				--div--;' . $ll . 'tx_news2_domain_model_news.tabs.relations,media,related_files,related_links,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.extended,'
		),
			// internal url
		'1' => array(
			'showitem' => 'l10n_parent, l10n_diffsource,
					title;;3,;;;;2-2-2, teaser;;;;3-3-3,author;;2,internalurl,

				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,

				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.options,categories,related, keywords,import_id,
				--div--;' . $ll . 'tx_news2_domain_model_news.tabs.relations,media,related_files,related_links,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.extended,'
		),
			// external url
		'2' => array(
			'showitem' => 'l10n_parent, l10n_diffsource,
					title;;3,;;;;2-2-2, teaser;;;;3-3-3,author;;2,externalurl,

				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,

				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.options,categories,related, keywords,import_id,
				--div--;' . $ll . 'tx_news2_domain_model_news.tabs.relations,media,related_files,related_links,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.extended,'
		),
	),
	'palettes' => array(
		'1' => array(
			'showitem' => 'endtime, fe_group,',
			'canNotCollapse' => TRUE
		),
		'2' => array(
			'showitem' => 'author_email,
							--linebreak--, datetime, archive,',
			'canNotCollapse' => TRUE
		),
		'3' => array(
			'showitem' => 'istopnews, type, sys_language_uid, hidden,',
			'canNotCollapse' => FALSE
		),
		'access' => array(
			'showitem' => 'starttime;LLL:EXT:cms/locallang_ttc.xml:starttime_formlabel, endtime;LLL:EXT:cms/locallang_ttc.xml:endtime_formlabel,
					--linebreak--, fe_group;LLL:EXT:cms/locallang_ttc.xml:fe_group_formlabel,
					--linebreak--,editlock,',
			'canNotCollapse' => TRUE,
		),
	)
);

	// category restriction based on settings in extension manager
if (isset($configurationArray['categoryRestriction'])) {
	$categoryRestriction = '';
	switch ($configurationArray['categoryRestriction']) {
		case 'current_pid':
			$categoryRestriction = ' AND tx_news2_domain_model_category.pid=###CURRENT_PID### ';
			break;
		case 'storage_pid':
			$categoryRestriction = ' AND tx_news2_domain_model_category.pid=###STORAGE_PID### ';
			break;
		case 'siteroot':
			$categoryRestriction = ' AND tx_news2_domain_model_category.pid IN (###SITEROOT###) ';
			break;
	}

	if (!empty ($categoryRestriction)) {
		$TCA['tx_news2_domain_model_news']['columns']['category']['config']['foreign_table_where'] .= $categoryRestriction;
	}

}

?>