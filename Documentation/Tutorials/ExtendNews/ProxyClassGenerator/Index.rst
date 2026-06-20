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


   # Table structure for table 'tx_news_domain_model_news'
   #
   CREATE TABLE tx_news_domain_model_news (
      location_simple varchar(255) DEFAULT '' NOT NULL,
      other_categories int(11) DEFAULT '0' NOT NULL
   );


TCA definition
""""""""""""""
The TCA defines which tables and fields are available in the backend and how those are rendered (e.g. as input field, textarea, select field, ...).

In this example, the table :sql:`tx_news_domain_model_news` will be extended by a simple input field and a
2nd category relation. Create the file :file:`Configuration/TCA/Overrides/tx_news_domain_model_news.php`.

.. code-block:: php

   <?php
   defined('TYPO3') or die();

   $fields = [
      'location_simple' => [
         'label' => 'My location',
         'config' => [
            'type' => 'input',
            'size' => 15
         ],
      ],
      'other_categories' => [
         'label' => 'Other categories',
         'config' => [
            'type' => 'category',
         ],
      ],
   ];

   \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $fields);
   \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', 'location_simple,other_categories');


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

   use GeorgRinger\News\Domain\Model\Category;
   use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
   use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

   class News extends \GeorgRinger\News\Domain\Model\News
   {
      protected string $locationSimple = '';

      /**
       * @var ?ObjectStorage<Category>
       */
      #[Lazy]
      protected ?ObjectStorage $otherCategories;

      public function __construct()
      {
         $this->otherCategories = new ObjectStorage();
      }

      public function initializeObject(): void
      {
         $this->otherCategories = $this->otherCategories ?? new ObjectStorage();
      }

      public function getLocationSimple(): string
      {
         return $this->locationSimple;
      }

      public function setLocationSimple(string $locationSimple): void
      {
         $this->locationSimple = $locationSimple;
      }

      /**
       * @return ?ObjectStorage<Category>
       */
      public function getOtherCategories(): ?ObjectStorage
      {
         return $this->otherCategories;
      }

      /**
       * @param ObjectStorage<Category> $otherCategories
       */
      public function setOtherCategories(ObjectStorage $otherCategories): void
      {
         $this->otherCategories = $otherCategories;
      }
   }

.. important::

   The proxy class generator merges all :php:`__construct()` and :php:`initializeObject()` methods from
   extending classes into single combined methods, so your constructor code **will** run when creating
   new objects and your object initialization code when loading entities from the database.

3) Exclude the class from dependency injection
----------------------------------------------

As the class you define will be added to a new generated class, the class needs to be excluded from dependency injection in Configuration/Services.yaml:

.. code-block:: yaml

    services:
      _defaults:
        autowire: true
        autoconfigure: true
        public: false
    
      GeorgRinger\Eventnews\:
        resource: '../Classes/*'
        exclude: '../Classes/Domain/Model/*'

.. hint::

   If you are using the extension :file:`extension_builder`, this class might have been created for you already.

.. important::

   If you reference other objects, you must define the full namespace at the location and don't use namespace imports (with "use")!

Rebuild proxy classes
^^^^^^^^^^^^^^^^^^^^^
After changes to the extending class or its registration, rebuild the proxy classes:

.. code-block:: bash

   bin/typo3 news:rebuildProxyClasses

This regenerates the merged class files in :file:`var/cache/code/news/`. You should run this command
after every change to the news model or any extension that extends it.

Alternatively, clearing the :guilabel:`system cache` via the backend or the :guilabel:`Admin Tools` module
will also trigger a rebuild on the next request.

