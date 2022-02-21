.. include:: /Includes.rst.txt

.. highlight:: typoscript
.. _typoscriptGeneral:

================
General settings
================

Any setting needs to be prefixed with  :typoscript:`plugin.tx_news.settings.`.


.. only:: html

   .. contents:: Properties
      :depth: 1
      :local:

.. _tsCssFile:

cssFile
=======

.. confval:: cssFile

   :type: string
   :Default: Depends on the chosen layout
   :Path: plugin.tx_news.settings

   Path to the css file. This is included with the Layouts.

.. _tsFormat:

format
======

.. confval:: format

   :type: string
   :Default: html
   :Path: plugin.tx_news.settings

   Set a different format for the output. Use e.g. :code:`xml` for RSS feeds.

useStdWrap
==========

.. confval:: useStdWrap

   :type: string
   :Default: singleNews
   :Path: plugin.tx_news.settings

   Add all TypoScript properties as a comma separated list which need
   support for stdWrap.

   As an example: ::

      settings {
         useStdWrap = singleNews

         singleNews.stdWrap.cObject = CONTENT
         singleNews.stdWrap.cObject {
            # ...
         }
      }


.. _tsOverrideFlexformSettingsIfEmpty:

overrideFlexformSettingsIfEmpty
===============================


.. confval:: overrideFlexformSettingsIfEmpty

   :type: string
   :Path: plugin.tx_news.settings
   :Default: cropMaxCharacters,dateField,timeRestriction,orderBy,orderDirection,
      backPid,listPid,startingpoint,
      recursive,list.paginate.itemsPerPage,list.paginate.templatePath


   The default behaviour of Extbase is to override settings from
   TypoScript by the one of the FlexForms. This is even valid if the setting is
   left empty in the FlexForms.

   Therefore you can define those settings which value should be taken from
   TypoScript if nothing is set in the plugin.

.. _tsDisplayDummyIfNoMedia:

displayDummyIfNoMedia
=====================


.. confval:: displayDummyIfNoMedia

   :type: boolean
   :Path: plugin.tx_news.settings
   :Default: 1

   If set and no preview image is defined in the record, a placeholder
   image defined via :confval:`list.media.dummyImage` is shown.

.. confval:: list.media.dummyImage

   :type: string
   :Default: typo3conf/ext/news/Resources/Public/Images/dummy-preview-image.png

   If preview image is defined, the defined placeholder is displayed.

Example: Display a dummy image from your sitepackage
----------------------------------------------------

.. code-block:: typoscript
   :caption: my_sitepackage/Configuration/TypoScript/setup.typoscript

   plugin.tx_news.settings {
     displayDummyIfNoMedia = 1
     list.media.dummyImage = EXT:my_sitepackage/Resources/Public/Images/News/MyPreviewImage.png
   }


Example: Remove dummy image from list view
------------------------------------------

.. code-block:: typoscript
   :caption: my_sitepackage/Configuration/TypoScript/setup.typoscript

   plugin.tx_news.settings {
     displayDummyIfNoMedia = 0
   }

.. _tsDetailPidDetermination:

detailPidDetermination
======================

.. confval:: detailPidDetermination

   :type: string, comma separated list of keywords
   :Default: "flexform, categories, default"
   :Path: plugin.tx_news.settings

   This setting defines which page is used for the link to the detail view.
   3 possible options are available which processed in the given order until
   a page has been found.

   flexform
      This setting tries to get the detail page from the plugin's setting
      :confval:`detailPid` :guilabel:`PageId for single news display` which
      can also be set by using TypoScript::

         # If set via TypoScript, also add detailPid to the setting "overrideFlexformSettingsIfEmpty"
         plugin.tx_news.settings.detailPid = 123

   categories
      A detail page can also be defined for every category. Use the field
      **Single-view page for news from this category** for that.

   **default**
      This type tries to get the value from the setting :confval:`defaultDetailPid`::

         plugin.tx_news.settings.defaultDetailPid = 456

