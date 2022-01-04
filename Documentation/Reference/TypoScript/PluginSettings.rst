.. include:: /Includes.rst.txt

.. highlight:: typoscript
.. _typoscriptPlugin:

===============
Plugin settings
===============

This section covers all settings, which can be defined in the plugin itself.
To improve the usability, only those settings are shown which are needed by
the chosen view (The setting :confval:`orderBy` is for example not needed in the single view).

.. important::
   Every setting can also be defined by TypoScript setup. However, please inform
   yourself about the setting :confval:`overrideFlexformSettingsIfEmpty`.


.. only:: html

   .. contents:: Properties
      :depth: 1
      :local:


Sheet general
=============

.. _tsOrderBy:

Sort by `orderBy`
-----------------

.. confval:: orderBy

   :type: string
   :Default: 'datetime'
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   Define the sorting of displayed news records.
   The chapter ":ref:`Extend news > Extend flexforms <extendFlexforms>`"
   shows how the select box can be extended.

.. _tsOrderDirection:

Sort direction `orderDirection`
-------------------------------

.. confval:: orderDirection

   :type: string
   :Default: 'desc'
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   Define the sorting direction which can either be "asc" for
   ascending or "desc" descending. This can be either *asc* or *desc*.

   ::

      plugin.tx_news.settings.orderDirection = asc

.. _tsDateField:

Date field to use `dateField`
-----------------------------

.. confval:: dateField

   :type: string
   :Default: 'datetime'
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   The date menu builds a menu by year and month and the given news records.
   The menu can either be built by using the date field or the archive field.

.. _tsCategories:

Category selection `categories`
-------------------------------

