<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

// Extension manager configuration
$configuration = \GeorgRinger\News\Utility\EmConfiguration::getSettings();

$tx_news_domain_model_media = array(
	'ctrl' => array(
		'title' => $ll . 'tx_news_domain_model_media',
		'descriptionColumn' => 'description',
		'label' => 'caption',
		'label_alt' => 'type, showinpreview',
		'label_alt_force' => 1,
		'formattedLabel_userFunc' => 'GeorgRinger\\News\\Hooks\\Labels->getUserLabelMedia',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'versioningWS' => TRUE,
		'origUid' => 't3_origuid',
		'dividers2tabs' => TRUE,
		'default_sortby' => 'ORDER BY sorting',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news') . 'Resources/Public/Icons/news_domain_model_media.gif',
		'hideTable' => TRUE,
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,title,media,type,video,showInPreview, width, height, description'
	),
	'columns' => array(
		'pid' => array(
			'label' => 'pid',
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'sorting' => array(
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
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_news_domain_model_media',
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
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'caption' => array(
			'exclude' => 1,
			'l10n_mode' => 'noCopy',
			'label' => $ll . 'tx_news_domain_model_media.caption',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			)
		),
		'title' => array(
			'exclude' => 1,
			'l10n_mode' => 'noCopy',
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:image_titleText',
			'config' => array(
				'type' => 'input',
				'size' => 20,
			)
		),
		'alt' => array(
			'exclude' => 1,
			'l10n_mode' => 'noCopy',
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:image_altText',
			'config' => array(
				'type' => 'input',
				'size' => 20,
			)
		),
		'image' => array(
			'exclude' => 0,
			'l10n_mode' => 'copy',
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
			'l10n_mode' => 'copy',
			'label' => $ll . 'tx_news_domain_model_media.multimedia',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
				'softref' => 'news_externalurl',
				'wizards' => array(
					'_PADDING' => 2,
					'link' => array(
						'type' => 'popup',
						'title' => 'Link',
						'icon' => 'link_popup.gif',
						'module' => array(
							'name' => 'wizard_element_browser',
							'urlParameters' => array(
								'mode' => 'wizard'
							)
						),
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'showinpreview' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_news_domain_model_media.showinpreview',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'type' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:media.type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array($ll . 'tx_news_domain_model_media.type.I.0', '0', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news') . 'Resources/Public/Icons/media_type_image.png'),
					array($ll . 'tx_news_domain_model_media.type.I.1', '1', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news') . 'Resources/Public/Icons/media_type_multimedia.png'),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'width' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:imagewidth_formlabel',
			'config' => array(
				'type' => 'input',
				'size' => 3,
				'eval' => 'int',
			)
		),
		'height' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cms/locallang_ttc.xlf:imageheight_formlabel',
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
		'description' => array(
			'exclude' => 0,
			'l10n_mode' => 'noCopy',
			'label' => $ll . 'tx_news_domain_model_file.description',
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
						'module' => array(
							'name' => 'wizard_rte',
						),
					),
				),
			)
		),
	),
	'types' => array(
		// Image
		'0' => array('showitem' => '--palette--;;palettteCore,image;;paletteWidthHeight,caption,title;;paletteAlt,copyright,description;;;richtext::rte_transform[flag=rte_disabled|mode=ts_css],'),
		// Multimedia (Video & Audio)
		'1' => array('showitem' => '--palette--;;palettteCore,multimedia,caption,copyright,description,'),
	),
	'palettes' => array(
		'paletteWidthHeight' => array(
			'showitem' => 'width,height,',
			'canNotCollapse' => TRUE
		),
		'palettteCore' => array(
			'showitem' => 'type,showinpreview, hidden,sys_language_uid, l10n_parent, l10n_diffsource,',
			'canNotCollapse' => TRUE
		),
		'paletteAlt' => array(
			'showitem' => 'alt',
			'canNotCollapse' => FALSE
		),
	)
);

// Hide image type when FAL + Multimedia is set
if ($configuration->getUseFal() === 3) {
	unset($tx_news_domain_model_media['columns']['type']['config']['items'][0]);
	$tx_news_domain_model_media['columns']['type']['config']['default'] = '1';
}

// Hide RTE description field
if (!$configuration->getShowMediaDescriptionField()) {
	unset($tx_news_domain_model_media['columns']['description']);
}

return $tx_news_domain_model_media;