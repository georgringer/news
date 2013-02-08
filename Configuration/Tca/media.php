<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xml:';


$TCA['tx_news_domain_model_media'] = array(
	'ctrl' => $TCA['tx_news_domain_model_media']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,title,media,type,html,video,showInPreview, width, height'
	),
	'feInterface' => $TCA['tx_news_domain_model_media']['feInterface'],
	'columns' => array(
		'pid' => array(
			'label'   => 'pid',
			'config'  => array(
				'type'    => 'passthrough'
			)
		),
		'sorting' => array(
			'label'   => 'pid',
			'config'  => array(
				'type'    => 'passthrough'
			)
		),
		'crdate' => array(
			'label'   => 'crdate',
			'config'  => array(
				'type'     => 'passthrough',
			)
		),
		'tstamp' => array(
			'label'   => 'tstamp',
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
				'foreign_table'       => 'tx_news_domain_model_media',
				'foreign_table_where' => 'AND tx_news_domain_model_media.pid=###CURRENT_PID### AND tx_news_domain_model_media.sys_language_uid IN (-1,0)',
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
		'caption' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_media.caption',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'title' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:image_titleText',
			'config' => array(
				'type' => 'input',
				'size' => 20,
			)
		),
		'alt' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:image_altText',
			'config' => array(
				'type' => 'input',
				'size' => 20,
			)
		),
		'image' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_media.media',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
				'uploadfolder' => 'uploads/tx_news',
				'show_thumbs' => 1,
				'size' => 1,
				'minitems' => 1,
				'maxitems' => 1,
			)
		),
		'multimedia' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_media.multimedia',
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
		'dam' => array(
			'exclude' => 0,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_media.dam',
			'config' => array(
				'form_type'				=> 'user',
				'userFunc'				=> 'EXT:dam/lib/class.tx_dam_tcefunc.php:&tx_dam_tceFunc->getSingleField_typeMedia',
				'userProcessClass'		=> 'EXT:mmforeign/class.tx_mmforeign_tce.php:tx_mmforeign_tce',
				'type'					=> 'group',
				'internal_type'			=> 'db',
				'allowed'				=> 'tx_dam',
				'prepend_tname'			=> 1,
				'foreign_table'			=> 'tx_dam',
				'MM'					=> 'tx_dam_mm_ref',
				'MM_opposite_field'		=> 'file_usage',
				'MM_match_fields'		=> array(
												'ident' => 'tx_news_media'
											),
				'allowed_types'			=> $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],

				'max_size'				=> 1,
				'show_thumbs'			=> 1,
				'size'					=> 1,
				'maxitems'				=> 1,
				'minitems'				=> 0,
				'autoSizeMax'			=> 1,
			)
		),
		'showinpreview' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_news_domain_model_media.showinpreview',
			'config'  => array(
				'type'    => 'check',
				'default' => 0
			)
		),
		'type' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:media.type',
			'config' => array(
				'type' => 'select',
				'itemsProcFunc' => 'Tx_News_Hooks_ItemsProcFunc->user_MediaType',
				'items' => array(
					array($ll . 'tx_news_domain_model_media.type.I.0', '0', t3lib_extMgm::extRelPath('news') . 'Resources/Public/Icons/media_type_image.png'),
					array($ll . 'tx_news_domain_model_media.type.I.1', '1', t3lib_extMgm::extRelPath('news') . 'Resources/Public/Icons/media_type_multimedia.png'),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'width' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:imagewidth_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 3,
				'eval' => 'int',
			)
		),
		'height' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xml:imageheight_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 3,
				'eval' => 'int',
			)
		),
		'copyright' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll . 'tx_news_domain_model_media.copyright',
			'config' => array(
				'type' => 'input',
				'size' => 20,
			)
		),
	),
	'types' => array(
			// Image
		'0' => array('showitem' => 'type;;palettteCore,image;;paletteWidthHeight,caption;;paletteTitle,copyright,'),
			// Multimedia (Video & Audio)
		'1' => array('showitem' => 'type;;palettteCore,multimedia,caption,copyright,'),
			// DAM
		'3' => array('showitem' => 'type;;palettteCore,dam,caption;;paletteTitle,copyright,')
	),
	'palettes' => array(
		'paletteWidthHeight' => array(
			'showitem' => 'width,height,',
			'canNotCollapse' => TRUE
		),
		'palettteCore' => array(
			'showitem' => 'showinpreview, hidden,sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource,',
			'canNotCollapse' => TRUE
		),
		'paletteTitle' => array(
			'showitem' => 'title,alt,',
			'canNotCollapse' => FALSE
		),
	)
);

	// Hide DAM field if not used to avoid errors
if (!t3lib_extMgm::isLoaded('dam')) {
	unset($TCA['tx_news_domain_model_media']['columns']['dam']);
}

?>