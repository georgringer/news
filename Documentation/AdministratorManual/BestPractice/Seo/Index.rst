.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _seo:

=================
SEO with EXT:news
=================

This chapters covers all configurations which are relevant for search engine optimization
regarding the news extension.

.. note::

        All settings described require TYPO3 9 and the the system extension "seo" installed.


.. only:: html

.. contents::
        :local:
        :depth: 2

Page title for single news
--------------------------
EXT:news implements a custom *pageTitleProvider* `\GeorgRinger\News\Seo\NewsTitleProvider` which is called through the controller.

It can be configured using TypoScript:

.. code-block:: typoscript

   plugin.tx_news.settings.detail {
      pageTitle = 1
      pageTitle {
         # Register alternative provider
         provider = GeorgRinger\News\Seo\NewsTitleProvider
         # Comma separated list of properties which should be checked, 1st value is used
         properties = teaser,title
      }
   }

It is also possible to set the page title through the template by using:

.. code-block:: html

   <n:titleTag>
      <f:format.htmlentitiesDecode>{newsItem.title}</f:format.htmlentitiesDecode>
   </n:titleTag>

Please disable the usage of the page title provider by using

.. code-block:: typoscript

   plugin.tx_news.settings.detail.pageTitle = 0

XML Sitemap
-----------
The sitemap includes links to all news records. This makes it easier for search engines to find all news records and to index those.

Depending on your requirements you can either use the simple sitemap provider from the core or a custom one shipped with EXT:news.

Basic sitemap
~~~~~~~~~~~~~
The core ships a basic sitemap configuration which can also be used for news records:

.. code-block:: typoscript

   plugin.tx_seo.config {
       xmlSitemap {
           sitemaps {
               news {
                   provider = TYPO3\CMS\Seo\XmlSitemap\RecordsXmlSitemapDataProvider
                   config {
                       table = tx_news_domain_model_news
                       additionalWhere =
                       sortField = sorting
                       lastModifiedField = tstamp
                       changeFreqField = sitemap_changefreq
                       priorityField = sitemap_priority
                       pid = 26
                       recursive = 2
                       url {
                           pageId = 25
                           fieldToParameterMap {
                               uid = tx_news_pi1[news]
                           }

                           additionalGetParameters {
                               tx_news_pi1.controller = News
                               tx_news_pi1.action = detail
                           }

                           useCacheHash = 1
                       }
                   }
               }
           }
       }
   }


Extended sitemap
~~~~~~~~~~~~~~~~

The :php:`GeorgRinger\News\Seo\NewsXmlSitemapDataProvider` provides the same feature set as
 :php:`RecordsXmlSitemapDataProvider` but with some additional ones on top:

- If you are using the feature to define the detail page through the field
  *Single-view page for news from this category* of a **sys_category** you need to use a custom provider.
- If you are need urls containing day, month or year information
- Setting `excludedTypes` to exclude certain news types from the sitemap
- Setting `googleNews` to load the news differently as required for Google News (newest news first and limit to last two days)

To enable the category detail page handling, checkout the setting `useCategorySinglePid = 1` in the following full example:

.. code-block:: typoscript

   plugin.tx_seo {
       config {
           xmlSitemap {
               sitemaps {
                   news {
                       provider = GeorgRinger\News\Seo\NewsXmlSitemapDataProvider
                       config {
                           excludedTypes = 1,2
                           additionalWhere =
                           ## enable these two lines to generate a Google News sitemap
                           # template = EXT:news/Resources/Private/Templates/News/GoogleNews.xml
                           # googleNews = 1

                           sortField = datetime
                           lastModifiedField = tstamp
                           pid = 84
                           recursive = 2
                           url {
                               pageId = 116
                               useCategorySinglePid = 1

                               hrDate = 0
                               hrDate {
                                   day = j
                                   month = n
                                   year = Y
                               }

                               fieldToParameterMap {
                                   uid = tx_news_pi1[news]
                               }

                               additionalGetParameters {
                                   tx_news_pi1.controller = News
                                   tx_news_pi1.action = detail
                               }

                               useCacheHash = 1
                           }
                       }
                   }
               }
           }
       }
   }

Multiple Sitemaps
~~~~~~~~~~~~~~~~~

With TYPO3 10 it is possible to define multiple sitemaps. This can be used to define a normal sitemap and one for google news. This example adds another sitemap for the google news and defines a new type.

.. code-block:: typoscript

   plugin.tx_seo {
      config {
         xmlSitemap {
            sitemaps {
               news {
                  provider = GeorgRinger\News\Seo\NewsXmlSitemapDataProvider
                  config {
                     # ...
                  }
               }

         }
         googleNewsSitemap {
            sitemaps {
               news < plugin.tx_seo.config.xmlSitemap.sitemaps.news
               news {
                  config {
                     template = EXT:news/Resources/Private/Templates/News/GoogleNews.xml
                     googleNews = 1
                  }
               }
            }
         }
      }
   }

   seo_sitemap_news < seo_sitemap
   seo_sitemap_news {
      typeNum = 1533906436
      10.sitemapType = googleNewsSitemap
   }

This sitemap can be added in the site config so it has a nice url:

.. code-block:: yaml
   :linenos:

   routeEnhancers:
     PageTypeSuffix:
       map:
         news_sitemap.xml: 1533906436

Hreflang on news detail pages
-----------------------------
If using languages with the language mode `strict`, the hreflang tag must only be generated if the according news record is translated as well!

.. note::
   This feature is only supported by TYPO3 10, described at https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/Hooks/Events/Frontend/ModifyHrefLangTagsEvent.html.

EXT:news reduces the rendered hreflang attributes by using this event and checking the availability of the records.

Check availability in fluid templates
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
If you are building a language menu and want to check if the news record is available, you can use the ViewHelper
:html:`<n:check.pageAvailableInLanguage language="{languageId}">`. A full example can look like this:

.. code-block:: html

   <ul>
       <f:for each="{LanguageMenu}" as="item">
           <f:if condition="{item.available}">
               <n:check.pageAvailableInLanguage language="{item.languageId}">
                   <li class="language-switch {f:if(condition:item.active, then:'active')}">
                       <a href="{item.link}">{item.navigationTitle}</a>
                   </li>
               </n:check.pageAvailableInLanguage>
           </f:if>
       </f:for>
   </ul>
