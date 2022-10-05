.. include:: /Includes.rst.txt

.. _cacheClearing:

==============
Cache clearing
==============


.. _cacheClearing-changed-records:

Clearing the cache after changing news records
==============================================

News has a built-in mechanism that takes care of clearing the cache after
manipulation of News records.

When a list or detail view is rendered on a page, a cache tag in format
:php:`tx_news_pid_PID` (where PID is the uid of the news storage folder) is
added. Each time a news record is edited, deleted or created, this cache entry
is flushed. No additional cache configuration is needed if only the News
plugins are used.

If you use other ways of displaying news records (e.g. an RSS feed created
by TypoScript on a page without a News plugin), the cache is not flushed
automatically.

This can be done automatically by using this command in the PageTsConfig: :

.. code-block:: typoscript
   :caption: page TSconfig

   TCEMAIN.clearCacheCmd = 123,456,789

The code needs to be added to the sys folder where the news records are edited.
Change the example page ids to the ones which should be cleared, e.g. a page
with an RSS feed. You can use:

.. code-block:: typoscript
   :caption: page TSconfig

   TCEMAIN.clearCacheCmd = pages

to clear the complete caches as well:

.. code-block:: typoscript
   :caption: page TSconfig

   TCEMAIN.clearCacheCmd = cacheTag:tx_news

to clear all caches of pages on which the news plugins are used but beware of
performance issues when news records are edited often.

.. hint::

   The mentioned TCEMAIN settings are part of the TYPO3 Core and can be used
   therefore not only for the news extension. Read more about the
   :ref:`clearCacheCmd in the TSconfig reference <t3tsconfig:pagetcemain-clearcachecmd>`.


.. _cacheClearing-publishing-date:

Cache lifetime and auto publishing (by setting start date)
==========================================================

By default the cache of TYPO3 pages is invalidated every 24 hours. If you set
a specific date and time for the news record to be published in the tab
:guilabel:`Access`:

.. include:: /Images/ManualScreenshots/NewsRecordAccess.rst.txt

The news will be published next time the cache is deleted which is usual after
midnight.

When you are using the :guilabel:`Publish Date` field you can use the following
TypoScript setup configuration: :ref:`config.cache <t3tsref:setup-config-cache>`

Let us assume you have a news plugin on page 42 and store your news records in a
sysfolder with the uid 27 then the following setting will take the publish date
of your news records into account when calculating the time when the cache
should expire:

.. code-block:: typoscript
   :caption: EXT:my_sitepackage/Configuration/TypoScript/setup.typoscript

   config.cache.42 = tx_news_domain_model_news:27

If there is more then one page containing a news plugin that might display the
news you have to make a setting for each page:


.. code-block:: typoscript
   :caption: EXT:my_sitepackage/Configuration/TypoScript/setup.typoscript

   config.cache {
      42 = tx_news_domain_model_news:27
      43 = tx_news_domain_model_news:27
      365 = tx_news_domain_model_news:27
   }

If you use a news plugin on every page it is also possible to define the
cache clearing for all pages:

.. code-block:: typoscript
   :caption: EXT:my_sitepackage/Configuration/TypoScript/setup.typoscript

   config.cache.all = tx_news_domain_model_news:27

You can also define serveral tables for one page, for example to include
categories:

.. code-block:: typoscript
   :caption: EXT:my_sitepackage/Configuration/TypoScript/setup.typoscript

   config.cache.42 = tx_news_domain_model_news:27,sys_category:26
