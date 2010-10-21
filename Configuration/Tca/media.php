<?php
/**
 * @version $Id$
 */

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$ll = 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:';


$TCA['tx_news2_domain_model_media'] = array(
	'ctrl' => $TCA['tx_news2_domain_model_media']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,title,media,type,html,video,showInPreview'
	),
	'feInterface' => $TCA['tx_news2_domain_model_media']['feInterface'],
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
		'title' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_media.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'showinpreview' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_news2_domain_model_media.showinpreview',
			'config'  => array(
				'type'    => 'check',
				'default' => 0
			)
		),
		'media' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_media.media',
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
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_media.type',
			'config' => array(
				'type' => 'select',
				'itemsProcFunc' => 'tx_news2_itemsProcFunc->user_MediaType',
				'items' => array(
					array($ll . 'tx_news2_domain_model_media.type.I.0', '0', t3lib_extMgm::extRelPath('news2') . 'Resources/Public/Icons/media_type_image.png'),
					array($ll . 'tx_news2_domain_model_media.type.I.1', '1', t3lib_extMgm::extRelPath('news2') . 'Resources/Public/Icons/media_type_movie.png'),
					array($ll . 'tx_news2_domain_model_media.type.I.2', '2', t3lib_extMgm::extRelPath('news2') . 'Resources/Public/Icons/media_type_html.png'),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'html' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_media.html',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			)
		),
		'video' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news2_domain_model_media.video',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'dam' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' =>  $ll . 'tx_news2_domain_model_media.media',
			'config' => array(
				'form_type' => 'user',
				'userFunc' => 'EXT:dam/lib/class.tx_dam_tcefunc.php:&tx_dam_tceFunc->getSingleField_typeMedia',

				'userProcessClass' => 'EXT:mmforeign/class.tx_mmforeign_tce.php:tx_mmforeign_tce',
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_dam',
				'prepend_tname' => 1,
				'foreign_table'       => 'tx_dam',
				'MM' => 'tx_dam_mm_ref',
				'MM_opposite_field' => 'file_usage',
				'MM_match_fields' => array('ident' => 'tx_news2_media'),

				'allowed_types' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],

				'max_size' => '1',
				'show_thumbs' => 1,
				'size' => 1,
				'maxitems' => 200,
				'minitems' => 0,
				'autoSizeMax' => 30,
			)
		)
	),
	'types' => array(
			// image
		0 => array('showitem' => 'title;;1;;, media'),
			// video
		'1' => array('showitem' => 'title;;1;;, video'),
			// HTML
		'2' => array('showitem' => 'title;;1;;, html'),
			// DAM
		'3' => array('showitem' => 'title;;1;;, dam')
	),
	'palettes' => array(
		'1' => array(
			'showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, type, --linebreak--,
							showinpreview, hidden',
			'canNotCollapse' => TRUE
		)
	)
);

	// extension manager configuration
$configurationArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news2']);

	// Hide DAM field if not used to avoid errors
if($configurationArray['enableDam'] != 1) {
	unset($TCA['tx_news2_domain_model_media']['columns']['dam']);
}

?>