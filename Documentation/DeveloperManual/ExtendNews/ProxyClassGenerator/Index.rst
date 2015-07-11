.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _proxyClassGenerator:

ProxyClass generator
====================

Follow this chapter to learn how to add new fields or actions.

It is important to know how this concept is implemented. If a class should be extended, EXT:news will generate
a new file containing the original class of the extension itself and all other classes which should extended it.

Take a look at the following 2 working examples:

- Add relation to a FE user: https://github.com/cyberhouse/t3ext-newsauthor
- Add an image gallery to a news record: https://github.com/cyberhouse/t3ext-news_gallery

.. attention:: This generator works only with the news version 3.2.0 or higher.

.. warning:: The drawbacks are easy to identify:

 	- Don't use any use statements as those are currently ignored!
 	- It is not possible to override an actual method or property!

The files are saved by using the Caching Framework in the directory ``typo3temp/Cache/Code/news``.

.. only:: html

	.. contents::
		:local:
		:depth: 1

1) Add a new field in the backend
---------------------------------
To add new fields, use either the extension **extension_builder** (http://typo3.org/extensions/repository/view/extension_builder) or create the extension from scratch.

The extension key used in this examples is ``eventnews``.

Create the fields
^^^^^^^^^^^^^^^^^
3 files are basically all what you need:

ext_emconf.php
""""""""""""""
The file  ``ext_emconf.php`` holds all basic information about the extension like the title, description and version number.

.. code-block:: php

	<?php

	$EM_CONF[$_EXTKEY] = array(
		'title' => 'news events',
		'description' => 'Events for news',
		'category' => 'plugin',
		'author' => 'Georg Ringer',
		'author_email' => '',
		'state' => 'alpha',
		'uploadfolder' => FALSE,
		'createDirs' => '',
		'clearCacheOnLoad' => TRUE,
		'version' => '1.0.0',
		'constraints' => array(
			'depends' => array(
				'typo3' => '6.2.0-7.2.99',
				'news' => '3.2.0-3.2.99',
			),
			'conflicts' => array(),
			'suggests' => array(),
		),
	);

SQL definition
""""""""""""""
Create the file ``ext_tables.sql`` in the root of the extension directory with the following content:

.. code-block:: sql


	# Table structure for table 'tx_news_domain_model_news '
	#
	CREATE TABLE tx_news_domain_model_news (
		location_simple varchar(255) DEFAULT '' NOT NULL
	);


TCA definition
""""""""""""""
The TCA defines which tables and fields are available in the backend and how those are rendered (e.g. as input field, textarea, select field, ...).

In this example, the table ``tx_news_domain_model_news`` will be extended by a simple input field.
Therefore, create the file ``Configuration/TCA/Overrides/tx_news_domain_model_news.php``.

.. code-block:: php

	<?php
	defined('TYPO3_MODE') or die();

	$fields = array(
		'location_simple' => array(
			'exclude' => 1,
			'label' => 'My location',
			'config' => array(
				'type' => 'input',
				'size' => 15
			),
		)
	);

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $fields);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', 'location_simple');


Install the extension
^^^^^^^^^^^^^^^^^^^^^
Now you should be able to install the extension and if you open a news record, you should see the new field in the last tab.

.. TODO: what if something wrong


2) Register the class
---------------------

Until now, EXT:news won't use the new field because it doesn't know about it. To change that, you need to register your new model.

Registration
^^^^^^^^^^^^

Create the file ``ext_localconf.php`` in the root of the extension:

.. code-block:: php

	<?php
	defined('TYPO3_MODE') or die();

	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News'][] = 'eventnews';

**Domain/Model/News** is the namespace to the class which should be extended and **eventnews** is the extension key.

Custom class
^^^^^^^^^^^^
As the class ``Domain/Model/News`` should be extended, create a file at the same path in the own extension which is
``typo3conf/ext/eventnews/Classes/Domain/Model/News.php``:

.. code-block:: php

	<?php

	namespace GeorgRinger\Eventnews\Domain\Model;

	/**
	 * News
	 */
	class News extends \GeorgRinger\News\Domain\Model\News {

		/**
		 * @var string
		 */
		protected $locationSimple;

		/**
		 * @return string
		 */
		public function getLocationSimple() {
			return $this->locationSimple;
		}

		/**
		 * @param string $locationSimple
		 */
		public function setLocationSimple($locationSimple) {
			$this->locationSimple = $locationSimple;
		}
	}

.. hint:: If you are using the extension extension_builder, this class might have been created for you already.

Clear system cache
^^^^^^^^^^^^^^^^^^
Now it is time to clear the **system cache**, either via the dropdown in the backend or in the Install Tool.

