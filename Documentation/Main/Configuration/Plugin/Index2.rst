.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _settings-plugin:

Plugin
---------------------

Properties
^^^^^^^^^^

.. container:: ts-properties

	===================================================== ================================================ =================== =====================
	Property                                              Data type                                        Default              Available in plugin
	===================================================== ================================================ =================== =====================
	orderBy_                                                 :ref:`t3tsref:data-type-string`                 datetime             [x]
	orderDirection_                                          :ref:`t3tsref:data-type-string`                 desc                 [x]
	categories_                                              :ref:`t3tsref:data-type-string`                                      [x]
	categoryConjunction_                                     :ref:`t3tsref:data-type-string`                                      [x]
	includeSubCategories_                                    :ref:`t3tsref:data-type-boolean`                0                    [x]
	archiveRestriction_                                      :ref:`t3tsref:data-type-string`                                      [x]
	timeRestriction_                                         :ref:`t3tsref:data-type-string`                                      [x]
	topNewsRestriction_                                      :ref:`t3tsref:data-type-string`                                      [x]
	startingpoint_                                           :ref:`t3tsref:data-type-string`                                      [x]
	recursive_                                               :ref:`t3tsref:data-type-integer`                                     [x]
	singleNews_                                              :ref:`t3tsref:data-type-integer`                                     [x]
	enablePreviewOfHiddenRecords_                            :ref:`t3tsref:data-type-integer`                                     [x]
	dateField_                                               :ref:`t3tsref:data-type-integer`               datetime              [x]
	detailPid_                                               :ref:`t3tsref:data-type-integer`                                     [x]
	backPid_                                                 :ref:`t3tsref:data-type-integer`                                     [x]
	listPid_                                                 :ref:`t3tsref:data-type-integer`                                     [x]
	limit_                                                   :ref:`t3tsref:data-type-integer`                                     [x]
	offset_                                                  :ref:`t3tsref:data-type-integer`                                     [x]
	topNewsFirst_                                            :ref:`t3tsref:data-type-integer`                                     [x]
	excludeAlreadyDisplayedNews_                             :ref:`t3tsref:data-type-integer`                                     [x]
	disableOverrideDemand_                                   :ref:`t3tsref:data-type-integer`                                     [x]
	===================================================== ================================================ =================== =====================


Property details
^^^^^^^^^^^^^^^^

.. _settings-orderBy:

orderBy
""""""""

:typoscript:`plugin.tx_news.settings.orderBy =` :ref:`t3tsref:data-type-string`

Define the sorting of displayed news records.

The chapter “Extend news > Extend flexforms” shows how the select box can be extended.


.. _settings-orderDirection::

orderDirection
""""""""""""""

:typoscript:`plugin.tx_news.settings.orderDirection =` :ref:`t3tsref:data-type-string`

Define the sorting direction which can either be "asc" for ascending or "desc" descending.


.. _settings-categories::

categories
""""""""""

:typoscript:`plugin.tx_news.settings.categories =` 1,2,3

Define the news categories which are taken into account when getting the correct news records.

.. caution::
	Don't forget to set the category mode too! See property below.


.. _settings-categories::

categoryConjunction
"""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.categoryConjunction =` or

The category mode defines who selected categories are checked. 5 options are available:

**1) Don't care, show all**

There is no restriction based on categories, even if categories are defined.

**2) Show items with selected categories (OR)**

All news records which belong to at least one of the selected
categories are shown.

**3) Show items with selected categories (AND)**

All news records which belong to  **all** selected categories are
shown.

**4) Do NOT show items with selected categories (OR)**

This is the negation of #2. All news records which don't belong to any
of the selected categories are shown.

**5) Do NOT show items with selected categories (AND)**

This is the negation of #3. All news records which don't belong to all
selected categories are shown.


.. _settings-includeSubCategories::

includeSubCategories
"""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.includeSubCategories =1`

Include subcategories in the category selection


.. _settings-archiveRestriction::

