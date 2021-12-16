.. include:: /Includes.rst.txt

.. _rss:

========
RSS feed
========

Displaying a RSS feed is the same as a normal list view, just with a different template.
Therefore you won't need any different configuration to e.g. excluded categories or configure the single view page.

.. only:: html

.. contents::
        :local:
        :depth: 3


The template for the RSS feed can be found in the file Resources/Private/Templates/News/List.xml.
The "magic" which uses the List.xml template instead of the List.html is the following configuration:

.. code-block:: typoscript

   plugin.tx_news.settings.format = xml
   # If you want atom, use
   plugin.tx_news.settings.format = atom


RSS feed by TypoScript
^^^^^^^^^^^^^^^^^^^^^^

A very simple way to generate the RSS feed is using plain TypoScript. All you need is to use the given TypoScript and adopt it to your needs.

.. code-block:: typoscript

    pageNewsRSS = PAGE
    pageNewsRSS {
       # Override the typeNum if you have more than one feed
       typeNum = {$plugin.tx_news.rss.channel.typeNum}
       config {
          disableAllHeaderCode = 1
          xhtml_cleaning = none
          admPanel = 0
          debug = 0
          disablePrefixComment = 1
          metaCharset = utf-8
          additionalHeaders.10.header = Content-Type:application/rss+xml;charset=utf-8
          absRefPrefix = {$plugin.tx_news.rss.channel.link}
          linkVars >
       }
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
             # Override the typeNum if you have more than one feed, must be the same as above!
             #list.rss.channel.typeNum = {$plugin.tx_news.rss.channel.typeNum}
          }
       }
    }

This example will show all news records which don't have the category with the uid 9 assigned and are saved on the page with uid 24. The single view page is the one with uid 25.

The RSS feed itself can be found with the link :code:`/?type=9818`.

RSS feeds by using a normal plugin
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Sometimes it is more convenient to generate the RSS feed using the normal plugin.
The biggest advantage is that the complete configuration can be done within the backend without touching TypoScript.

To create a RSS feed based on a plugin follow this steps:

#. Create a new page.

#. Add the news plugin and define the configuration you need. E.g. startingpoint, page with the single view, ...

#. Define a new TypoScript template and use a code like below.  **Very
   important**: Use :typoscript:`config.absRefPrefix = http://www.yourdomain.tld/` to
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

         # define charset
         metaCharset = utf-8
         additionalHeaders.10.header = Content-Type:application/rss+xml;charset=utf-8
         disablePrefixComment = 1
         linkVars >
      }

      # set the format
      plugin.tx_news.settings.format = xml

      # delete content wrap
      tt_content.stdWrap >
      tt_content.stdWrap.editPanel = 0

      # Use custom template for List.html of EXT:fluid_styled_content
      lib.contentElement.templateRootPaths.5 = EXT:news/Resources/Private/Examples/Rss/fluid_styled_content/Templates

.. warning::
 If your output still contains HTML code, please check your TypoScript (especially fluid\_styled\_content) as this HTML is produced there!

Automatic RSS feeds - based on plugins
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

There are usecases where many different list views are needed and each list view should also get its own RSS feed **without any additional configuration**.

The TypoScript code looks like this.

.. code-block:: typoscript

   [globalVar = TSFE:type = {$plugin.tx_news.rss.channel.typeNum}]
      lib.stdheader >
      tt_content.stdWrap.innerWrap >
      tt_content.stdWrap.wrap >
      tt_content.stdWrap.editPanel = 0
      # get away <div class="feEditAdvanced-firstWrapper" ...> if your logged into the backend
      styles.content.get.stdWrap >

      # Use custom template for List.html of EXT:fluid_styled_content
      lib.contentElement.templateRootPaths.5 = EXT:news/Resources/Private/Examples/Rss/fluid_styled_content/Templates

      pageNewsRSS = PAGE
      pageNewsRSS.typeNum = {$plugin.tx_news.rss.channel.typeNum}
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
         # define charset
         metaCharset = utf-8
         # you need an english locale to get correct rfc values for <lastBuildDate>, ...
         locale_all = en_EN
         # CMS 8 (adjust if using ATOM)
         additionalHeaders.10.header = Content-Type:application/xml;charset=utf-8
         disablePrefixComment = 1
         baseURL = {$plugin.tx_news.rss.channel.link}
         absRefPrefix = {$plugin.tx_news.rss.channel.link}
         linkVars >
      }

      # set the format
      plugin.tx_news.settings.format = xml
   [global]

**Some explanations**
The page object pageNewsRSS will render only those content elements which are in colPos 0 and are a news plugin. Therefore all other content elements won't be rendered in the RSS feed.




Misc
^^^^

RSS feed configuration
""""""""""""""""""""""

Don't forget to configure the RSS feed properly as the sample template won't fulfill your needs completely. Please look up the constants and change the mentioned settings.

.. code-block:: typoscript

   plugin.tx_news.rss.channel {
      title = Dummy Title
      description =
      link = http://example.com
      language = en-gb
      copyright = TYPO3 News
      category =
      generator = TYPO3 EXT:news
   }


Add a link to the RSS feed in the list view
"""""""""""""""""""""""""""""""""""""""""""

To be able to render a link in the header section of the normal page which points to the RSS feed you can use something like this in your List.html Fluid template.

.. code-block:: html

    <n:headerData>
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{f:uri.page(pageType: settings.list.rss.channel.typeNum)}" />
    </n:headerData>

Troubleshooting
^^^^^^^^^^^^^^^

Entity 'nbsp' not defined
"""""""""""""""""""""""""

If you are getting this error, the easiest thing is to replace the character by using TypoScript:

.. code-block:: typoscript

   pageNewsRSS.10.stdWrap.replacement {
      10  {
         search = &nbsp;
         replace = &#160;
      }
   }
