<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xml:';

/**
 * Add extra fields to the sys_category record
 */
$newSysCategoryColumns = array(
	'pid' => array(
		'label' => 'pid',
		'config' => array(
			'type' => 'passthrough'
		)
	),
	'sorting' => array(
		'label' => 'sorting',
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
	'images' => array(
		'exclude' => 1,
		'l10n_mode' => 'mergeIfNotBlank',
		'label' => $ll . 'tx_news_domain_model_category.image',
		'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
			'images',
			array(
				'appearance' => array(
					'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
				),
				'foreign_match_fields' => array(
					'fieldname' => 'images',
					'tablenames' => 'sys_category',
					'table_local' => 'sys_file',
				),
			),
			$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
		)
	),
	'single_pid' => array(
		'exclude' => 1,
		'l10n_mode' => 'mergeIfNotBlank',
		'label' => $ll . 'tx_news_domain_model_category.single_pid',
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
	'shortcut' => array(
		'exclude' => 1,
		'l10n_mode' => 'mergeIfNotBlank',
		'label' => $ll . 'tx_news_domain_model_category.shortcut',
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
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category', $newSysCategoryColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category', '--div--;LLL:EXT:cms/locallang_tca.xls:pages.tabs.options, images', '', 'before:description');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category', 'single_pid', '', 'after:description');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category', 'shortcut', '', 'after:single_pid');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category', 'fe_group');

// add fe_group as enable field
$GLOBALS['TCA']['sys_category']['ctrl']['enablecolumns']['fe_group'] = 'fe_group';
