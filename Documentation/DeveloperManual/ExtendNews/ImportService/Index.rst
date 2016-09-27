.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _importService:

Import Service
==============

Follow this chapter to learn how to use the import service to import news records and categories.

The extension provides an import service to import data from various sources. One extension which heavily uses it is the extension `news_ttnewsimport <https://typo3.org/extensions/repository/view/news_ttnewsimport>`_ which imports records from *tt_news*.

Starting the import
-------------------
There are 2 ways how the import can be started:

Import module
^^^^^^^^^^^^^
To be able to use the import module, you need to activate it. This needs to be done in the configuration of EXT:news inside the Extension Manager.

#. Activate the checkbox "Show importer", save and reload the backend. Now you should see the backend module "News Import".
#. Switch to the backend module.

CLI
^^^

Since version `5.3.0` the import can also be started by a CLI call.

.. code-block:: bash

	./typo3/cli_dispatch.phpsh extbase newsimport:run

Use a custom source
-------------------


Extend the importer
-------------------
Sometimes it is necessary to enrich a provided importer. A common use case is that *tt_news* has been extended by 3rd party extensions and those fields must be migrated as well. This can be achieved by using one of the signal slots.

As a requirement you need to create an empty extension. In this example the extension key is `news_importextended` and the vendor `GeorgRinger`. Please adopt those 2 to your needs!

Prehydrate slot
^^^^^^^^^^^^^^^
The prehydrate signal slot can be used to manipulate the data before it is actually used by the importer.

Register the slot in the `ext_localconf.php`

.. code-block:: php

	<?php
	if (!defined('TYPO3_MODE')) {
		die ('Access denied.');
	}

	\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
		'GeorgRainger\\News\\Domain\\Service\\NewsImportService',
		'preHydrate',
		'GeorgRinger\\NewsImportextended\\Aspect\\NewsImportAspect',
		'preHydrate'
	);

Create the file `typo3conf/ext/news_importextended/Classes/Aspect/NewsImportAspect.php:

.. code-block:: php

	<?php

	namespace GeorgRinger\NewsImportextended\Aspect;

	class NewsImportAspect
	{

		public function preHydrate(array $importItem)
		{
			$importItem['title'] = 'Modified title';
			$return = ['importItem' => $importItem];

			return $return;
		}
	}


Posthydrate slot
^^^^^^^^^^^^^^^^
The post hydrate slot can be used to modify the news object after it had been created by the importer. This slot must be used if the field you want to modify is not handled by the importer.

Register the slot in the `ext_localconf.php`

.. code-block:: php

	<?php
	if (!defined('TYPO3_MODE')) {
		die ('Access denied.');
	}

	\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
		'GeorgRinger\\News\\Domain\\Service\\NewsImportService',
		'postHydrate',
		'GeorgRinger\\NewsImportextended\\Aspect\\NewsImportAspect',
		'postHydrate'
	);

Create the file `typo3conf/ext/news_importextended/Classes/Aspect/NewsImportAspect.php:

.. code-block:: php

	<?php

	namespace GeorgRinger\NewsImportextended\Aspect;

	class NewsImportAspect
	{

		/**
		 * @param array $importData
		 * @param \GeorgRinger\News\Domain\Model\News $news
		 * @return void
		 */
		public function postHydrate(array $importData, $news)
		{
			/** @var \GeorgRinger\NewsFeuser\Domain\Model\News $news */
			if ($importData['import_source'] === 'TT_NEWS_IMPORT') {
				// The setter setTxNewsfeuserUser is provided by the extension news_feuser
				$news->setTxNewsfeuserUser('A custom field');
			}
		}
	}

