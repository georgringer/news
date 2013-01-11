.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Migration from tt_news to news
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This tutorial will help you to migrate records from tt_news to news.

**Important:** The implementation of the importer is rather basic, please look at the `Bugtracker <http://forge.typo3.org/projects/extension-news/issues>`_ for open issues with the category "*Importer*".

Requirements
"""""""""""""

* Installed extension tt_news
* Installed extension news (at least version 2.0.0)

Migration
""""""""""

To be able to migrate records, you need to activate the import module.
This needs to be done in the configuration of EXT:news inside the extension manager.

#. Activate the checkbox "Show importer", save and reload the backend. Now you should see the backend module "News Import".

#. Switch to the backend module.

#. Select "Import tt_news category records" from the select box and start the import of categories.

#. Select "Import tt_news news records" from the select box and start the import of news records.

Not migrated
************

The following things are not migrated:

* Plugins
* TypoScript configuration
* Fields of records which are added by 3rd party extensions