archiveRestriction
"""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.archiveRestriction =1`

News records can hold an optional archive date. 2 modes are available:

**active: Only active (non archived)**

All news records with an archive date before the current date are
shown.

**archived: Archived**

All news records with an archive date in the past are shown.

.. hint:: Records with no archive date aren't shown in any of the selected modes.


.. _settings-timeRestriction::

timeRestriction
"""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.timeRestriction =-1 week`

The time limit offers 2 different options:

**Time in seconds**

Only news records with a maximum age (compared to the “Date & Time”
field) are shown.

Example: An input like “86400” shows only news records which are one
day (60 seconds \* 60 minutes \* 24 hours) old.

**Time in words**

It is also possible to define the maximum age in words. Examples are:

- 3 days
- last Monday
- 10 months 3 days 2 hours

Words need to be in English and are translated by using `strtotime <http://de.php.net/strtotime>`_ .


.. _settings-topNewsRestriction::

topNewsRestriction
"""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.topNewsRestriction =2`

Any news record can be set as “Top News”. Therefore it is possible to show news records depending on this flag.

**1: Only Top News records**

Only news records which the checkbox set are shown.

**2: Except Top News records**

Only news records which don't have the checkbox set are shown.


.. _settings-startingpoint::

startingpoint
"""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.startingpoint =12,345`

If a startingpoint is set, all news records which are saved on one of the selected pages are shown, otherwise news of all pages are shown.


.. _settings-recursive::

recursive
"""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.recursive = 2`

The search for pages as startingpoint can be extended by setting a recursive level.


.. _settings-singleNews::

singleNews
"""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.singleNews =789`

It is possible to show a specific news record by selecting it the Detail view and the desired news record.


.. _settings-singleNews::

singleNews
"""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.singleNews =789`

It is possible to show a specific news record in the Detail view if the uid is set with this property.


.. _settings-enablePreviewOfHiddenRecords::

enablePreviewOfHiddenRecords
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.enablePreviewOfHiddenRecords =1`

If set, also records which are normally hidden are displayed. This is especially helpful when using a detail view as preview mode for editors.

.. note:: Be aware to secure the page (e.g. using a TS condition to make it available only if an BE user is logged in) as this page could be called by anyone using any news record uid to see its content.


.. _settings-dateField::

dateField
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.dateField =1`

The date menu builds a menu by year and month and the given news records. The menu can either be built by using the date field or the archive field.


.. _settings-detailPid::

detailPid
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.detailPid =12`

This page is uses as target for the detail view. If nothing set, the current page is used.
Be aware that this setting gets overridden (if set by TS pidDetailFromCategories) and a detail pid inside related category records.


.. _settings-backPid::

backPid
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.backPid =12`

Define a page for the detail view to return to. This is typically the page on which the list view can be found.


.. _settings-listPid::

listPid
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.listPid =12`

This page is uses as target for the listings, e.g. the date menu and the Search form.


.. _settings-limit::

limit
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.limit =10`

Define the maximum records shown.

.. note:: This is not about the pagination but a general limit!


.. _settings-offset::

offset
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.offset =3`

Define the offset. If set to e.g. 2, the first 2 records are not shown. This is especially useful in combination with multiple plugins on the same page and the setting “Max records displayed”.


.. _settings-topNewsFirst::

topNewsFirst
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.topNewsFirst =1`

If checked, news records with the checkbox “Top News” are shown before the others, no matter which sorting configuration is used.


.. _settings-excludeAlreadyDisplayedNews::

excludeAlreadyDisplayedNews
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.excludeAlreadyDisplayedNews =1`

If checked, news items which are already rendered are excluded in the current plugin. To exclude news items, the viewHelper <n:excludeDisplayedNews newsItem="{newsItem}" /> needs to be added to the template.

.. note:: The order of rendering in the frontend is essential as the information which news record is shown and should not be included anymore is fetched during runtime.


.. _settings-disableOverrideDemand::

disableOverrideDemand
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.disableOverrideDemand =1`

If set, the settings of the plugin can't be overridden by arguments in the URL. The override is used, e.g. to show only news of a given category (category given in the URL).


.. _settings-cropMaxCharacters::

cropMaxCharacters
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.cropMaxCharacters =100`

Define the maximum length of the teaser text before it is cropped.


.. _settings-templateLayout::

templateLayout
""""""""""""""""""""""""""""

:typoscript:`plugin.tx_news.settings.templateLayout = 123`

Select different layouts. See the section Templating > Custom Templates by using the Layout selector