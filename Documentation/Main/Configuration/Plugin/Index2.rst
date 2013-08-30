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
	===================================================== ================================================ =================== =====================


Property details
^^^^^^^^^^^^^^^^


.. _settings-plugin-orderBy:

orderBy
""""""""

:typoscript:`plugin.tx_news.settings.orderBy =` :ref:`t3tsref:data-type-string`

Define the sorting of displayed news records.

The chapter “Extend news > Extend flexforms” shows how the select box can be extended.


.. _settings-plugin-orderDirection::

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