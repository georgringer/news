.. include:: /Includes.rst.txt

.. _extensionConfiguration:

=======================
Extension Configuration
=======================

Some general settings can be configured in the Extension Configuration.

#. Go to :guilabel:`Admin Tools > Settings > Extension Configuration`
#. Choose :guilabel:`news`

The settings are divided into several tabs and described here in detail:

.. only:: html

   .. contents:: Properties
        :local:
        :depth: 2

Records
=======

.. _extensionConfigurationArchiveDate:

Archive Date `archiveDate`
--------------------------

.. confval:: archiveDate

   :type: string (keyword)
   :Default: date

   Define how the archive date field should be rendered:

   `date`
      Render the field as pure date

   `datetime`
      Render it as date and time field

Enable a RTE for the teaser field `rteForTeaser`
------------------------------------------------

.. confval:: rteForTeaser

   :type: bool
   :Default: 0

   If set, the teaser field will be rendered using a RTE.

.. _extensionConfigurationTagPid:

Define pid of tag records `tagPid`
----------------------------------

.. confval:: tagPid

   :type: int
   :Default: 0


   New tags can be saved directly inside the news record. The given ID is used
   as page on which the tag records will be saved.

   This setting can also be done with TSconfig, see :ref:`tagPid <tsconfigTagPid>`

   If you want to use TsConfig to define the page, set the tagPid to 0 and use
   the following syntax in TsConfig: ::

      # Save tags on page with UID 123
      tx_news.tagPid = 123

.. _extensionConfigurationPrependAtCopy:

Prepend at copy `prependAtCopy`
-------------------------------

.. confval:: prependAtCopy

   :type: bool
   :Default: 1

   If set and a news record is copied, the news record will be prepended
   with the string **Copy X**.

.. _extensionConfigurationCategoryRestriction:

Category restriction `categoryRestriction`
------------------------------------------

Category restriction: Restrict the available categories in news records.

PageTsConfig::

   TCEFORM.tx_news_domain_model_news.categories.PAGE_TSCONFIG_IDLIST=120.

.. warning::

   This feature is currently under development and not being expected to work!


.. _extensionConfigurationCategoryBeGroupTceFormsRestriction:

Editor needs to have permissions to all selected categories to save a news item `categoryBeGroupTceFormsRestriction`
---------------------------------------------------------------------------------------------------------------------

.. confval:: categoryBeGroupTceFormsRestriction

   :type: bool
   :Default: 0

   If activated, an editor needs to have permissions to all categories
   added to a news item to be able to edit this record.

.. _extensionConfigurationContentElementRelation:

Use content element relation `contentElementRelation`
-----------------------------------------------------

.. confval:: contentElementRelation

   :type: bool
   :Default: 1

   If set, you can add content elements as relation to a news record.
   This makes it easy to enrich the news article with further images, plugins, ...

   If you want to reduce the available options of the content elements, you can
   use TsConfig in the sysfolder of the news records: ::

      # Hide content element types
      TCEFORM.tt_content.CType.removeItems = header,bullets,table,uploads,menu,list,html,login,mailform,search,shortcut,div
      # Hide fields
      TCEFORM.tt_content.header.disabled = 1
      TCEFORM.tt_content.header_layout.disabled = 1

   More information can be found at http://docs.typo3.org/typo3cms/TSconfigReference/PageTsconfig/TCEform/Index.html.


.. _extensionConfigurationContentElementPreview:

Improved preview of content element relations `contentElementPreview`
---------------------------------------------------------------------

.. confval:: contentElementPreview

   :type: bool
   :Default: 1

   If set, the preview in the backend is rendered as in the page module.

.. _extensionConfigurationManualSorting:

Enable manual sorting of news records `manualSorting`
-----------------------------------------------------

.. confval:: manualSorting

   :type: bool
   :Default: 0

   If set, news records can be manually sorted in the list view by the well
   known icons "up" and "down".

.. _extensionConfigurationDateTimeNotRequired:

Disable required date field `dateTimeNotRequired`
-------------------------------------------------

.. confval:: dateTimeNotRequired

   :type: bool
   :Default: 0

   If set, the date field of the news record is not a required field anymore.
   Furthermore if creating a new record, it is not filled anymore with the
   current date.

   Be aware that using this feature may lead to unexpected results if using
   e.g. the date menu if the field is not used anymore.

.. _extensionConfigurationAdvancedMediaPreview:

Advanced preview configuration for media files `advancedMediaPreview`
----------------------------------------------------------------------

.. confval:: advancedMediaPreview

   :type: bool
   :Default: 1

   If enabled, more options are available for editors defining where an media
   element should be displayed.


.. _extensionConfigurationSlugBehaviour:

Slug behaviour `slugBehaviour`
------------------------------

.. confval:: slugBehaviour

   :type: string, keyword
   :Default: unique

   Choose one of the following slug behaviours:

   uniqueInSite
      The same slug can be used for news in different sites. Use this
      setting *only* if no news records are shared between sites.

   unique
      The same news title in different sites will lead to different slug names.

Backend Module
==============

.. _extensionConfigurationShowAdministrationModule:

Show administration module `showAdministrationModule`
-----------------------------------------------------

.. confval:: showAdministrationModule

   :type: bool
   :Default: 1

   If set, the backend module "News" is shown. This view might be easier for
   editors who use a very limited set of features in the backend.

.. _extensionConfigurationShowImporter:

.. _extensionConfigurationHidePageTreeForAdministrationModule:

Hide page tree for Administration module `hidePageTreeForAdministrationModule`
-------------------------------------------------------------------------------

.. confval:: hidePageTreeForAdministrationModule

   :type: bool
   :Default: 0

   If set, the backend module "News" is shown without the page tree. In
   combination with the TsConfig :confval:`redirectToPageOnStart` you can
   achieve a very simple workflow for editors if those need only to create
   news records.

Import Module
=============

Show importer `showImporter`
----------------------------

.. confval:: showImporter

   :type: bool
   :Default: 0

   If set, the backend module "News import" is shown. This is used to
   import news articles from sources like t3blog, tt_news or custom providers.

.. _extensionConfigurationStorageUidImporter:

UID of storage to use when importing files/images `storageUidImporter`
----------------------------------------------------------------------

.. confval:: storageUidImporter

   :type: int
   :Default: 1

   Define the uid of the storage which is used for importing media elements
   into FAL relations.

.. _extensionConfigurationResourceFolderImporter:

In which folder should the importer save files/images (has to exist) `resourceFolderImporter`
---------------------------------------------------------------------------------------------

.. confval:: resourceFolderImporter

   :type: string
   :Default: /news_import

   Define the folder which is used for the media elements which are imported.

Alternative configuration instead of Admin Tools
================================================

Instead of defining the property in the Admin Tools it is also possible to define
the properties in the :file:`AdditionalConfiguration.php`. This is useful if
you want to include the setting in version control.

.. code-block:: php
   :caption: AdditionalConfiguration.php

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news'] = serialize([
       'archiveDate' => 'date',
       'rteForTeaser' => 0,
       'tagPid' => 1,
       'prependAtCopy' => 0,
       'categoryRestriction' => 'none',
       'categoryBeGroupTceFormsRestriction' => 0,
       'contentElementRelation' => 1,
       'manualSorting' => 0,
       'dateTimeNotRequired' => 0,
       'showAdministrationModule' => 1,
       'showImporter' => 0,
       'storageUidImporter' => '1',
       'resourceFolderImporter' => '/news_import',
   ]),
