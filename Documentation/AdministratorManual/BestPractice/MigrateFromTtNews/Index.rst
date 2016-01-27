.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _migrateFromTtNews:

Migrate from tt_news
--------------------

This tutorial will help you to migrate records from the extension tt_news to news.

.. only:: html

.. contents::
       :local:
       :depth: 3



Requirements
^^^^^^^^^^^^

* Installed extension news
* Installed extension news_ttnewsimport: It is available in the TER and at https://github.com/fsaris/news_ttnewsimport.

It is not required to have tt_news installed or available in the installation. All it needs are the database table and the images and files used in the records.

Migration
^^^^^^^^^

To be able to migrate records, you need to activate the import module.
This needs to be done in the configuration of EXT:news inside the extension manager.

#. Activate the checkbox "Show importer", save and reload the backend. Now you should see the backend module "News Import".

#. Switch to the backend module.

#. Select "Import tt_news category records" from the select box and start the import of categories.

#. Select "Import tt_news news records" from the select box and start the import of news records.

Migration of the plugins
""""""""""""""""""""""""

The plugins of tt_news can be migrated to plugins of EXT:news as well. This is done by using the CLI:

.. code-block:: bash

	./typo3/cli_dispatch.phpsh extbase ttnewspluginmigrate:run
	./typo3/cli_dispatch.phpsh extbase ttnewspluginmigrate:removeOldPlugins

Read more about the migration and its limitation in the documentation of news_ttnewsimport at https://github.com/fsaris/news_ttnewsimport.

Not migrated
^^^^^^^^^^^^

* TypoScript configuration
* Fields of records which are added by 3rd party extensions