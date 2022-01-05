.. include:: /Includes.rst.txt

.. _tsconfigGeneral:

=====================
General configuration
=====================

The general configuration covers options available during the creation
and editing of news records.

.. contents:: Properties
   :backlinks: top
   :class: compact-list
   :depth: 1
   :local:

.. _tsconfigTemplateLayouts:

templateLayouts
===============

.. confval:: templateLayouts

   :type: array
   :Path: tx_news

   The select box :guilabel:`Template Layout` inside a plugin can be extended by
   using TSconfig. Contains an array with key-value pairs. It is possible to
   define Groups using the :typoscript:`--div--` keyword.

   allowedColPos
      Limit a layout to a list of colPos values.

Examples: Add template layouts
------------------------------

Show 3 layout options with the specified key / value pairs.

.. code-block:: typoscript

   tx_news.templateLayouts {
         1 = Foobar
         2 = Another one
         3 =  --div--,Group 2
         4 = Blub
   }

You can use this setting within the Fluid template by checking
:fluid:`{settings.templateLayout}`. See a complete example:
:ref:`Template selector tutorial <templatesSelector>`.

By using the configuration :php:`allowedColPos` it is possible to restrict a
template layout to a specific list of colPos values.

.. code-block:: typoscript

   tx_news.templateLayouts {
      1 = Foobar
      2 = Another one
      2.allowedColPos = 1,2,3
      3 =  --div--,Group 2
      4 = Blub
      4.allowedColPos = 0,1
   }

.. _tsconfigArchive:

archive
=======

.. confval:: archive

   :type: string
   :Path: tx_news

   Use strtotime (see `http://www.php.net/strtotime <http://www.php.net/strtotime>`__ )
   to predefine the archive date

Example: Set the archive date
-----------------------------

Set the archive date on the the next friday:

.. code-block:: typoscript

   # Example:
   tx_news.predefine.archive = next friday


.. _tsconfigTagPid:

tagPid
======

.. confval:: tagPid

   :type: integer
   :Path: tx_news

   Besides the configuration in the
   :ref:`Extension Configuration <extensionConfigurationTagPid>` it is also
   possible to define the pid of tags created directly in the news record by
   using TSconfig:


Example: store new tags on page 123
-----------------------------------

Store new tags on page 123.

.. code-block:: typoscript

   # Example:
   tx_news.tagPid = 123


.. _tsconfigCategoryRestrictionForFlexForms:

categoryRestrictionForFlexForms
===============================

.. confval:: categoryRestrictionForFlexForms

   :type: bool
   :Path: tx_news

   After defining the category restriction in the
   :ref:`Extension Configuration <extensionConfigurationCategoryRestriction>`
   it is also possible to restrict the categories in the news plugin.
   This needs to enabled by TsConfig:

   .. code-block:: typoscript

      # Example:
      tx_news.categoryRestrictionForFlexForms = 1


.. _tsconfigShowContentElementsInNewsSysFolder:

showContentElementsInNewsSysFolder
==================================

.. confval:: showContentElementsInNewsSysFolder

   :type: bool
   :Path: tx_news

If a sys folder is configured with **Contains Plugin:** `News`,
content elements are hidden on those pages in the page and list module.
If the content elements should be shown, use the Page TsConfig.

.. code-block:: typoscript

   # Example:
   tx_news.showContentElementsInNewsSysFolder = 1
