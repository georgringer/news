.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _settings-plugin:

Plugin
---------------------

.. only:: html

	.. contents::
:local:
		:depth: 1


Properties
^^^^^^^^^^

.. container:: ts-properties

	===================================================== ================================================ =================== =====================
	Property                                              Data type                                        Default              Available in plugin
	===================================================== ================================================ =================== =====================
	orderBy_                                                 :ref:`t3tsref:data-type-string`                 datetime
	orderDirection_                                          :ref:`t3tsref:data-type-string`                 desc
	===================================================== ================================================ =================== =====================


Property details
^^^^^^^^^^^^^^^^

.. only:: html

	.. contents::
:local:
		:depth: 1


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
