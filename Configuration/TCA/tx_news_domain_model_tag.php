<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

return array(
	'ctrl' => array(
		'title' => $ll . 'tx_news_domain_model_tag',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'default_sortby' => 'ORDER BY title',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news') . 'Resources/Public/Icons/news_domain_model_tag.png',
		'searchFields' => 'uid,title',
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden,title'
	),
	'columns' => array(
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
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'title' => array(
			'exclude' => 0,
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

