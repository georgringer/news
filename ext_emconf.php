<?php

########################################################################
# Extension Manager/Repository config file for ext "news".
#
# Auto generated 23-01-2013 22:54
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'News system',
	'description' => 'Versatile news extension, based on extbase & fluid. Editor friendly, default integration of social sharing and many other features',
	'category' => 'fe',
	'author' => 'Georg Ringer',
	'author_email' => 'typo3@ringerge.org',
	'shy' => '',
	'dependencies' => 'extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '2.0.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.5-0.0.0',
			'extbase' => '',
			'fluid' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:259:{s:13:"Changelog.txt";s:4:"e9b2";s:20:"class.ext_update.php";s:4:"e0a8";s:16:"ext_autoload.php";s:4:"8543";s:21:"ext_conf_template.txt";s:4:"b39c";s:12:"ext_icon.gif";s:4:"777b";s:17:"ext_localconf.php";s:4:"9746";s:14:"ext_tables.php";s:4:"28c3";s:14:"ext_tables.sql";s:4:"db3c";s:35:"Classes/Cache/ClassCacheBuilder.php";s:4:"9d2b";s:47:"Classes/Controller/AdministrationController.php";s:4:"9e44";s:41:"Classes/Controller/CategoryController.php";s:4:"bb7b";s:39:"Classes/Controller/ImportController.php";s:4:"7bab";s:41:"Classes/Controller/NewsBaseController.php";s:4:"c3c7";s:37:"Classes/Controller/NewsController.php";s:4:"871a";s:33:"Classes/Domain/Model/Category.php";s:4:"8172";s:40:"Classes/Domain/Model/DemandInterface.php";s:4:"5c9e";s:29:"Classes/Domain/Model/File.php";s:4:"ee2f";s:29:"Classes/Domain/Model/Link.php";s:4:"db8c";s:30:"Classes/Domain/Model/Media.php";s:4:"1a33";s:29:"Classes/Domain/Model/News.php";s:4:"2c0e";s:36:"Classes/Domain/Model/NewsDefault.php";s:4:"0a24";s:37:"Classes/Domain/Model/NewsExternal.php";s:4:"1c81";s:37:"Classes/Domain/Model/NewsInternal.php";s:4:"d309";s:28:"Classes/Domain/Model/Tag.php";s:4:"9763";s:49:"Classes/Domain/Model/Dto/AdministrationDemand.php";s:4:"7e88";s:44:"Classes/Domain/Model/Dto/EmConfiguration.php";s:4:"decc";s:39:"Classes/Domain/Model/Dto/NewsDemand.php";s:4:"5647";s:35:"Classes/Domain/Model/Dto/Search.php";s:4:"2318";s:43:"Classes/Domain/Model/External/TtContent.php";s:4:"4032";s:56:"Classes/Domain/Repository/AbstractDemandedRepository.php";s:4:"b7f4";s:48:"Classes/Domain/Repository/CategoryRepository.php";s:4:"1d16";s:57:"Classes/Domain/Repository/DemandedRepositoryInterface.php";s:4:"bd3b";s:44:"Classes/Domain/Repository/FileRepository.php";s:4:"a9f8";s:44:"Classes/Domain/Repository/LinkRepository.php";s:4:"d86d";s:45:"Classes/Domain/Repository/MediaRepository.php";s:4:"c16f";s:44:"Classes/Domain/Repository/NewsRepository.php";s:4:"76c4";s:49:"Classes/Domain/Repository/TtContentRepository.php";s:4:"e92c";s:48:"Classes/Domain/Service/CategoryImportService.php";s:4:"52b2";s:44:"Classes/Domain/Service/NewsImportService.php";s:4:"e301";s:27:"Classes/Hooks/CmsLayout.php";s:4:"7618";s:31:"Classes/Hooks/ItemsProcFunc.php";s:4:"f516";s:24:"Classes/Hooks/Labels.php";s:4:"62e8";s:33:"Classes/Hooks/SuggestReceiver.php";s:4:"2668";s:37:"Classes/Hooks/SuggestReceiverCall.php";s:4:"a540";s:29:"Classes/Hooks/T3libBefunc.php";s:4:"7f2b";s:26:"Classes/Hooks/Tceforms.php";s:4:"36b3";s:25:"Classes/Hooks/Tcemain.php";s:4:"3077";s:37:"Classes/Interfaces/MediaInterface.php";s:4:"6e0a";s:32:"Classes/Interfaces/Audio/Mp3.php";s:4:"9b94";s:33:"Classes/Interfaces/Video/File.php";s:4:"1946";s:38:"Classes/Interfaces/Video/Quicktime.php";s:4:"210b";s:39:"Classes/Interfaces/Video/Videosites.php";s:4:"ccf1";s:34:"Classes/Jobs/AbstractImportJob.php";s:4:"2069";s:35:"Classes/Jobs/ImportJobInterface.php";s:4:"2f61";s:40:"Classes/Jobs/TTNewsCategoryImportJob.php";s:4:"9e25";s:36:"Classes/Jobs/TTNewsNewsImportJob.php";s:4:"6c1d";s:32:"Classes/Service/CacheService.php";s:4:"1b5f";s:35:"Classes/Service/CategoryService.php";s:4:"10ad";s:31:"Classes/Service/FileService.php";s:4:"c750";s:35:"Classes/Service/SettingsService.php";s:4:"b961";s:55:"Classes/Service/Import/DataProviderServiceInterface.php";s:4:"044f";s:60:"Classes/Service/Import/TTNewsCategoryDataProviderService.php";s:4:"f8fc";s:56:"Classes/Service/Import/TTNewsNewsDataProviderService.php";s:4:"258f";s:49:"Classes/TreeProvider/DatabaseTreeDataProvider.php";s:4:"4184";s:36:"Classes/Utility/CategoryProvider.php";s:4:"907c";s:33:"Classes/Utility/Compatibility.php";s:4:"e3ab";s:35:"Classes/Utility/EmConfiguration.php";s:4:"988e";s:29:"Classes/Utility/ImportJob.php";s:4:"76bb";s:24:"Classes/Utility/Page.php";s:4:"cc44";s:23:"Classes/Utility/Url.php";s:4:"9153";s:30:"Classes/Utility/Validation.php";s:4:"5218";s:50:"Classes/ViewHelpers/CategoryChildrenViewHelper.php";s:4:"f337";s:54:"Classes/ViewHelpers/ExcludeDisplayedNewsViewHelper.php";s:4:"7a6d";s:44:"Classes/ViewHelpers/HeaderDataViewHelper.php";s:4:"2986";s:45:"Classes/ViewHelpers/IncludeFileViewHelper.php";s:4:"4088";s:38:"Classes/ViewHelpers/LinkViewHelper.php";s:4:"33d9";s:46:"Classes/ViewHelpers/MediaFactoryViewHelper.php";s:4:"f171";s:41:"Classes/ViewHelpers/MetaTagViewHelper.php";s:4:"07b1";s:40:"Classes/ViewHelpers/ObjectViewHelper.php";s:4:"7ef7";s:50:"Classes/ViewHelpers/PaginateBodytextViewHelper.php";s:4:"fea5";s:42:"Classes/ViewHelpers/TitleTagViewHelper.php";s:4:"bbf3";s:46:"Classes/ViewHelpers/Be/ClickmenuViewHelper.php";s:4:"9e6e";s:50:"Classes/ViewHelpers/Be/MultiEditLinkViewHelper.php";s:4:"5b8f";s:58:"Classes/ViewHelpers/Be/Buttons/IconForRecordViewHelper.php";s:4:"f5c3";s:49:"Classes/ViewHelpers/Be/Buttons/IconViewHelper.php";s:4:"6ee5";s:44:"Classes/ViewHelpers/Format/DamViewHelper.php";s:4:"7b41";s:45:"Classes/ViewHelpers/Format/DateViewHelper.php";s:4:"c1d2";s:53:"Classes/ViewHelpers/Format/FileDownloadViewHelper.php";s:4:"bf76";s:49:"Classes/ViewHelpers/Format/FileSizeViewHelper.php";s:4:"675e";s:44:"Classes/ViewHelpers/Format/HscViewHelper.php";s:4:"f7f2";s:59:"Classes/ViewHelpers/Format/HtmlentitiesDecodeViewHelper.php";s:4:"a547";s:48:"Classes/ViewHelpers/Format/NothingViewHelper.php";s:4:"bdf1";s:50:"Classes/ViewHelpers/Format/StriptagsViewHelper.php";s:4:"cb82";s:47:"Classes/ViewHelpers/Social/DisqusViewHelper.php";s:4:"b6eb";s:51:"Classes/ViewHelpers/Social/GooglePlusViewHelper.php";s:4:"d0c0";s:48:"Classes/ViewHelpers/Social/TwitterViewHelper.php";s:4:"ed6d";s:57:"Classes/ViewHelpers/Social/Facebook/CommentViewHelper.php";s:4:"5947";s:54:"Classes/ViewHelpers/Social/Facebook/LikeViewHelper.php";s:4:"f208";s:55:"Classes/ViewHelpers/Social/Facebook/ShareViewHelper.php";s:4:"230b";s:49:"Classes/ViewHelpers/Widget/PaginateViewHelper.php";s:4:"7721";s:60:"Classes/ViewHelpers/Widget/Controller/PaginateController.php";s:4:"b3b7";s:41:"Configuration/FlexForms/flexform_news.xml";s:4:"dbcf";s:30:"Configuration/Tca/category.php";s:4:"8889";s:26:"Configuration/Tca/file.php";s:4:"f26e";s:26:"Configuration/Tca/link.php";s:4:"2a33";s:27:"Configuration/Tca/media.php";s:4:"6954";s:26:"Configuration/Tca/news.php";s:4:"9af8";s:25:"Configuration/Tca/tag.php";s:4:"2c1a";s:38:"Configuration/TypoScript/constants.txt";s:4:"f9ca";s:34:"Configuration/TypoScript/setup.txt";s:4:"d415";s:24:"Documentation/Images.txt";s:4:"4442";s:26:"Documentation/Includes.txt";s:4:"ca02";s:23:"Documentation/Index.rst";s:4:"3507";s:25:"Documentation/License.txt";s:4:"2886";s:26:"Documentation/Settings.yml";s:4:"b1b3";s:21:"Documentation/uml.pdf";s:4:"8e15";s:47:"Documentation/NewsVersatileNewsSystem/Index.rst";s:4:"7647";s:62:"Documentation/NewsVersatileNewsSystem/Administration/Index.rst";s:4:"4a03";s:94:"Documentation/NewsVersatileNewsSystem/Administration/ReadBeforeInstallingOrUpdating/Images.txt";s:4:"a9a9";s:93:"Documentation/NewsVersatileNewsSystem/Administration/ReadBeforeInstallingOrUpdating/Index.rst";s:4:"668e";s:66:"Documentation/NewsVersatileNewsSystem/Administration/Rss/Index.rst";s:4:"52f1";s:63:"Documentation/NewsVersatileNewsSystem/BreakingChanges/Index.rst";s:4:"1708";s:77:"Documentation/NewsVersatileNewsSystem/BreakingChanges/From110To120/Images.txt";s:4:"af2b";s:76:"Documentation/NewsVersatileNewsSystem/BreakingChanges/From110To120/Index.rst";s:4:"64d9";s:57:"Documentation/NewsVersatileNewsSystem/Changelog/Index.rst";s:4:"a049";s:61:"Documentation/NewsVersatileNewsSystem/Configuration/Index.rst";s:4:"2736";s:92:"Documentation/NewsVersatileNewsSystem/Configuration/ExtensionManagersConfiguration/Index.rst";s:4:"5964";s:68:"Documentation/NewsVersatileNewsSystem/Configuration/Plugin/Index.rst";s:4:"9213";s:71:"Documentation/NewsVersatileNewsSystem/Configuration/Reference/Index.rst";s:4:"3f66";s:79:"Documentation/NewsVersatileNewsSystem/Configuration/ReferenceTsconfig/Index.rst";s:4:"7683";s:58:"Documentation/NewsVersatileNewsSystem/ExtendNews/Index.rst";s:4:"aa6d";s:74:"Documentation/NewsVersatileNewsSystem/ExtendNews/ExtendFlexforms/Index.rst";s:4:"e71c";s:69:"Documentation/NewsVersatileNewsSystem/Images/manual_html_11cdfe72.gif";s:4:"d7ef";s:69:"Documentation/NewsVersatileNewsSystem/Images/manual_html_16a05934.gif";s:4:"4949";s:69:"Documentation/NewsVersatileNewsSystem/Images/manual_html_1be9c912.gif";s:4:"fccd";s:69:"Documentation/NewsVersatileNewsSystem/Images/manual_html_3c561b14.gif";s:4:"db1b";s:69:"Documentation/NewsVersatileNewsSystem/Images/manual_html_3c9c2593.png";s:4:"f048";s:69:"Documentation/NewsVersatileNewsSystem/Images/manual_html_50bb30c2.gif";s:4:"0720";s:70:"Documentation/NewsVersatileNewsSystem/Images/manual_html_m3418f72f.gif";s:4:"1b67";s:70:"Documentation/NewsVersatileNewsSystem/Images/manual_html_m3730e0f9.gif";s:4:"a2b8";s:70:"Documentation/NewsVersatileNewsSystem/Images/manual_html_m543552d2.png";s:4:"2f80";s:70:"Documentation/NewsVersatileNewsSystem/Images/manual_html_m6c4904d6.gif";s:4:"d18e";s:60:"Documentation/NewsVersatileNewsSystem/Introduction/Index.rst";s:4:"efcc";s:69:"Documentation/NewsVersatileNewsSystem/Introduction/NeedHelp/Index.rst";s:4:"e653";s:70:"Documentation/NewsVersatileNewsSystem/Introduction/SayThanks/Index.rst";s:4:"afde";s:73:"Documentation/NewsVersatileNewsSystem/Introduction/Screenshots/Images.txt";s:4:"8887";s:72:"Documentation/NewsVersatileNewsSystem/Introduction/Screenshots/Index.rst";s:4:"bbd0";s:80:"Documentation/NewsVersatileNewsSystem/Introduction/SponsoringBugfixing/Index.rst";s:4:"a307";s:73:"Documentation/NewsVersatileNewsSystem/Introduction/WhatDoesItDo/Index.rst";s:4:"7b84";s:61:"Documentation/NewsVersatileNewsSystem/KnownProblems/Index.rst";s:4:"9141";s:57:"Documentation/NewsVersatileNewsSystem/To-doList/Index.rst";s:4:"3fd3";s:56:"Documentation/NewsVersatileNewsSystem/Tutorial/Index.rst";s:4:"603f";s:81:"Documentation/NewsVersatileNewsSystem/Tutorial/ChangingEditingTemplates/Index.rst";s:4:"5d20";s:93:"Documentation/NewsVersatileNewsSystem/Tutorial/HowToAddASimpleNewsSystemToYourSite/Images.txt";s:4:"01b5";s:92:"Documentation/NewsVersatileNewsSystem/Tutorial/HowToAddASimpleNewsSystemToYourSite/Index.rst";s:4:"2555";s:74:"Documentation/NewsVersatileNewsSystem/Tutorial/IntegrationWithTs/Index.rst";s:4:"e5bf";s:76:"Documentation/NewsVersatileNewsSystem/Tutorial/MigrationFromTtNews/Index.rst";s:4:"295d";s:59:"Documentation/NewsVersatileNewsSystem/UsersManual/Index.rst";s:4:"c3cf";s:85:"Documentation/NewsVersatileNewsSystem/UsersManual/AboutNewsCategoryRecords/Images.txt";s:4:"b474";s:84:"Documentation/NewsVersatileNewsSystem/UsersManual/AboutNewsCategoryRecords/Index.rst";s:4:"7de0";s:40:"Resources/Private/Language/locallang.xml";s:4:"5f91";s:43:"Resources/Private/Language/locallang_be.xml";s:4:"8cb1";s:53:"Resources/Private/Language/locallang_csh_category.xml";s:4:"08e1";s:49:"Resources/Private/Language/locallang_csh_file.xml";s:4:"d3cc";s:54:"Resources/Private/Language/locallang_csh_flexforms.xml";s:4:"9f2c";s:49:"Resources/Private/Language/locallang_csh_link.xml";s:4:"7301";s:50:"Resources/Private/Language/locallang_csh_media.xml";s:4:"cc36";s:49:"Resources/Private/Language/locallang_csh_news.xml";s:4:"3aff";s:48:"Resources/Private/Language/locallang_csh_tag.xml";s:4:"24df";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"91d8";s:44:"Resources/Private/Language/locallang_mod.xml";s:4:"59da";s:58:"Resources/Private/Language/locallang_modadministration.xml";s:4:"a707";s:37:"Resources/Private/Layouts/Detail.html";s:4:"7995";s:38:"Resources/Private/Layouts/General.html";s:4:"bbe4";s:46:"Resources/Private/Layouts/Backend/Default.html";s:4:"fc82";s:54:"Resources/Private/Partials/Administration/Buttons.html";s:4:"451f";s:55:"Resources/Private/Partials/Administration/ListItem.html";s:4:"e5df";s:46:"Resources/Private/Partials/Category/Items.html";s:4:"0c97";s:53:"Resources/Private/Partials/Detail/MediaContainer.html";s:4:"866b";s:47:"Resources/Private/Partials/Detail/MediaDam.html";s:4:"faaf";s:49:"Resources/Private/Partials/Detail/MediaImage.html";s:4:"4496";s:49:"Resources/Private/Partials/Detail/MediaVideo.html";s:4:"0d16";s:48:"Resources/Private/Partials/Detail/Opengraph.html";s:4:"3ed2";s:41:"Resources/Private/Partials/List/Item.html";s:4:"ea1b";s:44:"Resources/Private/Php/class.news_wizicon.php";s:4:"cd9b";s:53:"Resources/Private/Templates/Administration/Index.html";s:4:"4f4e";s:62:"Resources/Private/Templates/Administration/NewsPidListing.html";s:4:"a8e8";s:46:"Resources/Private/Templates/Category/List.html";s:4:"4b69";s:45:"Resources/Private/Templates/Import/Index.html";s:4:"af24";s:46:"Resources/Private/Templates/News/DateMenu.html";s:4:"45e0";s:44:"Resources/Private/Templates/News/Detail.html";s:4:"dde1";s:42:"Resources/Private/Templates/News/List.html";s:4:"1f84";s:41:"Resources/Private/Templates/News/List.xml";s:4:"41b2";s:48:"Resources/Private/Templates/News/SearchForm.html";s:4:"1bee";s:50:"Resources/Private/Templates/News/SearchResult.html";s:4:"d390";s:48:"Resources/Private/Templates/ViewHelpers/Flv.html";s:4:"63c6";s:66:"Resources/Private/Templates/ViewHelpers/Widget/Paginate/Index.html";s:4:"610a";s:39:"Resources/Public/Css/administration.css";s:4:"71b0";s:35:"Resources/Public/Css/news-basic.css";s:4:"56fd";s:33:"Resources/Public/Icons/ce_wiz.gif";s:4:"628a";s:33:"Resources/Public/Icons/folder.gif";s:4:"ab9a";s:40:"Resources/Public/Icons/import_module.gif";s:4:"91b6";s:41:"Resources/Public/Icons/media_type_dam.gif";s:4:"999b";s:42:"Resources/Public/Icons/media_type_html.png";s:4:"2e14";s:43:"Resources/Public/Icons/media_type_image.png";s:4:"bd2e";s:48:"Resources/Public/Icons/media_type_multimedia.png";s:4:"094d";s:53:"Resources/Public/Icons/news_domain_model_category.gif";s:4:"659a";s:49:"Resources/Public/Icons/news_domain_model_file.gif";s:4:"e646";s:49:"Resources/Public/Icons/news_domain_model_link.gif";s:4:"a172";s:50:"Resources/Public/Icons/news_domain_model_media.gif";s:4:"8621";s:49:"Resources/Public/Icons/news_domain_model_news.gif";s:4:"46a1";s:58:"Resources/Public/Icons/news_domain_model_news_external.gif";s:4:"d7ad";s:58:"Resources/Public/Icons/news_domain_model_news_internal.gif";s:4:"c2cf";s:48:"Resources/Public/Icons/news_domain_model_tag.png";s:4:"5667";s:34:"Resources/Public/Icons/preview.gif";s:4:"4e9f";s:47:"Resources/Public/Images/dummy-preview-image.png";s:4:"8084";s:35:"Resources/Public/Images/topnews.png";s:4:"7e6b";s:62:"Resources/Public/JavaScript/Contrib/audioplayer-noswfobject.js";s:4:"cc36";s:58:"Resources/Public/JavaScript/Contrib/audioplayer-player.swf";s:4:"d6fa";s:59:"Resources/Public/JavaScript/Contrib/flowplayer-3.2.4.min.js";s:4:"ae57";s:56:"Resources/Public/JavaScript/Contrib/flowplayer-3.2.5.swf";s:4:"3670";s:65:"Resources/Public/JavaScript/Contrib/flowplayer.controls-3.2.3.swf";s:4:"37d1";s:52:"Resources/Public/JavaScript/Contrib/swfobject-2-2.js";s:4:"84e0";s:51:"Resources/Public/JavaScript/Contrib/table_sorter.js";s:4:"59d7";s:44:"Tests/Unit/Controller/NewsControllerTest.php";s:4:"efa0";s:40:"Tests/Unit/Domain/Model/CategoryTest.php";s:4:"a8c3";s:36:"Tests/Unit/Domain/Model/FileTest.php";s:4:"c307";s:36:"Tests/Unit/Domain/Model/LinkTest.php";s:4:"a21c";s:37:"Tests/Unit/Domain/Model/MediaTest.php";s:4:"2d12";s:43:"Tests/Unit/Domain/Model/NewsDefaultTest.php";s:4:"b72a";s:42:"Tests/Unit/Domain/Model/NewsDemandTest.php";s:4:"a1fe";s:44:"Tests/Unit/Domain/Model/NewsExternalTest.php";s:4:"f22f";s:44:"Tests/Unit/Domain/Model/NewsInternalTest.php";s:4:"b6b7";s:36:"Tests/Unit/Domain/Model/NewsTest.php";s:4:"50d4";s:35:"Tests/Unit/Domain/Model/TagTest.php";s:4:"ba75";s:51:"Tests/Unit/Domain/Model/Dto/EmConfigurationTest.php";s:4:"af06";s:42:"Tests/Unit/Domain/Model/Dto/SearchTest.php";s:4:"2c28";s:50:"Tests/Unit/Domain/Model/External/TtContentTest.php";s:4:"fa8d";s:57:"Tests/Unit/Domain/Repository/NewsRepositoryDemandTest.php";s:4:"5d7d";s:51:"Tests/Unit/Domain/Repository/NewsRepositoryTest.php";s:4:"f86d";s:39:"Tests/Unit/Interfaces/Audio/Mp3Test.php";s:4:"d2b1";s:40:"Tests/Unit/Interfaces/Video/FileTest.php";s:4:"1969";s:45:"Tests/Unit/Interfaces/Video/QuicktimeTest.php";s:4:"0698";s:46:"Tests/Unit/Interfaces/Video/VideositesTest.php";s:4:"cd67";s:43:"Tests/Unit/Jobs/TTNewsNewsImportJobTest.php";s:4:"3a19";s:43:"Tests/Unit/Utility/CategoryProviderTest.php";s:4:"d1f7";s:42:"Tests/Unit/Utility/EmConfigurationTest.php";s:4:"c49a";s:36:"Tests/Unit/Utility/ImportJobTest.php";s:4:"35a4";s:30:"Tests/Unit/Utility/UrlTest.php";s:4:"030c";s:37:"Tests/Unit/Utility/ValidationTest.php";s:4:"d1b2";s:57:"Tests/Unit/ViewHelpers/PaginateBodytextViewHelperTest.php";s:4:"da87";s:57:"Tests/Unit/ViewHelpers/Be/MultiEditLinkViewhelperTest.php";s:4:"7fec";s:52:"Tests/Unit/ViewHelpers/Format/DateViewHelperTest.php";s:4:"0cdd";s:56:"Tests/Unit/ViewHelpers/Format/FileSizeViewHelperTest.php";s:4:"b890";s:55:"Tests/Unit/ViewHelpers/Format/NothingViewHelperTest.php";s:4:"81f3";s:57:"Tests/Unit/ViewHelpers/Format/StriptagsViewHelperTest.php";s:4:"2e16";s:39:"Tests/Unit/ViewHelpers/Format/dummy.txt";s:4:"f4a4";s:54:"Tests/Unit/ViewHelpers/Social/DisqusViewHelperTest.php";s:4:"cbd0";s:14:"doc/manual.sxw";s:4:"7987";}',
	'suggests' => array(
	),
);

?>