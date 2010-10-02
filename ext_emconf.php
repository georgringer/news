<?php

########################################################################
# Extension Manager/Repository config file for ext "news2".
#
# Auto generated 25-09-2010 09:53
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'News system',
	'description' => '',
	'category' => 'fe',
	'author' => 'Georg Ringer',
	'author_email' => 'typo3@ringerge.org',
	'shy' => '',
	'dependencies' => 'extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'excludeFromUpdates',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.0',
	'constraints' => array(
		'depends' => array(
			'extbase' => '',
			'fluid' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:46:{s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"e4fa";s:14:"ext_tables.php";s:4:"b4a3";s:14:"ext_tables.sql";s:4:"18fe";s:37:"Classes/Controller/NewsController.php";s:4:"5a96";s:33:"Classes/Domain/Model/Category.php";s:4:"9aae";s:30:"Classes/Domain/Model/Media.php";s:4:"612e";s:29:"Classes/Domain/Model/News.php";s:4:"baa9";s:48:"Classes/Domain/Repository/CategoryRepository.php";s:4:"bd90";s:45:"Classes/Domain/Repository/MediaRepository.php";s:4:"7e86";s:44:"Classes/Domain/Repository/NewsRepository.php";s:4:"e743";s:41:"Configuration/FlexForms/flexform_news.xml";s:4:"583a";s:25:"Configuration/Tca/tca.php";s:4:"a852";s:38:"Configuration/TypoScript/constants.txt";s:4:"f4d8";s:34:"Configuration/TypoScript/setup.txt";s:4:"4eb2";s:54:"Resources/Private/Backend/class.tx_news2_cms_layout.php";s:4:"79a4";s:51:"Resources/Private/Backend/class.tx_news_wizicon.php";s:4:"e7d8";s:58:"Resources/Private/Backend/class.user_tx_news_labelFunc.php";s:4:"2afb";s:40:"Resources/Private/Language/locallang.xml";s:4:"b3e2";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"852a";s:53:"Resources/Private/Partials/Detail/MediaContainer.html";s:4:"fb68";s:48:"Resources/Private/Partials/Detail/MediaHtml.html";s:4:"c39e";s:49:"Resources/Private/Partials/Detail/MediaImage.html";s:4:"0827";s:49:"Resources/Private/Partials/Detail/MediaVideo.html";s:4:"20c5";s:41:"Resources/Private/Partials/List/item.html";s:4:"488f";s:48:"Resources/Private/Partials/List/noNewsFound.html";s:4:"7c31";s:54:"Resources/Private/Partials/List/specialHightlight.html";s:4:"2fb0";s:44:"Resources/Private/Templates/News/detail.html";s:4:"c9dc";s:44:"Resources/Private/Templates/News/latest.html";s:4:"f8c3";s:42:"Resources/Private/Templates/News/list.html";s:4:"8c9d";s:44:"Resources/Private/Templates/News/search.html";s:4:"c6be";s:32:"Resources/Public/Icons/Thumbs.db";s:4:"b155";s:33:"Resources/Public/Icons/ce_wiz.gif";s:4:"db33";s:61:"Resources/Public/Icons/icon_tx_news_domain_model_category.gif";s:4:"2efd";s:58:"Resources/Public/Icons/icon_tx_news_domain_model_media.gif";s:4:"475a";s:57:"Resources/Public/Icons/icon_tx_news_domain_model_news.gif";s:4:"5e2a";s:66:"Resources/Public/Icons/icon_tx_news_domain_model_news_external.gif";s:4:"57f6";s:71:"Resources/Public/Icons/selicon_tx_news_domain_model_media_type_html.png";s:4:"2e14";s:72:"Resources/Public/Icons/selicon_tx_news_domain_model_media_type_image.png";s:4:"bd2e";s:72:"Resources/Public/Icons/selicon_tx_news_domain_model_media_type_movie.png";s:4:"094d";s:67:"Resources/Public/Icons/selicon_tx_news_domain_model_news_type_0.gif";s:4:"02b6";s:67:"Resources/Public/Icons/selicon_tx_news_domain_model_news_type_1.gif";s:4:"02b6";s:67:"Resources/Public/Icons/selicon_tx_news_domain_model_news_type_2.gif";s:4:"02b6";s:42:"Resources/Public/Icons/tt_news_article.gif";s:4:"91b6";s:19:"doc/wizard_form.dat";s:4:"b3c8";s:20:"doc/wizard_form.html";s:4:"1f26";}',
	'suggests' => array(
	),
);

?>