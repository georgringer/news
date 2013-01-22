<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xml:';


$TCA['tx_news_domain_model_link'] = array(
	'ctrl' => $TCA['tx_news_domain_model_link']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title,description,uri'
	),
	'feInterface' => $TCA['tx_news_domain_model_link']['feInterface'],
	'columns' => array(
		'pid' => array(
			'config'  => array(
				'type'    => 'passthrough'
			)
		),
		'crdate' => array(
			'config'  => array(
				'type'     => 'passthrough',
			)
		),
		'tstamp' => array(
			'config'  => array(
				'type'     => 'passthrough',
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
				'foreign_table'       => 'tx_news_domain_model_link',
				'foreign_table_where' => 'AND tx_news_domain_model_link.pid=###CURRENT_PID### AND tx_news_domain_model_link.sys_language_uid IN (-1,0)',
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
		'title' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_link.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'description' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_link.description',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'uri' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_link.uri',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
				'wizards' => array(
					'_PADDING' => 2,
					'link' => array(
						'type' => 'popup',
						'title' => 'Link',
						'icon' => 'link_popup.gif',
						'script' => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
	),
	'types' => array(
		0 => array(
			'showitem' => 'uri;;palettteCore,title;;palettteDescription,'
		)
	),
	'palettes' => array(
		'palettteCore' => array(
			'showitem' => 'hidden,sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource,',
			'canNotCollapse' => TRUE
		),
		'palettteDescription' => array(
			'showitem' => 'description',
			'canNotCollapse' => FALSE
		)
	)
);


?>