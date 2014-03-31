.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Plugin
^^^^^^^^^

The most important configuration settings can be done through the
settings of a plugin. As not every configuration is needed for every
view, the not needed are hidden.

Because of using Extbase every setting can also be done by using
TypoScript but remember that the settings of the plugin always
override the ones from TypoScript.


Tab “Settings”
""""""""""""""

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :View:
         View:

   :Description:
         Description:

   :Key:
         Key:


 - :Property:
         What to display

   :View:
         All

   :Description:
         Selection of view:

         - List view: List of all news records which fit the configuration

         - Detail view: Shows the complete news record

         - Search from: Search form to search for news records [yet to be done]

         - Search result: View to show result of search form action [yet to be
           done]

         - Date menu: Menu based on the dates of news records

         - Category menu: A category tree

         - Tag list: List all tags

   :Key:
         -


 - :Property:
         Sort by

   :View:
         List view, Search result

   :Description:
         Define the sorting of displayed news records.

         The chapter “Extend news > Extend flexforms” shows how the select box
         can be extended.

   :Key:
         orderBy


 - :Property:
         Sort direction

   :View:
         List view, Search result

   :Description:
         Define the sorting direction which can either be ascending or
         descending.

   :Key:
         orderDirection


 - :Property:
         Category selection

   :View:
         List view, Search result, Date menu

   :Description:
         Define the news categories which are taken into account when getting
         the correct news records. Don't forget to set the category mode too!

   :Key:
         categories


 - :Property:
         Category mode

   :View:
         List view, Search result, Date menu

   :Description:
         The category mode defines who selected categories are checked. 5
         options are available:

         **1) Don't care, show all**

         There is no restriction based on categories.

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

   :Key:
         categoryConjunction


 - :Property:
         Include subcategories

   :View:
         List view, Search result, Date menu

   :Description:
         Include subcategories in the category selection

   :Key:
         includeSubCategories


 - :Property:
         Archive

   :View:
         List view, Search result, Date menu

   :Description:
         News records can hold an optional archive date. 2 modes are available:

         **Only active (non archived)**

         All news records with an archive date before the current date are
         shown.

         **Archived**

         All news records with an archive date in the past are shown.

         **Important:** Records with no archive date aren't shown in any of the
         selected modes.

   :Key:
         archiveRestriction


 - :Property:
         Time limit

   :View:
         List view, Search result, Date menu

   :Description:
         The time limit offers 2 different options:

         **Time in seconds**

         Only news records with a maximum age (compared to the “Date & Time”
         field) are shown.

         Example: An input like “86400” shows only news records which are one
         day (60 seconds \* 60 minutes \* 24 hours) old.

         **Time in words**

         It is also possible to define the maximum age in words. Examples are:

         - \- 3 days

         - last Monday

         - \- 10 months 3 days 2 hours

         Words need to be in English and are translated by using `strtotime
         <http://de.php.net/strtotime>`_ .

   :Key:
         timeRestriction


 - :Property:
         Top News

   :View:
         List view, Search result, Date menu

   :Description:
         Any news record can be set as “Top News”. Therefore it is possible to
         show news records depending on this flag.

         **Only Top News records**

         Only news records which the checkbox set are shown.

         **Except Top News records**

         Only news records which don't have the checkbox set are shown.

   :Key:
         topNewsRestriction


 - :Property:
         Startingpoint

   :View:
         List view, Search result, Date menu

   :Description:
         If a startingpoint is set, all news records which are saved on one of
         the selected pages are shown, otherwise news of all pages are shown.

   :Key:
         startingpoint


 - :Property:
         Recursive

   :View:
         List view, Search result, Date menu

   :Description:
         The search for pages as startingpoint can be extended by setting a
         recursive level.

   :Key:
         recursive


 - :Property:
         Show a single news record

   :View:
         Detail view

   :Description:
         It is possible to show a specific news record by selecting it the
         Detail view and the desired news record.

   :Key:
         singleNews


 - :Property:
         Allow preview of hidden records

   :View:
         Detail view

   :Description:
         If set, also records which are normally hidden are displayed. This is
         especially helpful when using a detail view as preview mode for
         editors.

         Be aware to secure the page (e.g. using a TS condition to make it
         available only if an BE user is logged in) as this page could be
         called by anyone using any news record uid to see its content.

   :Key:
         enablePreviewOfHiddenRecords


 - :Property:
         Date field to use

   :View:
         Date menu

   :Description:
         The date menu builds a menu by year and month and the given news
         records. The menu can either be built by using the date field or the
         archive field.

   :Key:
         dateField


Tab “Additional”
""""""""""""""""

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :View:
         View:

   :Description:
         Description:

   :Key:
         Key:


 - :Property:
         PageId for single news display

   :View:
         List view, Search result, Date menu, Detail view

   :Description:
         This page is uses as target for the detail view. If nothing set, the
         current page is used. Be aware that this setting gets overridden (if
         set by TS pidDetailFromCategories) and a detail pid inside related
         category records.

   :Key:
         detailPid


 - :Property:
         PageId to return to

   :View:
         Detail view

   :Description:
         Define a page for the detail view to return to. This is typically the
         page on which the list view can be found.

   :Key:
         backPid


 - :Property:
         PageId for list display

   :View:
         Date menu

   :Description:
         @todo

   :Key:
         listPid


 - :Property:
         Max records displayed

   :View:
         List view, Search result

   :Description:
         Define the maximum records shown. This is not about the pagination
         which can be configured by TypoScript

   :Key:
         limit


 - :Property:
         Start with news item #

   :View:
         List view, Search result

   :Description:
         Define the offset. If set to e.g. 2, the first 2 records are not
         shown. This is especially useful in combination with multiple plugins
         on the same page and the setting “Max records displayed”.

   :Key:
         offset


 - :Property:
         Sort “Top news” before

   :View:
         List view, Search result

   :Description:
         If checked, news records with the checkbox “Top News” are shown before
         the others, no matter which sorting configuration is used.

   :Key:
         topNewsFirst


 - :Property:
         Exclude already displayed news

   :View:
         All

   :Description:
         If checked, news items which are already rendered are excluded in the current plugin.
         To exclude news items, the viewHelper <n:excludeDisplayedNews newsItem="{newsItem}" />
         needs to be added to the template.

   :Key:
         excludeAlreadyDisplayedNews


 - :Property:
         Disable override demand

   :View:
         All

   :Description:
         If set, the settings of the plugin can't be overridden by arguments in
         the URL. The override is used, e.g. to show only news of a given
         category (category given in the URL).

   :Key:
         disableOverrideDemand


Tab “Template”
""""""""""""""

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :View:
         View:

   :Description:
         Description:

   :Key:
         Key:


 - :Property:
         Max width for media elements

   :View:
         List view, Search result, Detail view

   :Description:
         Define the maximum with of media elements

   :Key:
         media.maxWidth


 - :Property:
         Max height for media elements

   :View:
         List view, Search result, Detail view

   :Description:
         Define the maximum height of media elements

   :Key:
         media.maxHeight


 - :Property:
         Length of teaser (in chars)

   :View:
         List view, Search result

   :Description:
         Define the maximum length of the teaser text before it is cropped.

   :Key:
         cropMaxCharacters


 - :Property:
         Template Layout

   :View:
         All

   :Description:
         Select different layouts. See the section Templating > Custom Templates by using the Layout selector

   :Key:
         templateLayout

