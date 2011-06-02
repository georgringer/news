<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xml:';


$TCA['tx_news_domain_model_tag'] = array(
	'ctrl' => $TCA['tx_news_domain_model_tag']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title'
	),
	'feInterface' => $TCA['tx_news_domain_model_tag']['feInterface'],
	'columns' => array(
		'pid' => array(
			'config'  => array(
				'type'    => 'input'
			)
		),
		'crdate' => array(
			'l10n_mode' => 'mergeIfNotBlank',
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
			'config'  => array(
				'type'     => 'input',
				'size'     => 8,
				'max'      => 20,
				'eval'     => 'date',
				'default'  => 0,
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
			'label' => $ll . 'tx_news_domain_model_tag.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'required,unique,trim',
			)
		),
	),
	'types' => array(
		0 => array(
			'showitem' => 'title;;palletteCore,'
		)
	),
	'palettes' => array(
		'palletteCore' => array(
			'showitem' => 'hidden,',
			'canNotCollapse' => TRUE
		),
	)
);

?>