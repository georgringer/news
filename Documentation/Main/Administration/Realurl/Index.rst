.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


EXT:news and RealURL
^^^^^^^^^^^^^^^^^^^^^^^

This section will show you how you can rewrite the urls using the extension **RealURL**.


Basic setup
"""""""""""""""

The most simple example is the following one. You can just add the section with copy&paste to
the *postVarSets/Default* section: ::

	// EXT:news start
	'news' => array(
		array(
			'GETvar' => 'tx_news_pi1[action]',
		),
		array(
			'GETvar' => 'tx_news_pi1[controller]',
		),
		array(
			'GETvar' => 'tx_news_pi1[news]',
			'lookUpTable' => array(
				'table' => 'tx_news_domain_model_news',
				'id_field' => 'uid',
				'alias_field' => 'title',
				'addWhereClause' => ' AND NOT deleted',
				'useUniqueCache' => 1,
				'useUniqueCache_conf' => array(
					'strtolower' => 1,
					'spaceCharacter' => '-',
				),
				'languageGetVar' => 'L',
				'languageExceptionUids' => '',
				'languageField' => 'sys_language_uid',
				'transOrigPointerField' => 'l10n_parent',
				'autoUpdate' => 1,
				'expireDays' => 180,
			),
		),
	),
	// EXT:news end


Advanced example
""""""""""""""""""

This example is advanced and works best with one single view page.
It hides the controller and action name by using fixedPostVars.
Here is a full RealURL configuration with the explanation below. ::

	<?php

	$TYPO3_CONF_VARS['FE']['addRootLineFields'].= ',tx_realurl_pathsegment';
	$TYPO3_CONF_VARS['EXTCONF']['realurl']['_DEFAULT'] = array(
		'pagePath' => array(
			'type' => 'user',
			'userFunc' => 'EXT:realurl/class.tx_realurl_advanced.php:&tx_realurl_advanced->main',
			'spaceCharacter' => '-',
			'languageGetVar' => 'L',
			'expireDays' => '3',
			'rootpage_id' => 1,
			'firstHitPathCache'=>1
		),
		'init' => array(
			'enableCHashCache' => TRUE,
			'enableCHashCache' => 1,
			'respectSimulateStaticURLs' => 0,
			'enableUrlDecodeCache' => 1,
			'enableUrlEncodeCache' => 1
		),
		'preVars' => array(
			array(
				'GETvar' => 'L',
				'valueMap' => array(
					'en' => '1',
				),
				'noMatch' => 'bypass',
			),
			array(
				'GETvar' => 'no_cache',
				'valueMap' => array(
					'nc' => 1,
				),
				'noMatch' => 'bypass',
			),
		),
		'fixedPostVars' => array(
			'newsDetailConfiguration' => array(
				array(
					'GETvar' => 'tx_news_pi1[news]',
					'lookUpTable' => array(
						'table' => 'tx_news_domain_model_news',
						'id_field' => 'uid',
						'alias_field' => 'title',
						'addWhereClause' => ' AND NOT deleted',
						'useUniqueCache' => 1,
						'useUniqueCache_conf' => array(
							'strtolower' => 1,
							'spaceCharacter' => '-'
						),
						'languageGetVar' => 'L',
						'languageExceptionUids' => '',
						'languageField' => 'sys_language_uid',
						'transOrigPointerField' => 'l10n_parent',
						'autoUpdate' => 1,
						'expireDays' => 180,
					)
				)
			),
			'newsCategoryConfiguration' => array(
				array(
					'GETvar' => 'tx_news_pi1[overwriteDemand][categories]',
					'lookUpTable' => array(
						'table' => 'tx_news_domain_model_category',
						'id_field' => 'uid',
						'alias_field' => 'title',
						'addWhereClause' => ' AND NOT deleted',
						'useUniqueCache' => 1,
						'useUniqueCache_conf' => array(
							'strtolower' => 1,
							'spaceCharacter' => '-'
						)
					)
				)
			),
			'newsTagConfiguration' => array(
				array(
					'GETvar' => 'tx_news_pi1[overwriteDemand][tags]',
					'lookUpTable' => array(
						'table' => 'tx_news_domain_model_tag',
						'id_field' => 'uid',
						'alias_field' => 'title',
						'addWhereClause' => ' AND NOT deleted',
						'useUniqueCache' => 1,
						'useUniqueCache_conf' => array(
							'strtolower' => 1,
							'spaceCharacter' => '-'
						)
					)
				)
			),
			'70' => 'newsDetailConfiguration',
			'701' => 'newsDetailConfiguration', // For additional detail pages, add their uid as well
			'71' => 'newsTagConfiguration',
			'72' => 'newsCategoryConfiguration',

		),
		'postVarSets' => array(
			'_DEFAULT' => array(
				'controller' => array(
					array(
						'GETvar' => 'tx_news_pi1[action]',
						'noMatch' => 'bypass'
					),
					array(
						'GETvar' => 'tx_news_pi1[controller]',
						'noMatch' => 'bypass'
					)
				),

				'dateFilter' => array(
					array(
						'GETvar' => 'tx_news_pi1[overwriteDemand][year]',
					),
					array(
						'GETvar' => 'tx_news_pi1[overwriteDemand][month]',
					),
				),
				'page' => array(
					array(
						'GETvar' => 'tx_news_pi1[@widget_0][currentPage]',
					),
				),
			),
		),

	);

	?>

**Explanation**

The configuration of *_newsDetailConfiguration_* is used for the single view.
Its name is not that important but the same name has to be used in line 86 where the uid of the single view page is set.
In this example it is *70*. Of course you need to set the uid of your single view page.

The same happens for a single view of categories and tags by using newsCategoryConfiguration and newsTagConfiguration.

Because of using fixedPostVars, the arguments can be removed in the @postVarSets@ section.

Removing controller and action arguments from URL
"""""""""""""""""""""""""""""""""""""""""""""""""""

