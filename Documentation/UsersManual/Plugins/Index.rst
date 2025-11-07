.. _plugin:

======
Plugin
======

The news plugin is used to output a defined list of records.

It can be added to create a content element with the type
:guilabel:`Plugin` and by selecting the plugin type :guilabel:`News system`.

.. TODO: screenshot



.. contents:: The available actions are:
    :local:
    :depth: 1

.. _plugin-list:

List
^^^^
The list action displays a list of defined records. The filters can be defined either in the plugin or by using TypoScript and are described :ref:`here <ts>`.

2 different list modes are available.

News article list (incl. detail view)
"""""""""""""""""""""""""""""""""""""
CType: `news_pi1`

If a plugin is configured to show this implementation of the list action, it will change its behaviour if the URL contains a link to a detail action!
If this is fulfilled, the detail action will be shown instead of the list action.

News article list
"""""""""""""""""
CType: `news_newsliststicky`

The described behaviour of the first implementation might not be desired all the time.
A typical example is to show a list view in the sidebar of a detail view page. If the above action would be used, the detail view would be shown in the sidebar too.

News article list (curated)
"""""""""""""""""""""""""""
CType: `news_newsselectedlist`

Show a specific list of articles.

.. _plugin-detail:

News article detail view
^^^^^^^^^^^^^^^^^^^^^^^^
CType: `news_newsdetail`

There are 2 typical use cases for this action:

- Use this action to show a full news record which is linked by a list view.
- Provide a specific news record to output that one. This is very useful to embed a news record e.g. in a newsletter.

.. _plugin-dateMenu:

Date menu of news articles
^^^^^^^^^^^^^^^^^^^^^^^^^^
CType: `news_newsdatemenu`

Use this action to show a list of news items grouped by the date. A typical output can look like: ::

   2015
      January: 13 entries
      February: 9 entries
      March: 6 entries
      June: 4 entries
      ...
   2014
      March: 81 entries
      April: 32 entries
      May: 1 entry


If you define a specific page id in field :guilabel:`PageId for list display` (inside the tab :guilabel:`Additional`) and
placing a news plugin with the type :guilabel:`List` there, it is possible to create a date filter.

.. _plugin-searchForm:

News Search form
^^^^^^^^^^^^^^^^
CType: `news_newssearchform`

Use this action to show a basic search inside news records.

.. _plugin-searchResult:

News Search result
^^^^^^^^^^^^^^^^^^
CType: `news_newssearchresult`

The search result action is based on the list action including an additional filter provided by the search form.

.. _plugin-categoryList:

News Category menu
^^^^^^^^^^^^^^^^^^
CType: `news_categorylist`

Use this action to show a list of categories. The categories are listed as tree.

If you define a specific page id in field :guilabel:`PageId for list display` (inside the tab "*Additional*") and
placing a news plugin with the type :guilabel:`List` there, it is possible to create a category filter.

.. tip::

   To have the selected category available, the checkbox "Disable override demand" must not be set in the category plugin.


.. _plugin-tagList:

News Tag list
^^^^^^^^^^^^^
CType: `news_taglist`

Use this action to show a list of tags.

If you define a specific page id in field :guilabel:`PageId for list display` (inside the tab "*Additional*") and
placing a news plugin with the type :guilabel:`List` there, it is possible to create a tag filter.

.. tip::

   To keep the tags available after selecting one, the checkbox "Disable override demand" must be active in the tag plugin.
