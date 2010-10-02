<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_news2_domain_model_category'] = array(
	'ctrl' => $TCA['tx_news2_domain_model_category']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,description,image,parentcategory,single_pid'
	),
	'feInterface' => $TCA['tx_news2_domain_model_category']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
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
				'foreign_table'       => 'tx_news2_domain_model_category',
				'foreign_table_where' => 'AND tx_news2_domain_model_category.pid=###CURRENT_PID### AND tx_news2_domain_model_category.sys_language_uid IN (-1,0)',
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
		'starttime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'default'  => 0,
				'checkbox' => 0
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'checkbox' => 0,
				'default'  => 0,
				'range'    => array(
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_category.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'required',
			)
		),
		'description' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_category.description',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'image' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_category.image',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
				'uploadfolder' => 'uploads/tx_news',
				'show_thumbs' => 1,
				'size' => 3,
				'minitems' => 0,
				'maxitems' => 5,
			)
		),
		'parentcategory' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_category.parentcategory',
			'config' => array(
				'type' => 'select',
					'form_type' => 'user',
					'userFunc' => 'tx_news2_treeview->displayCategoryTree',
					'treeView' => 1,
					'treeName' => 'newscategorytree',
					'foreign_table' => ' tx_news2_domain_model_category',
					'size' => 5,
					'autoSizeMax' => 10,
					'minitems' => 0,
					'maxitems' => 5,
			)
		),
		'single_pid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_category.single_pid',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 1,
				'maxitems' => 1,
				'minitems' => 0,
				'show_thumbs' => 1
			)
		),
	),
	'types' => array(
		0 => array(
			'showitem' =>
				'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, title;;;;2-2-2, parentcategory, ;;;;3-3-3, ,
				--div--;LLL:EXT:tt_news/locallang_tca.xml:tt_news.tabs.catAndRels, image, description;;;;3-3-3,single_pid;;;;3-3-3,
				--div--;LLL:EXT:tt_news/locallang_tca.xml:tt_news.tabs.access, hidden,starttime,endtime,fe_group,
				--div--;LLL:EXT:tt_news/locallang_tca.xml:tt_news.tabs.extended,
'
		)
	),
	'palettes' => array(
//		'1' => array('showitem' => '')
	)
);



$TCA['tx_news2_domain_model_news'] = array(
	'ctrl' => $TCA['tx_news2_domain_model_news']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,teaser,bodytext,datetime,archive,author,category,related,type,keywords,media,internalurl,externalurl'
	),
	'feInterface' => $TCA['tx_news2_domain_model_news']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
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
		'starttime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'default'  => 0,
				'checkbox' => 0
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'checkbox' => 0,
				'default'  => 0,
				'range'    => array(
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'required',
			)
		),
		'teaser' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.teaser',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'bodytext' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.bodytext',
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
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.datetime',
			'config' => array(
				'type'     => 'input',
				'size'     => 12,
				'max'      => 20,
				'eval'     => 'date,required',
				'checkbox' => 0,
				'default'  => 0
			)
		),
		'archive' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.archive',
			'config' => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'checkbox' => 0,
				'default'  => 0
			)
		),
		'author' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.author',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'category' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.category',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_news2_domain_model_category',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'MM' => 'tx_news2_domain_model_news_category_mm',
				'foreign_table' => 'tx_news2_domain_model_category',
				'wizards' => array(
      				'suggest' => array(
        				'type' => 'suggest',
					),
				),
			)
		),
		'related' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.related',
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
		'type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.type.I.0', 0, t3lib_extMgm::extRelPath('news2') . 'Resources/Public/Icons/selicon_tx_news2_domain_model_news_type_0.gif'),
					array('LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.type.I.1', '1', t3lib_extMgm::extRelPath('news2') . 'Resources/Public/Icons/selicon_tx_news2_domain_model_news_type_1.gif'),
					array('LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.type.I.2', '2', t3lib_extMgm::extRelPath('news2') . 'Resources/Public/Icons/selicon_tx_news2_domain_model_news_type_2.gif'),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'keywords' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.keywords',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'media' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.media',
			'config' => array(
//				'type' => 'select',
//				'foreign_table' => 'tx_news2_domain_model_media',
//				'foreign_table_where' => 'ORDER BY tx_news2_domain_model_media.sorting',
//				'size' => 5,
//				'minitems' => 0,
//				'maxitems' => 10,
				'type' => 'inline',
				'foreign_table' => 'tx_news2_domain_model_media',
//				'foreign_table_where' => 'ORDER BY tx_news2_domain_model_media.uid',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'appearance' => array(
					'collapseAll' => 1,
					'expandSingle' => 1,
					'newRecordLinkPosition' => 'both',
					'useSortable' => 1
				)
			)
		),
		'internalurl' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.internalurl',
			'config' => array(
				'type' => 'input',
				'size' => 50,
			)
		),
		'externalurl' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_news.externalurl',
			'config' => array(
				'type' => 'input',
				'size' => 50,
			)
		),
	),
	'types' => array(
			// default news
		'0' => array(
			'showitem' =>
				'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title;;3,;;;;2-2-2, teaser;;;;3-3-3,datetime;;2,
				--div--;Main content,
					bodytext;;;richtext[]:rte_transform[mode=ts],
				--div--;Additional information,category, related, keywords,
				--div--;Media,media'
		),
			// internal url
		'1' => array(
			'showitem' =>
				'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title;;3,;;;;2-2-2, teaser;;;;3-3-3,datetime;;2,internalurl,
				--div--;Additional information,category, related, keywords,
				--div--;Media,media'
		),

			// external url
		'2' => array(
			'showitem' =>
				'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title;;3,;;;;2-2-2, teaser;;;;3-3-3,datetime;;2,externalurl,
				--div--;Additional information,category, related, keywords,
				--div--;Media,media'
		)
	),
	'palettes' => array(
		'1' => array(
			'showitem' => 'starttime, endtime, fe_group',
			'canNotCollapse' => TRUE
		),
		'2' => array(
			'showitem' => 'archive',
			'canNotCollapse' => TRUE
		),
		'3' => array(
			'showitem' => 'type,author',
			'canNotCollapse' => TRUE
		),
	)
);



$TCA['tx_news2_domain_model_media'] = array(
	'ctrl' => $TCA['tx_news2_domain_model_media']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title,media,type,html,video'
	),
	'feInterface' => $TCA['tx_news2_domain_model_media']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => 0
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'media' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media.media',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
				'uploadfolder' => 'uploads/tx_news',
				'show_thumbs' => 1,
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media.type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media.type.I.0', 0, t3lib_extMgm::extRelPath('news2').'Resources/Public/Icons/selicon_tx_news2_domain_model_media_type_image.png'),
					array('LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media.type.I.1', '1', t3lib_extMgm::extRelPath('news2').'Resources/Public/Icons/selicon_tx_news2_domain_model_media_type_movie.png'),
					array('LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media.type.I.2', '2', t3lib_extMgm::extRelPath('news2').'Resources/Public/Icons/selicon_tx_news2_domain_model_media_type_html.png'),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'html' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media.html',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'video' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:tx_news2_domain_model_media.video',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
	),
	'types' => array(
			// image
		0 => array('showitem' => 'hidden;;1;;1-1-1, type,title;;;;2-2-2, media'),
			// video
		'1' => array('showitem' => 'hidden;;1;;1-1-1, type,title,video'),
			// html
		'2' => array('showitem' => 'hidden;;1;;1-1-1, type,title,html')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);
?>