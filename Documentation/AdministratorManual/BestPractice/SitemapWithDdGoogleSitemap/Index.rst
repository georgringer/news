.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Integration of EXT:dd_googlesitemap
-----------------------------------

Since version 4.2.0 EXT:news supports creating sitemaps using the extension **dd_googlesitemap**.

.. only:: html

    .. contents::
        :local:
        :depth: 1


Configuration
^^^^^^^^^^^^^

After installing dd_googlesitemap you can create sitemaps for the news records by calling the URL
``https://www.yourdomain.tld/index.php?eID=dd_googlesitemap&id=1&sitemap=txnews&singlePid=123&pidList=456&L=0``.

The following parameters need to be configured properly:

**singlePid**

Define the page id which is used to show the news record. The links in the sitemap will point to this page.

**pidList**

Define the page ids on which the news records are saved.

**L**

Define the language uid which should be used. Use one call for each language

**type**

By adding the optional argument ``&type=news`` a news sitemap is used instead of a default sitemap type. Check out https://support.google.com/news/publisher/answer/74288?hl=en for details.

Human readable dates
""""""""""""""""""""
If you need links with human readable dates, you can achieve this with the following TypoScript:

.. code-block:: typoscript

        tx_ddgooglesitemap.tx_news {
          hrDate = 1
          hrDate {
            day = j
            month = n
            year = Y
          }
        }

Skip controller and action
""""""""""""""""""""""""""
To skip the controller and action, use the following TypoScript:

.. code-block:: typoscript

        tx_ddgooglesitemap.tx_news.skipControllerAndAction = 1

Integration
^^^^^^^^^^^
Use the configured URL and add it to the scheduler task of dd_googlesitemap.