.. _tsDefaultDetailPid:

defaultDetailPid
================

.. confval:: defaultDetailPid

   :type: int
   :Default: 0
   :Path: plugin.tx_news.settings

   If :confval:`detailPidDetermination` contains the keyword "default" this
   value is used to determine the pid used for detail links of news records.

.. _tsHideIdList:

hideIdList
==========

.. confval:: hideIdList

   :type: string
   :Default: (none)
   :Path: plugin.tx_news.settings


   Define a list of ids of news articles which are excluded in the view. This
   is similar to the setting :confval:`excludeAlreadyDisplayedNews` but the
   exclusion is defined in TypoScript instead of the template.

Example: Hide current news in the list
--------------------------------------

As an example this excludes the news record of a detail action of the
same page::

   plugin.tx_news.settings {
      useStdWrap := addToList(hideIdList)
      hideIdList.cObject = TEXT
      hideIdList.cObject {
         data = GP:tx_news_pi1|news
      }
   }

.. _tsOrderByAllowed:

orderByAllowed
==============

.. confval:: orderByAllowed

   :type: string
   :Default: sorting,author,uid,title,teaser,author,tstamp,crdate,datetime,categories.title
   :Path: plugin.tx_news.settings


   Due to restrictions of Extbase itself it is required to define all fields
   which are allowed for sorting results.

.. _tsAnalyticsSocial:

analytics.social
================

.. confval:: analytics.social

   :type: array

   Use additional code for google analytics tracking of the social
   functionalities.

   Default::

      analytics.social {
         facebookLike = 1
         facebookShare = 1
         twitter = 1
      }

.. _tsDemandClass:

demandClass
===========

.. confval:: demandClass

   :type: string
   :Path: plugin.tx_news.settings

   Overload the demand object which is used to build the queries. Read more
   about how to use and extend :ref:`demands <demands>`.

.. _tsLinkHrDate:

link.hrDate
===========

.. confval:: link.hrDate

   :type: boolean / array
   :Default: 0
   :Path: plugin.tx_news.settings

   The url to a single news record contains only the uid of the record.
   Sometimes it is nice to have the date in url too (for example
   :samp:`https://example.org/news/2021/8/news-title.html`).

   If this setting is enabled parameters for year, month and day are added
   to the URL. A :ref:`Routing configuration <routing>` can then be used
   to create a human readable date like the example above.

   Each parameter (day, month, year) can be separately configured by using
   the full options of the `php function date()
   <http://at2.php.net/manual/en/function.date.php>`_ . This example will
   add the day as a number without leading zeros, the month with leading
   zeros and the year by using 4 digits::

      link = 1
      link {
         hrDate = 1
         hrDate {
            day = j
            month = m
            year = Y
         }
      }

   This option is only applied if the build-in
   :ref:`LinkViewHelper <viewHelperLink>` is used

.. _tsLinkTypesOpeningInNewWindow:

link.typesOpeningInNewWindow
============================

.. confval:: link.typesOpeningInNewWindow

   :type: string
   :Default: 2
   :Path: plugin.tx_news.settings

   Comma separated list of news types which open with :html:`target="_blank"`
   Default is 2 which is the news type "Link to external page".

   This option is only applied if the build-in
   :ref:`LinkViewHelper <viewHelperLink>` is used.

.. _tsFacebookLocale:

facebookLocale
==============

.. confval:: facebookLocale

   :type: string
   :Default: en\_US
   :Path: plugin.tx_news.settings

    Facebook locale which is used to translate facebook texts.

   Examples are de\_DE, fr\_FR, ...


.. _tsOpengraph:

opengraph
=========

