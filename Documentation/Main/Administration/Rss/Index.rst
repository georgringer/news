.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


RSS
^^^

Displaying a RSS feed is the same as a normal list view, just with a different template.
Therefore you won't need any different configuration to e.g. excluded categories or configure the single view page.

The template for the RSS feed can be found in the file Resources/Private/Templates/News/List.xml.
The "magic" which uses the List.xml template instead of the List.html is the following configuration: ::

   plugin.tx_news.settings.format = xml

Support for Atom
******************

The **Atom** format is supported since version 2.2.0. All you need to do is change the format to *atom*: ::

   plugin.tx_news.settings.format = atom



RSS feed by TypoScript
""""""""""""""""""""""
A very simple way to generate the RSS feed is using plain TypoScript. All you need is to use the given TypoScript and adopt it to your needs. ::

    [globalVar = TSFE:type = 9818]
    config {
    	disableAllHeaderCode = 1
    	xhtml_cleaning = none
    	admPanel = 0
    	metaCharset = utf-8
    	additionalHeaders = Content-Type:text/xml;charset=utf-8
    	disablePrefixComment = 1
    }

    pageNewsRSS = PAGE
    pageNewsRSS {
    	typeNum = 9818
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
    			format = xml
    		}
    	}
    }
    [global]

This example will show all news records which don't have the category with the uid 9 assigned and are saved on the page with uid 24. The single view page is the one with uid 25.

The RSS feed itself can be found with the link **/?type=9818**.

Change the RSS feed link with RealURL
*************************************
If you want to rewrite the URL, use a configuration like this one. ::

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT'] = array(
    	'fileName' => array (
    		'defaultToHTMLsuffixOnPrev' => 0,
    		'acceptHTMLsuffix' => 1,
    		'index' => array(
    			'feed.rss' => array(
    				'keyValues' => array(
    					'type' => 9818,
    				)
    			),
    		)
    	)
    );

This will change the URL to /feed.rss


RSS feeds by using a normal plugin
""""""""""""""""""""""""""""""""""

Sometimes it is more convenient to generate the RSS feed using the normal plugin.
The biggest advantage is that the complete configuration can be done within the backend without touching TypoScript.

To create a RSS feed based on a plugin follow this steps:

#. Create a new page.

#. Add the news plugin and define the configuration you need. E.g. startingpoint, page with the single view, ...

#. Define a new TypoScript template and use a code like below.  **Very
   important** : Use config.absRefPrefix = http://www.yourdomain.tld/ to
   produce absolute urls for links and images! ::

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
             additionalHeaders = Content-Type:text/xml;charset=utf-8
             disablePrefixComment = 1
      }

      # set the format
      plugin.tx_news.settings.format = xml

      # delete content wrap
      tt_content.stdWrap >

**Important:** If your output still contains HTML code, please check your TypoScript
(especially from css\_styled\_content) as this HTML is produced there!

Automatic RSS feeds - based on plugins
""""""""""""""""""""""""""""""""""""""
There are usecases where many different list views are needed and each list view should also get its own RSS feed **without any additional configuration**.

The TypoScript code looks like this. ::

    [globalVar = TSFE:type = 9818]
    	lib.stdheader >
    	tt_content.stdWrap.innerWrap >
    	tt_content.stdWrap.wrap >
    	# get away <div class="feEditAdvanced-firstWrapper" ...> if your logged into the backend
    	styles.content.get.stdWrap >

    	pageNewsRSS = PAGE
    	pageNewsRSS.typeNum = 9818
    	pageNewsRSS.10 < styles.content.get
    	pageNewsRSS.10.select.where = colPos=0 AND list_type = "news_pi1"
    	pageNewsRSS.10.select {
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
    		additionalHeaders = Content-Type:text/xml;charset=utf-8
    		disablePrefixComment = 1
    		baseURL = http://www.domain.tld/
    		absRefPrefix = http://www.domain.tld/
    	}

    	# set the format
    	plugin.tx_news.settings.format = xml
    [global]

**Some explanations**
The page object pageNewsRSS will render only those content elements which are in colPos 0 and are a news plugin. Therefore all other content elements won't be rendered in the RSS feed.

Add a link to the RSS feed in the list view
********************************************

To be able to render a link in the header section of the normal page which points to the RSS feed you can use something like this in your List.html fluid template. ::

    <n:headerData>
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<f:uri.page additionalParams="{type:9818}"/>" />
    </n:headerData>


RSS feed configuration
""""""""""""""""""""""

Don't forget to configure the RSS feed properly as the sample template won't fulfill your needs completely. Please look up the constants and change the mentioned settings. ::

    plugin.tx_news {
    	rss.channel {
    		title = Dummy Title
    		description =
    		link = http://example.com
    		language = en_GB
    		copyright = TYPO3 News
    		category =
    		generator = TYPO3 EXT:news
    	}
    }

