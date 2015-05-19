.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Clearing the cache after editing records
----------------------------------------

News has a built-in mechanism that takes care of clearing the cache after manipulation of News records.

When a list or detail view is rendered on a page, a cache tag in format ``tx_news_pid_PID`` (where PID is the uid of the news storage folder) is added. Each time a news record is edited, deleted or created, this cache entry is flushed. No additional cache configuration is needed if only the News plugins are used.

If you use other ways of displaying news records (e.g. an RSS feed created by TypoScript on a page without a News plugin), the cache is not flushed automatically.

This can be done automatically by using this command in the PageTsConfig: ::

	TCEMAIN.clearCacheCmd = 123,456,789

The code needs to be added to the sys folder where the news records are edited. Change the example page ids to the ones which should be cleared, e.g. a page with an RSS feed.
You can use: ::

	TCEMAIN.clearCacheCmd = pages

to clear the complete caches as well ::

	TCEMAIN.clearCacheCmd = cacheTag:tx_news

to clear all caches of pages on which the news plugins are used but beware of performance issues when news records are edited often.

.. Hint::

	The mentioned TCEMAIN settings are part of the TYPO3 core and can be used therefore not only for the news extension.