.. confval:: opengraph

   :type: array
   :Path: plugin.tx_news.settings

   Additional open graph properties can be defined using TypoScript.
   Those are included in the the template partial
   :file:`EXT:news/Resources/Private/Partials/Detail/Opengraph.html`.

   The most important properties are filled automatically:

   og:title
      is filled with the field **Alternative title** or if that is empty
      with the **Title**.

   og:description
      is filled with the field **Description** or if that is empty with
      the **Teaser**.

   og:image
      is filled with the first preview image.

   og:url
      is filled with the current url.

   Check out https://dev.twitter.com/cards/getting-started for more information
   regarding the twitter cards.

   Default::

      opengraph {
         site_name =  {$plugin.tx_news.opengraph.site_name}
         type = article
         locale =
         admins =
         twitter {
            card = {$plugin.tx_news.opengraph.twitter.card}
            site = {$plugin.tx_news.opengraph.twitter.site}
            creator = {$plugin.tx_news.opengraph.twitter.creator}
         }
      }

.. _tsDetailMedia:

detail.media
============

.. confval:: detail.media

   :type: array
   :Path: plugin.tx_news.settings

   Configuration for media elements in the detail view.

   .. attention::
      If you need different options like using **width** instead of
      **maxWidth** you need also to adopt the template files.

   Default::

      detail.media {
         image {
            maxWidth = 282
            maxHeight =

            # If using fluid_styled_content
            lightbox {
               enabled = {$styles.content.textmedia.linkWrap.lightboxEnabled}
               class = {$styles.content.textmedia.linkWrap.lightboxCssClass}
               width = {$styles.content.textmedia.linkWrap.width}
               height = {$styles.content.textmedia.linkWrap.height}
            }
            # If using css_styled_content, use those ssettings
            # lightbox {
            #    enabled = {$styles.content.imgtext.linkWrap.lightboxEnabled}
            #    class = {$styles.content.imgtext.linkWrap.lightboxCssClass}
            #    width = {$styles.content.imgtext.linkWrap.width}
            #    height = {$styles.content.imgtext.linkWrap.height}
            #    rel = lightbox[myImageSet]
            # }
         }

         video {
            width = 282
            height = 300
         }
      }



.. _tsDetailErrorHandling:

detail.errorHandling
====================

.. confval:: detail.errorHandling

   :type: string
   :Path: plugin.tx_news.settings
   :Default: "showStandaloneTemplate,EXT:news/Resources/Private/Templates/News/DetailNotFound.html,404"

   If no news entry is found, it is possible to use various types of error handling.

   showStandaloneTemplate
      A template is rendered. The syntax is
      `showStandaloneTemplate,<path>,<errorCode>`, for example
      `showStandaloneTemplate,EXT:news/Resources/Private/Templates/News/DetailNotFound.html,404`

   redirectToListView
      Redirect to the list view on the same page.

   redirectToPage
      Redirect to any page by using the syntax redirectToPage,<pageid>,<status>.
      This means e.g. redirectToPage,123,404 to redirect to the page with UID 123 and error code 404.

   pageNotFoundHandler
      The page not found handler defined in the site configuration is called.

Example: Show a custom not found template
-----------------------------------------

If the current news record is not found, show the custom template instead and
return the HTTP-code `404` (not found)::

   plugin.tx_news.settings.detail.errorHandling = showStandaloneTemplate,EXT:my_sitepackage/Resources/Private/Templates/NotFound.html,404

Example: Redirect to page 123 if news record is not found
---------------------------------------------------------

If the current news record is not found, forward to page 123 with
the HTTP-code `301` (moved permanently)::

   plugin.tx_news.settings.detail.errorHandling = redirectToPage,123,301


.. _tsDetailCheckPidOfNewsRecord:

detail.checkPidOfNewsRecord
===========================

.. confval:: detail.checkPidOfNewsRecord

   :type: boolean
   :Default: 0

   If set, the detail view checks the incoming news record against the defined
   :confval:`startingpoint`.

   If those don't match, the news record won't be displayed and
   :confval:`detail.errorHandling` applied.

.. _tsDetailShowMetaTags:

detail.showMetaTags
===================

