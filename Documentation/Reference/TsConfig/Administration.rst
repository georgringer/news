.. include:: /Includes.rst.txt

.. _tsconfigAdministration:

=====================
Administration module
=====================

Configuration of the :guilabel:`Web > News Administration` module

.. contents:: Properties
   :backlinks: top
   :class: compact-list
   :depth: 1
   :local:

.. _tsconfigPreselect:

preselect
=========

.. confval:: preselect

   :type: array
   :Path: tx_news > module

   Predefine the form in the administration module. The possible fields for
   the preselection are:

   - recursive
   - timeRestriction
   - topNewsRestriction
   - sortingField
   - sortingDirection
   - categoryConjunction

   .. code-block:: typoscript

      # Example:
      tx_news.module {
         preselect {
            topNewsRestriction = 1
         }
      }


.. _tsconfigColumns:

columns
=======

.. confval:: columns

   :type: string
   :Path: tx_news > module
   :Default: teaser,istopnews,datetime,categories


   Define a list of columns which are displayed in the administration module.
   Default is.

Example: Show the columns datetime, archive and author
------------------------------------------------------

.. code-block:: typoscript

    tx_news.module.columns = datetime,archive,author

.. _tsconfigDefaultPid:

defaultPid
==========

.. confval:: defaultPid

   :type: int
   :Path: tx_news > module

   If no page is selected in the page tree, any record created in the
   administration module would be saved on the root page.
   If this is not desired, the pid can be defined by using defaultPid.<tablename>:

Example: Set default pid for new news records
---------------------------------------------

New news records will be saved on the page with ID 123.

.. code-block:: typoscript

   # Example
   tx_news.module.defaultPid.tx_news_domain_model_news = 123


localizationView
================

.. confval:: localizationView

   :type: bool
   :Path: tx_news > module
   :Default: 1

   Ability to disable the localizationView in the administration module. Example:

Example: Disable the localization view
--------------------------------------

.. code-block:: typoscript

    tx_news.module.localizationView = 0

controlPanels
=============

.. confval:: controlPanels

   :type: bool
   :Path: tx_news > module
   :Default: 0

   Display control panels to sort, hide and delete records in the administration
   module.

Example: Enable the control panels
----------------------------------

.. code-block:: typoscript

   tx_news.module.controlPanels = 1

allowedCategoryRootIds
======================

.. confval:: allowedCategoryRootIds

   :type: string, comma separated list of integers
   :Path: tx_news > module

Reduce the shown categories by defining a list of **root** category IDs.

Example:

.. code-block:: none

   Example category tree

   ├── [10] Cat 1
   ├── [12] Cat 2
   ├   └──[13] Cat 2 b
   ├── [14] Cat 3
   └── [15] Cat 4

.. code-block:: typoscript

    tx_news.module.allowedCategoryRootIds = 12,15


.. code-block:: none

   Category tree shown

   ├── [12] Cat 2
   ├   └──[13] Cat 2 b
   └── [15] Cat 4

.. _tsconfigRedirectToPageOnStart:

redirectToPageOnStart
=====================

.. confval:: redirectToPageOnStart

   :type: integer
   :Path: tx_news > module

If no page is selected, the user will be redirected to the given page.

Example: redirect the user to page 456
---------------------------------------

Redirect the user to the page with the uid 456, if no other page is chosen.

.. code-block:: typoscript

   # Example:
   tx_news.module.redirectToPageOnStart = 456

.. _tsconfigAllowedPage:

allowedPage
===========

.. confval:: allowedPage

   :type: integer
   :Path: tx_news > module

If defined, limit the administration module to the given page and always
redirect the user, no matter what defined in the page tree.

Example: Limit the news module to page 123
------------------------------------------

Always redirect the user to the page with the uid 123.

.. code-block:: typoscript

   # Example:
   tx_news.module.allowedPage = 123


.. _tsconfigAlwaysShowFilter_:

alwaysShowFilter
================

.. confval:: alwaysShowFilter

   :type: bool
   :Path: tx_news > module

   If defined, the administration module will always show the filter opened.

Example: Always show the filter
-------------------------------

.. code-block:: typoscript

   # Example:
   tx_news.module.alwaysShowFilter = 1

The user will be redirected to the page with the uid 123.

filters
=======

.. confval:: filters

   :type: array
   :Path: tx_news > module

   Define whether filters should be available or not. By default, all the
   filters are enabled. The available filters are:

   - searchWord
   - timeRestriction
   - topNewsRestriction
   - hidden
   - archived
   - sortingField
   - number
   - categories
   - categoryConjunction
   - includeSubCategories


   .. note::
      ``categoryConjunction`` and ``includeSubCategories`` can only be enabled
      when ``categories`` is enabled.

Example: disable the filter of top news restriction
---------------------------------------------------

.. code-block:: typoscript

   # Example:
   tx_news.module {
      filters {
         topNewsRestriction = 0
      }
   }