.. confval:: categories

   :type: string
   :Default: (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   Define the news categories which are taken into account when getting the
   correct news records

   ::

      plugin.tx_news.settings.categories = 1,2,3

   .. caution::

      Don't forget to set the category mode too! See property below.

.. _tsCategoryConjunction:

Category mode `categoryConjunction`
-----------------------------------

.. confval:: categoryConjunction

   :type: int
   :Default: 0 (Don't care, show all)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup


   The category mode defines how selected categories are checked. 5 options are available:

   `1` (Don't care, show all)
      There is no restriction based on categories, even if categories are defined.

   `2` (Show items with selected categories (OR))
      All news records which belong to at least one of the selected categories are shown.

   `3` (Show items with selected categories (AND))
      All news records which belong to  all selected categories are shown.

   `4` (Do NOT show items with selected categories (OR))
      This is the negation of #2. All news records which don't belong to any of the selected categories are shown.

   `5` (Do NOT show items with selected categories (AND))
      This is the negation of #3. All news records which don't belong to all selected categories are shown.

   ::

      plugin.tx_news.settings.categoryConjunction = 2

.. _tsIncludeSubCategories:

Include subcategories `includeSubCategories`
--------------------------------------------

.. confval:: includeSubCategories

   :type: boolean
   :Default: 0
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   Include subcategories in the category selection

   ::

      plugin.tx_news.settings.includeSubCategories = 1


.. _tsArchiveRestriction:

Archive `archiveRestriction`
----------------------------

.. confval:: archiveRestriction

   :type: string
   :Default: (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.archiveRestriction = active

   News records can hold an optional archive date. 2 modes are available:

   `active`: Only active (non archived)
      All news records with an archive date in the future are shown.

   `archived`: Archived
      All news records with an archive date in the past are shown.

   .. hint:: Records with no archive date aren't shown in any of the selected modes.

.. _tsTimeRestriction:

Time limit (LOW) `timeRestriction`
----------------------------------

.. confval:: timeRestriction

   :type: string
   :Default: (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.timeRestriction =-1 week

   The time limit offers 3 different options.

   **Date**

   A date in the format `HH:mm DD-MM-YYYY` can be set and only news records that are newer than this date are shown.

   Example: 15:30 01-04-2020 (April 1st, 2020 at 3.30 pm)

   **Time in seconds**

   Only news records with a maximum age (compared to the :guilabel:`Date & Time` field) are shown.

   Example: An input like :code:`86400` shows only news records which are one day (60 seconds \* 60 minutes \* 24 hours) old.

   **Time in words**

   It is also possible to define the maximum age in words. Examples are:

   - -3 days
   - last Monday
   - -10 months 3 days 2 hours

   Words need to be in English and are translated by using `strtotime <http://de.php.net/strtotime>`__ .

.. _tsTimeRestrictionHigh:

Time limit (HIGH) `timeRestrictionHigh`
---------------------------------------

.. confval:: timeRestrictionHigh

   :type: string
   :Default: (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   See :confval:`timeRestriction` above. The configuration is the same but for the higher time end.

.. _tsTopNewsRestriction:

Top news `topNewsRestriction`
-----------------------------

.. confval:: topNewsRestriction

   :type: int
   :Default: 0

   ::

      plugin.tx_news.settings.topNewsRestriction =2

   Any news record can be set as :guilabel:`Top News`. Therefore it is possible
   to show news records depending on this flag.

   `1`: Only Top News records
      Only news records which the checkbox set are shown.

   `2`: Except Top News records
      Only news records which don't have the checkbox set are shown.

.. _tsSingleNews:

Show a single news record `singleNews`
--------------------------------------

.. confval:: singleNews

   :type: int
   :Default: 0
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.singleNews =789

   It is possible to show a specific news record in the Detail view if the uid is set with this property.

.. _tsPreviewHiddenRecords:

Allow preview of hidden records `previewHiddenRecords`
------------------------------------------------------

.. confval:: previewHiddenRecords

   :type: int
   :Default: 0
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.previewHiddenRecords = 1

   If set, also records which are normally hidden are displayed. This is
   especially helpful when using a detail view as preview mode for editors.
   The setting :confval:`enablePreviewOfHiddenRecords` is needed (instead of
   :confval:`previewHiddenRecords`) if the detail view plugin is used and the plugin
   configuration option :confval:`previewHiddenRecords` is set to
   "Defined in TypoScript" (value `2`).

   .. note::
      Be aware to secure the page (e.g. using a TypoScript condition to make it
      available only if an backend user is logged in) as this page could
      be called by anyone using any news record uid to see its content.

   .. note::
      If set, any hidden records on the current page are shown as well!


.. confval:: enablePreviewOfHiddenRecords

   :type: int
   :Default: 0
   :Path: plugin.tx_news.settings
   :Scope: TypoScript Setup

   ::

      plugin.tx_news.settings.previewHiddenRecords = 2
      plugin.tx_news.settings.enablePreviewOfHiddenRecords = 1

   If :confval:`previewHiddenRecords` is set to `2` the setting of
   :confval:`enablePreviewOfHiddenRecords` is used instead.

.. _tsStartingpoint:

Startingpoint `startingpoint`
-----------------------------

.. confval:: startingpoint

   :type: string
   :Default: (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.startingpoint =12,345

   If a startingpoint is set, all news records which are saved on one
   of the selected pages are shown, otherwise news of all pages are shown.

.. _tsRecursive:

Recursive `recursive`
---------------------

.. confval:: recursive

   :type: int
   :Default: 0 (No recursion)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.recursive = 2

   The search for pages as startingpoint can be extended by setting a recursive
   level.

Sheet additional
================

.. _tsDetailPid:

PageId for single news display `detailPid`
------------------------------------------

.. confval:: detailPid

   :type: int
   :Default: 0 (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.detailPid =12

   This page is used as target for the detail view. If nothing set, the current
   page is used.

   .. hint::
      Be aware that this setting might not be used, depending on the setting
      :confval:`detailPidDetermination`.

.. _tsListPid:

PageId for list display `listPid`
=================================

.. confval:: listPid

   :type: int
   :Default: 0 (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.listPid =12

   This page is used as target for the listings, for example the date menu and
   the search form.

.. _tsBackPid:

PageId to return to `backPid`
-----------------------------

.. confval:: backPid

   :type: int
   :Default: 0 (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.backPid =12

   Define a page for the detail view to return to. This is typically the page on which the list view can be found.

.. _tsLimit:

Max records displayed `limit`
-----------------------------

.. confval:: limit

   :type: int
   :Default: 0 (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.limit =10

   Define the maximum records shown.

.. _tsOffset:

Starting with given news record `offset`
----------------------------------------

.. confval:: offset

   :type: int
   :Default: (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.offset = 3

   Define the offset. If set to e.g. 2, the first 2 records are not
   shown. This is especially useful in combination with multiple plugins on
   the same page and the setting :confval:`limit`.

.. _tsTags:

Tags `tags`
-----------

.. confval:: tags

   :type: string
   :Default: (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   Add a constraint to the given tags

.. _tsHidePagination:

Hide the pagination `hidePagination`
------------------------------------

.. confval:: hidePagination

   :type: boolean
   :Default: 0 (do not hide)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   If defined, the pagination is not shown.

.. _tsListPaginateItemsPerPage:

Items per Page `list.paginate.itemsPerPage`
-------------------------------------------

.. confval:: list.paginate.itemsPerPage

   :type: int
   :Default: 10
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   Define the amount of news items shown per page in the pagination.

.. _tsTopNewsFirst:

Sort "Top news" before `topNewsFirst`
-------------------------------------

.. confval:: topNewsFirst

   :type: boolean
   :Default: 0 (Do not show top news first)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.topNewsFirst =1

   If set, news records with the checkbox **"Top News"** are shown before
   the others, no matter which sorting configuration is used.

.. _tsExcludeAlreadyDisplayedNews:

Exclude already displayed news `excludeAlreadyDisplayedNews`
-------------------------------------------------------------

.. confval:: excludeAlreadyDisplayedNews

   :type: boolean
   :Default: 0 (Do not exclude)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.excludeAlreadyDisplayedNews =1

   If checked, news items which are already rendered are excluded in the
   current plugin.

   **To exclude news items, the viewHelper <n:excludeDisplayedNews newsItem="{newsItem}" />
   needs to be added to the template.**

   .. note::
      The order of rendering in the frontend is essential as the information
      which news record is shown and should not be included anymore is fetched
      during runtime.

.. _tsDisableOverrideDemand:

Disable override demand `disableOverrideDemand`
-----------------------------------------------

.. confval:: disableOverrideDemand

   :type: boolean
   :Default: 1 (Disable override)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.disableOverrideDemand =1

   If set, the settings of the plugin can't be overridden by arguments in
   the URL. Read more about :ref:`demands <demands>`.

Sheet template
==============

.. _tsMediaMaxWidth:

Max width for media elements `media.maxWidth`
---------------------------------------------

.. confval:: media.maxWidth

   :type: int
   :Default: 0 (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   Maximum width of assets

.. _tsMediaMaxHeight:

Max height for media elements `media.maxHeight`
-----------------------------------------------

.. confval:: media.maxHeight

   :type: int
   :Default: 0 (none)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   Maximum height of assets

.. _tsCropMaxCharacters:

Length of teaser (in chars) `cropMaxCharacters`
-----------------------------------------------

.. confval:: cropMaxCharacters

   :type: int
   :Default: 0 (do not crop)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.cropMaxCharacters =100

   Define the maximum length of the teaser text before it is cropped.

.. _tsTemplateLayout:

Template Layout `templateLayout`
--------------------------------

.. confval:: templateLayout

   :type: string
   :Default: (none, use default)
   :Path: plugin.tx_news.settings
   :Scope: Plugin, TypoScript Setup

   ::

      plugin.tx_news.settings.templateLayout = 123

   Select different layouts. See :ref:`this section <tsconfigTemplateLayouts>`
   how to add layouts.
