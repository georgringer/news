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
``https://www.yourdomain.tld/index.php?eId=dd_googlesitemap&id=1&sitemap=txnews&singlePid=123&pidList=456``.

The following parameters need to be configured properly:

**singlePid**

Define the page id which is used to show the news record. The links in the sitemap will point to this page.

**pidList**

Define the page ids on which the news records are saved.

**type**

By adding the optional argument ``&type=news`` a news sitemap is used instead of a default sitemap type. Check out https://support.google.com/news/publisher/answer/74288?hl=en for details.

Integration
^^^^^^^^^^^
Use the configured URL and add it to the scheduler task of dd_googlesitemap.