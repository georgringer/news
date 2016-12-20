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
	archiveDate_                          basic                                 date
	rteForTeaser_                         records                               1
	tagPid_                               records                               1
	prependAtCopy_                        records                               1
	categoryRestriction_                  records
	categoryBeGroupTceFormsRestriction_   records
	contentElementRelation_               records                               0
	manualSorting_                        records                               0
	dateTimeNotRequired_                  records                               fal
	showAdministrationModule_             backend modules                       0
	hidePageTreeForAdministrationModule_  backend modules                       0
	showImporter_                         import module                         0
	storageUidImporter_                   import module
	resourceFolderImporter_               import module                         /news_import
	==================================== ===================================== ====================

Property details
^^^^^^^^^^^^^^^^

.. only:: html

   .. contents::
        :local:
        :depth: 1

.. _extensionManagerArchiveDate:

archiveDate
"""""""""""
Define if the archive date field should be rendered as a date field or including the time as well.

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
Define an additional constraint for the categories inside a news record. To use this constraint for the news plugins as well, take a look at the  :ref:`TsConfig configuration <tsconfigCategoryRestrictionForFlexForms>`.

Possible options are:

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

dateTimeNotRequired
"""""""""""""""""""
If set, the date field of the news record is not a required field anymore. Furthermore if creating a new record, it is not filled anymore with the current date.

Be aware that using this feature may lead to unexpected results if using e.g. the date menu if the field is not used anymore.

.. _extensionManagerShowAdministrationModule:

showAdministrationModule
""""""""""""""""""""""""
If set, the backend module "News" is shown.This view might be easier for editors who use a very limited set of features in the backend.

.. _extensionManagerShowImporter:

.. _extensionManagerHidePageTreeForAdministrationModule:

hidePageTreeForAdministrationModule
"""""""""""""""""""""""""""""""""""

If set, the backend module "News" is shown without the page tree. In combination with the TsConfig `redirectToPageOnStart` you can achieve a very simple workflow for editors if those need only to create news records.

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
