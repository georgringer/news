.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _extensionManager:

Extension Manager
-----------------

Some general settings can be configured in the Extension Manager.
If you need to configure those, switch to the module "Extension Manager", select the extension "**news**" and press on the configure-icon!

.. todo: screenshot

The settings are divided into several tabs and described here in detail:

Properties
^^^^^^^^^^

.. container:: ts-properties

	==================================== ===================================== ====================
	Property                             Tab                                   Default
	==================================== ===================================== ====================
	removeListActionFromFlexforms_        basic                                 List only
	archiveDate_                          basic                                 date
	pageModuleFieldsCategory_             basic                                 title,description
	pageModuleFieldsNews_                 basic
	showMediaDescriptionField_            records                               0
	rteForTeaser_                         records                               1
	tagPid_                               records                               1
	prependAtCopy_                        records                               1
	categoryRestriction_                  records
	categoryBeGroupTceFormsRestriction_   records
	contentElementRelation_               records                               0
	manualSorting_                        records                               0
	useFal_                               records                               fal
	showAdministrationModule_             backend modules                       0
	showImporter_                         backend modules                       0
	storageUidImporter_                   backend modules
	resourceFolderImporter_               backend modules                       /news_import
	==================================== ===================================== ====================

Property details
^^^^^^^^^^^^^^^^

.. only:: html

   .. contents::
        :local:
        :depth: 1

.. _extensionManager-removeListActionFromFlexforms:

removeListActionFromFlexforms
"""""""""""""""""""""""""""""
This switch enables you to configure the behaviour of the list view if the URL contains the link to a single view.
Basically there are 2 possible variants:

* **List only**: No matter if a single news is defined in the URL, the plugin will still render the list view. You will need a separate detail view somewhere.
* **List & Detail**: If a single news is defined in the URL, the single view will be shown.

.. warning::
	If you have created a plugin and change the setting, you need to reselect the new view in the plugin again!

.. _extensionManagerArchiveDate:

archiveDate
"""""""""""
Define if the archive date field should be rendered as a date field or including the date as well.

.. _extensionManagerPageModuleFieldsCategory:

pageModuleFieldsCategory
""""""""""""""""""""""""
Define the fields which should be shown for category records in the page module.
Empty the field if you don't want to show those records in the page module.

.. _extensionManagerPageModuleFieldsNews:

pageModuleFieldsNews
""""""""""""""""""""
Define the fields which should be shown for news records in the page module. A very simple API makes it possible to render a select box with multiple variants.

The syntax is: ::

	Label1=field1,field2,field3;Label2=field1,field2

.. _extensionManagerShowMediaDescriptionField:

showMediaDescriptionField
"""""""""""""""""""""""""
If set, a description field for media elements is shown.

.. note::
	This is just for non FAL relations!

.. _extensionManageRrteForTeaser:

rteForTeaser
""""""""""""
If set, the teaser field will be rendered using a RTE.

.. note::
	This is just for non FAL relations!

.. _extensionManagerTagPid:

tagPid
""""""
New tags can be saved directly inside the news record. The given ID is used as page on which the tag records will be saved.

If you want to use TsConfig to define the page, set the tagPid to 0 and use the following syntax in TsConfig: ::

	# Save tags on page with UID 123
	tx_news.tagPid = 123

.. _extensionManagerPrependAtCopy:

prependAtCopy
"""""""""""""
If set and a news record is copied, the news record will be prepended with the string **Copy X**.

.. _extensionManagerCategoryRestriction:

categoryRestriction
"""""""""""""""""""
Define an additional constraint for the categories inside a news record. Possible options are:

.. only:: html

   .. contents::
        :local:
        :depth: 1

None
~~~~
No additional constraint is used.

Page TsConfig
~~~~~~~~~~~~~
By using TsConfig it is possible to define those pages which contain the available categories: ::

	# Example: Only use categories which are saved in the pages with ID 12 and 34
	TCEFORM.tx_news_domain_model_news.categories.PAGE_TSCONFIG_IDLIST = 12,34

Categories from current page
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Only those categories are shown in a news record which are located at the **same** page.

Categories from page which is defined in page properties
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Only those categories are shown in a news record which are saved in the page defined in the page properties in the field **General Record Storage Page**.

Categories from site root
~~~~~~~~~~~~~~~~~~~~~~~~~
Only those categories are shown which are saved at the root page.


.. _extensionManagerCategoryBeGroupTceFormsRestriction:

categoryBeGroupTceFormsRestriction
""""""""""""""""""""""""""""""""""
If activated, an editor needs to have permissions to all categories added to a news item to be able to edit this record.

.. _extensionManagerContentElementRelation:

contentElementRelation
""""""""""""""""""""""
If set, you can add content elements as relation to a news record. This makes it easy to enrich the news article with further images, plugins, ...

If you want to reduce the available options of the content elements, you can use TsConfig in the sysfolder of the news records: ::

	# Hide content element types
	TCEFORM.tt_content.CType.removeItems = header,bullets,table,uploads,menu,list,html,login,mailform,search,shortcut,div
	# Hide fields
	TCEFORM.tt_content.header.disabled = 1
	TCEFORM.tt_content.header_layout.disabled = 1

More information can be found at http://docs.typo3.org/typo3cms/TSconfigReference/PageTsconfig/TCEform/Index.html.

.. _extensionManagerManualSorting:

manualSorting
"""""""""""""
If set, news records can be manually sorted in the list view by the well known icons "up" and "down".

.. _extensionManagerUseFal:

useFal
""""""
The following options are available:

- **Yes**: Enables usage of FAL relations
- **No**: Hides the relations to FAL elements
- **Both**: The old and the new media relations are used
- **Fal & Multimedia**: Enables usage of FAL relations + the old media relation, limited to type "multimedia"

.. _extensionManagerShowAdministrationModule:

showAdministrationModule
""""""""""""""""""""""""
If set, the backend module "News" is shown.This view might be easier for editors who use a very limited set of features in the backend.

.. _extensionManagerShowImporter:

showImporter
""""""""""""
If set, the backend module "News import" is shown. This is used to import news articles from sources like t3blog, tt_news or custom providers.

.. _extensionManagerStorageUidImporter:

storageUidImporter
""""""""""""""""""
Define the uid of the storage which is used for importing media elements into FAL relations.

.. _extensionManagerResourceFolderImporter:

resourceFolderImporter
""""""""""""""""""""""
Define the folder which is used for the media elements which are imported.
