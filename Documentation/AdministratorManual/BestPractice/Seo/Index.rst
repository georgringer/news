.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _rss:

=================
SEO with EXT:news
=================

This chapters covers all configurations which are relevant for search engine optimization
regarding the news extension.

.. Info::

        All settings described require TYPO3 9 and the the system extension "seo" installed.


.. only:: html

.. contents::
        :local:
        :depth: 2


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
