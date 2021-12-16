.. include:: /Includes.rst.txt


.. _dataProcessing_LanguageMenuProcessor:

=====================
LanguageMenuProcessor
=====================

.. versionadded:: 8.5.0
   With version 8.5 a new data processor, :php:`LanguageMenuProcessor` has
   been added.

This data processor renders a correct language menu on detail pages, which
respects if a news record is translated or not.

Usage
=====

.. code-block:: typoscript

   10 = TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor
   10 {
      as = languageMenu
      addQueryString = 1
   }

   11 = GeorgRinger\News\DataProcessing\DisableLanguageMenuProcessor
   11.menus = languageMenu

The property :typoscript:`menus` is a comma-separated list of
:php:`MenuProcessors`.

If a language menu is rendered on a detail page and the
languages are configured to use a strict mode this data processor can be used:

If no translation exists, the property `available` in the
:php:`LanguageMenuProcessor` is set to `false` - just as if the current page
is not translated.


Examples
========

See :ref:`SEO: Language menu on news detail pages <seo_language_menus>`.