If you got a separate page to display the single view it is possible to skip the arguments ::

	&tx_news_pi1[controller]=News
	&tx_news_pi1[action]=detail

If you want that, you need to activate the following setting in your TypoScript: ::

	plugin.tx_news {
		settings {
			link {
				skipControllerAndAction = 1
			}
		}
	}


Human readable dates
"""""""""""""""""""""""

If you want to have human readable dates inside the URL which means having URLs like
*domain.tld/fo/bar/article/31/01/2011/this-is-the-news-title.html* you need 3 things.

1st: EXT:news version 1.1.0+
2nd: Enable the configuration in TypoScript ::

	plugin.tx_news.settings.link {
		hrDate = 1
		hrDate {
			day = j
			month = n
			year = Y
		}
	}

You can configure each argument (day/month/year) separately by using the configuration of PHP function *date*,
(see http://www.php.net/date).

3rd: RealURL configuration ::

	array(
		'GETvar' => 'tx_news_pi1[day]',
		'noMatch' => 'bypass',
	),
	array(
		'GETvar' => 'tx_news_pi1[month]',
		'noMatch' => 'bypass',
	),
	array(
		'GETvar' => 'tx_news_pi1[year]',
		'noMatch' => 'bypass',
	),

alias_field Variations
""""""""""""""""""""""""""

Every news record got a field called **Speaking URL path segment** which can be used to build the URL of the news record.

The following snippet shows how to use this field: ::

	array(
		'GETvar' => 'tx_news_pi1[news]',
		'lookUpTable' => array(
			'table' => 'tx_news_domain_model_news',
			'id_field' => 'uid',

			'alias_field' => "CONCAT(uid, '-', IF(path_segment!='',path_segment,title))",
			/** OR ***************/
			'alias_field' => 'IF(path_segment!="",path_segment,title)',
			/** OR ***************/
			'alias_field' => "CONCAT(uid, '-', title))",

			'addWhereClause' => ' AND NOT deleted',
			'useUniqueCache' => 1,
			'useUniqueCache_conf' => array(
				'strtolower' => 1,
				'spaceCharacter' => '-'
			),
			'languageGetVar' => 'L',
			'languageExceptionUids' => '',
			'languageField' => 'sys_language_uid',
			'transOrigPointerField' => 'l10n_parent',
			'autoUpdate' => 1,
			'expireDays' => 180,
		)
	)

As you can see, it is possible to combine any of the fields of the record.