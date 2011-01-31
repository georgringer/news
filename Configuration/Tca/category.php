<?php
/**
 * @version $Id$
 */

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

	// extension manager configuration
$configurationArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news2']);


$ll = 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:';

$TCA['tx_news2_domain_model_category'] = array(
	'ctrl' => $TCA['tx_news2_domain_model_category']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sorting,sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,description,image,parentcategory,single_pid,shortcut,'
	),
	'feInterface' => $TCA['tx_news2_domain_model_category']['feInterface'],
	'columns' => array(
		'pid' => array(
			'label'   => 'pid',
			'config'  => array(
				'type'    => 'input'
			)
		),
		'sorting' => array(
			'label'   => 'sorting',
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
			'label' => $ll . 'tx_news2_domain_model_category.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'required',
			)
		),
		'description' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_category.description',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'image' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_category.image',
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
			'l10n_mode' => 'exclude',
			'label' => $ll . 'tx_news2_domain_model_category.parentcategory',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_news2_domain_model_category',
				'foreign_table_where' => ' AND tx_news2_domain_model_category.pid = ###CURRENT_PID### AND tx_news2_domain_model_category.uid != ###THIS_UID###',
				'renderMode' => 'tree',
				'subType' => 'db',
				'treeConfig' => array(
					'parentField' => 'parentcategory',
					'appearance' => array(
						'expandAll' => TRUE,
						'showHeader' => FALSE,
					),
				),
				'size' => 10,
				'autoSizeMax' => 20,
				'minitems' => 0,
				'maxitems' => 2
			)
		),
		'single_pid' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_category.single_pid',
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
		'shortcut' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_category.shortcut',
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
		'import_id' => array(
			'label'   => $ll . 'tx_news2_domain_model_news.import_id',
			'config' => array(
				'type' => 'input'
			)
		),
	),
	'types' => array(
		0 => array(
			'showitem' =>
				'title;;1, parentcategory, ;;;;3-3-3,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.options, image, description;;;;3-3-3,single_pid;;;;3-3-3,shortcut,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,
				--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.extended,
'
		)
	),
	'palettes' => array(
		'1' => array(
			'showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource,hidden,',
			'canNotCollapse' => TRUE,
		),
		'access' => array(
			'showitem' => 'starttime;LLL:EXT:cms/locallang_ttc.xml:starttime_formlabel, endtime;LLL:EXT:cms/locallang_ttc.xml:endtime_formlabel,
					--linebreak--, fe_group;LLL:EXT:cms/locallang_ttc.xml:fe_group_formlabel',
			'canNotCollapse' => TRUE,
		),
	)
);

?>