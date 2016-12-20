.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


ICalendar
---------

Displaying an iCalendar feed is the same as a normal list view, just with a different template.
Therefore you won't need any different configuration to e.g. excluded categories or configure the single view page.

.. only:: html

.. contents::
       :local:
       :depth: 3

The template for the iCalendar feed can be found in the file Resources/Private/Templates/News/List.ical.
The "magic" which uses the List.ical template instead of the List.html is the following configuration:

.. code-block:: typoscript

   plugin.tx_news.settings.format = ical
   plugin.tx_news.settings.domain.data = getEnv:HTTP_HOST
   plugin.tx_news.settings.useStdWrap = domain


iCalendar feed by TypoScript
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
A very simple way to generate the iCalendar feed is using plain TypoScript. All you need is to use the given TypoScript and adopt it to your needs.

.. code-block:: typoscript

    [globalVar = TSFE:type = 9819]
    config {
    	disableAllHeaderCode = 1
    	xhtml_cleaning = none
    	admPanel = 0
    	metaCharset = utf-8
    	additionalHeaders = Content-Type:text/calendar;charset=utf-8
    	disablePrefixComment = 1
    }

    pageNewsICalendar = PAGE
    pageNewsICalendar {
    	typeNum = 9819
    	10 < tt_content.list.20.news_pi1
    	10 {
    		switchableControllerActions {
    			News {
    				1 = list
    			}
    		}
    		settings < plugin.tx_news.settings
    		settings {
    			categories = 9
    			categoryConjunction = notor
    			limit = 30
    			detailPid = 25
    			startingpoint = 24
    			format = ical
    			domain.data = getEnv:HTTP_HOST
    			useStdWrap = domain
    		}
    	}
    }
    [global]

This example will show all news records which don't have the category with the uid 9 assigned and are saved on the page with uid 24.

The iCalendar feed itself can be found with the link **/?type=9819**.


iCalendar feeds by using a normal plugin
""""""""""""""""""""""""""""""""""""""""

Sometimes it is more convenient to generate the iCalendar feed using the normal plugin.
The biggest advantage is that the complete configuration can be done within the backend without touching TypoScript.

To create an ICalendar feed based on a plugin follow this steps:

#. Create a new page.

#. Add the news plugin and define the configuration you need. E.g. startingpoint, page with the single view, ...

#. Define a new TypoScript template and use a code like below.  **Very
   important** : Use config.absRefPrefix = http://www.yourdomain.tld/ to
   produce absolute urls for links and images!

   .. code-block:: typoscript

		page = PAGE
		page.10 < styles.content.get

		config {
			  # deactivate Standard-Header
			 disableAllHeaderCode = 1
			 # no xhtml tags
			 xhtml_cleaning = none
			 admPanel = 0
			 metaCharset = utf-8
			 # define charset
			 additionalHeaders = Content-Type:text/calendar;charset=utf-8
			 disablePrefixComment = 1
		}

		# set the format
		plugin.tx_news.settings.format = ical
		# set the domain for real unique uids
		plugin.tx_news.settings.domain.data = getEnv:HTTP_HOST
		plugin.tx_news.settings.useStdWrap = domain

		# delete content wrap
		tt_content.stdWrap >

**Important:** If your output still contains HTML code, please check your TypoScript
(especially from css\_styled\_content) as this HTML is produced there!

Automatic iCalendar feeds - based on plugins
""""""""""""""""""""""""""""""""""""""""""""
There are use cases where many different list views are needed and each list view should also get its own iCalendar feed **without any additional configuration**.

The TypoScript code looks like this.

.. code-block:: typoscript

    [globalVar = TSFE:type = 9819]
    	lib.stdheader >
    	tt_content.stdWrap.innerWrap >
    	tt_content.stdWrap.wrap >
    	# get away <div class="feEditAdvanced-firstWrapper" ...> if your logged into the backend
    	styles.content.get.stdWrap >

    	pageNewsICalendar = PAGE
    	pageNewsICalendar.typeNum = 9819
    	pageNewsICalendar.10 < styles.content.get
    	pageNewsICalendar.10.select.where = colPos=0 AND list_type = "news_pi1"
    	pageNewsICalendar.10.select {
    		orderBy = sorting ASC
    		max = 1
    	}

    	config {
    		# deactivate Standard-Header
    		disableAllHeaderCode = 1
    		# no xhtml tags
    		xhtml_cleaning = none
    		admPanel = 0
    		metaCharset = utf-8
    		# you need an english locale to get correct rfc values for <lastBuildDate>, ...
    		locale_all = en_EN
    		# define charset
    		additionalHeaders = Content-Type:text/calendar;charset=utf-8
    		disablePrefixComment = 1
    	}

    	# set the format
        plugin.tx_news.settings.format = ical
        # set the domain for real unique uids
        plugin.tx_news.settings.domain.data = getEnv:HTTP_HOST
        plugin.tx_news.settings.useStdWrap = domain

        # delete content wrap
        tt_content.stdWrap >
    [global]

**Some explanations**
The page object ``pageNewsICalendar`` will render only those content elements which are in colPos 0 and are a news plugin. Therefore all other content elements won't be rendered in the iCalendar feed.

Misc
^^^^

Add a link to the iCalendar feed in the list view
"""""""""""""""""""""""""""""""""""""""""""""""""

To be able to render a link in the header section of the normal page which points to the iCalendar feed you can use something like this in your List.html fluid template.

.. code-block:: html

    <n:headerData>
        <link rel="alternate" type="text/calendar" title="iCalendar 2.0" href="{f:uri.page(additionalParams:{type:9819})}" />
    </n:headerData>


Change the iCalendar feed link with RealURL
"""""""""""""""""""""""""""""""""""""""""""

If you want to rewrite the URL, use a configuration like this one.

.. code-block:: php

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT'] = array(
    	'fileName' => array (
    		'defaultToHTMLsuffixOnPrev' => 0,
    		'acceptHTMLsuffix' => 1,
    		'index' => array(
    			'calendar.ical' => array(
    				'keyValues' => array(
    					'type' => 9819,
    				)
    			),
    		)
    	)
    );

This will change the URL to :code:`/feed.ical`.
