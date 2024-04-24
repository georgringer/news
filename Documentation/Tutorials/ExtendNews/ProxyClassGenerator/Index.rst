.. _proxyClassGenerator:

=================
Add custom fields
=================

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

The files are saved by using the Caching Framework in the directory :file:`typo3temp/Cache/Code/news`.

.. contents::
      :local:
      :depth: 1

1) Add a new field in the backend
---------------------------------
To add new fields, use either the extension `extension_builder <http://typo3.org/extensions/repository/view/extension_builder>`__ or create the extension from scratch.

The extension key used in this examples is :code:`eventnews`.

Create the fields
^^^^^^^^^^^^^^^^^
3 files are basically all what you need:

ext_emconf.php
""""""""""""""
The file  :file:`ext_emconf.php` holds all basic information about the extension like the title, description and version number.

.. code-block:: php

   <?php

   $EM_CONF[$_EXTKEY] = [
      'title' => 'news events',
      'description' => 'Events for news',
      'category' => 'plugin',
      'author' => 'Georg Ringer',
      'author_email' => '',
      'state' => 'alpha',
      'uploadfolder' => false,
      'createDirs' => '',
      'clearCacheOnLoad' => true,
      'version' => '1.0.0',
      'constraints' => [
         'depends' => [
            'typo3' => '7.6.13-8.7.99',
            'news' => '6.2.0-6.9.99',
         ],
         'conflicts' => [],
         'suggests' => [],
      ],
   ];

SQL definition
""""""""""""""
Create the file :file:`ext_tables.sql` in the root of the extension directory with the following content:

.. code-block:: sql


   # Table structure for table 'tx_news_domain_model_news '
   #
   CREATE TABLE tx_news_domain_model_news (
      location_simple varchar(255) DEFAULT '' NOT NULL
   );


TCA definition
""""""""""""""
The TCA defines which tables and fields are available in the backend and how those are rendered (e.g. as input field, textarea, select field, ...).

In this example, the table :sql:`tx_news_domain_model_news` will be extended by a simple input field.
Therefore, create the file :file:`Configuration/TCA/Overrides/tx_news_domain_model_news.php`.

.. code-block:: php

   <?php
   defined('TYPO3') or die();

   $fields = [
      'location_simple' => [
         'exclude' => 1,
         'label' => 'My location',
         'config' => [
            'type' => 'input',
            'size' => 15
         ],
      ]
   ];

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

Create the file :file:`ext_localconf.php` in the root of the extension:

.. code-block:: php

   <?php
   defined('TYPO3') or die();

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News']['eventnews'] = 'eventnews';

:php:`Domain/Model/News` is the namespace to the class which should be extended and :code:`eventnews` is the extension key.

Custom class
^^^^^^^^^^^^
As the class :php:`Domain/Model/News` should be extended, create a file at the same path in the own extension which is
:file:`path/to/eventnews/Classes/Domain/Model/News.php`:

.. code-block:: php

   <?php

   namespace GeorgRinger\Eventnews\Domain\Model;

   class News extends \GeorgRinger\News\Domain\Model\News
   {
      protected string $locationSimple;

      public function getLocationSimple(): string
      {
         return $this->locationSimple;
      }

      public function setLocationSimple(string $locationSimple)
      {
         $this->locationSimple = $locationSimple;
      }
   }

.. hint::

   If you are using the extension :file:`extension_builder`, this class might have been created for you already.

.. important::

   If you reference other objects, you must define the full namespace at the location and don't use namespace imports (with "use")!

Clear system cache
^^^^^^^^^^^^^^^^^^
Now it is time to clear the :guilabel:`system cache`, either via the dropdown in the backend or in the module :guilabel:`Admin Tools`.