.. confval:: detail.showMetaTags

   :type: boolean
   :Path: plugin.tx_news.settings
   :Default:  1

   If enabled, the meta tags including title, description and various
   open graph tags (defined in :confval:`opengraph`) are rendered.

.. _tsDetailShowPrevNext:

detail.showPrevNext
===================

.. confval:: detail.showPrevNext

   :type: boolean
   :Default:  0

   If enabled, links to the previous and next news records are shown

.. _tsDetailRegisterProperties:

detail.registerProperties
=========================

.. confval:: detail.registerProperties

   :type: string
   :Path: plugin.tx_news.settings
   :Default: keywords,title

   This property is currently not used.

.. _tsDetailShowSocialShareButtons:

detail.showSocialShareButtons
=============================

.. confval:: detail.showSocialShareButtons

   :type: boolean
   :Default: 1

   If the extension `rx_shariff <https://extensions.typo3.org/extension/rx_shariff>`__
   is installed and this option is enabled,
   the social share functionality provided by rx\_shariff is shown.

   You can install this extension with composer:

   .. code-block:: bash
      :caption: bash

      composer req reelworx/rx-shariff

list.media
==========

.. confval:: list.media

   :type: array
   :Path: plugin.tx_news.settings

   Configuration for media elements in the list view.

   .. attention::
      If you need different options like using **width** instead of **maxWidth** you need also
      to adopt the template files!

   Default::

      list.media {
         image {
            maxWidth = 100
            maxHeight = 100
         }
      }

.. _tsListPaginate:

list.paginate
=============

.. confval:: list.paginate

   :type: array
   :Path: plugin.tx_news.settings


   EXT:news uses a custom ViewHelper to render the pagination.

   The following settings are available:

   class
      The class that should be used for the pagination

   itemsPerPage
      Define how many items are shown on one page.

   insertAbove
      Set it to `0` to hide the pagination before the actual news items.

   insertBelow
      Set it to `0` to hide the pagination after the actual news items.

   maximumNumberOfLinks
      If set, not all pages of the pagination are shown but only the given amount. Imagine
      1000 news records and 10 items per page. This would result in 100
      links in the frontend.

   Default::

      list.paginate {
         class = GeorgRinger\NumberedPagination\NumberedPagination
         itemsPerPage = 10
         insertAbove = 1
         insertBelow = 1
         maximumNumberOfLinks = 3
      }

.. _tsListRss:

list.rss
========


.. confval:: list.rss

   :type: array
   :Path: plugin.tx_news.settings

   Additional settings for the RSS view.

   See the :ref:`RSS configuration <rss>`.

   Default::

      rss {
         channel {
            title = {$plugin.tx_news.rss.channel.title}
            description = {$plugin.tx_news.rss.channel.description}
            language = {$plugin.tx_news.rss.channel.language}
            copyright = {$plugin.tx_news.rss.channel.copyright}
            generator = {$plugin.tx_news.rss.channel.generator}
            link = {$plugin.tx_news.rss.channel.link}
         }
      }

.. _tsSearchFields:

search.fields
=============

.. confval:: search.fields

   :type: string
   :Path: plugin.tx_news.settings
   :Default: teaser,title,bodytext

   Comma separated list of fields which are used for the search.

   .. hint::
      You can also search in relations, e.g. the category title by using
      :code:`categories.title`

search.splitSearchWord
======================


.. confval:: search.splitSearchWord

   :type: boolean
   :Path: plugin.tx_news.settings
   :Default: 0


   If set to `1`, the search subject will be split by spaces and it will
   not only find the phrase but also if the search terms are scattered
   in a field.

   As an example: Searching for *hello world* will give you as result also
   the news item with the title `hello the world`. The search terms must be
   found in the same field, which means that a news item with the world
   *hello* in the `title` and the word *world* in the bodytext won't be found.

   .. hint::
      If you need a better search experience, think about using something like EXT:solr!
