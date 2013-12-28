.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Simple snippets making EXT:news more awesome
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This section contains snippets which might be useful for your projects as well.

.. tip::

	If you miss a snippet, please open an issue at http://forge.typo3.org/projects/extension-news/issues and report it there, thanks!


Improved back links
"""""""""""""""""""""

The back link on a detail page is a fixed link to a given page. However it might be that you use multiple list views
and want to change the link depending on the given list view.

A nice solution would be to use this JavaScript jQuery snippet: ::

	if ($(".news-backlink-wrap a").length > 0) {
		if(document.referrer.indexOf(window.location.hostname) != -1) {
			$(".news-backlink-wrap a").attr("href","javascript:history.back();").text('Back');
		}
	}


This snippet has been provided by d.ros at http://forge.typo3.org/issues/51966. Thanks!