.. include:: /Includes.rst.txt

.. _dataProcessing_AddNewsToMenuProcessor:

======================
AddNewsToMenuProcessor
======================

.. versionadded:: 7.2.0
   With version 7.2 a new data processor, :php:`AddNewsToMenuProcessor` has
   been added which is useful for detail pages to add the news record as
   fake menu entry.

This data processor can be used to add the currently displayed news record
to a pages breadcrumb.

Usage
=====

.. code-block:: typoscript

   10 = TYPO3\CMS\Frontend\DataProcessing\MenuProcessor
   10 {
      as = breadcrumbMenu
      special = rootline
      # [...] further configuration
   }
   20 = GeorgRinger\News\DataProcessing\AddNewsToMenuProcessor
   20.menus = breadcrumbMenu,specialMenu

The property :typoscript:`menus` is a comma-separated list of
:php:`MenuProcessors` that shall have the currently displayed news record
attached as fake menu entry.

Examples
========

See :ref:`Tutorial: Breadcrumb based on data processing and Fluid <breadcrumbFluid>`.
