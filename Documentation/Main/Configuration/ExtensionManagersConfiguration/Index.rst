.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Extension Manager's configuration
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

news offers some basic configuration inside the Extension Manager.
Those are described in this chapter. To be able to set this
configuration, switch to the Extension Manager and search for the
extension news. Click on it to see the available settings.


Properties
^^^^^^^^^^

.. container:: ts-properties

	===================================================== ================================================ ===================
	Property                                              Data type                                        Default
	===================================================== ================================================ ===================
	removeListActionFromFlexforms_                          :ref:`t3tsref:data-type-string`                  List only
	archiveDate_                                            :ref:`t3tsref:data-type-string`                  date
	pageModuleFieldsCategory_                               :ref:`t3tsref:data-type-string`                  title,description
	pageModuleFieldsNews_                                   :ref:`t3tsref:data-type-string`
	showMediaDescriptionField_                              :ref:`t3tsref:data-type-boolean`                 TRUE
	rteForTeaser_                                           :ref:`t3tsref:data-type-boolean`                 FALSE
	tagPid_                                                 :ref:`t3tsref:data-type-integer`                 1
	prependAtCopy_                                          :ref:`t3tsref:data-type-boolean`                 TRUE
	categoryRestriction_                                    :ref:`t3tsref:data-type-string`
	contentElementRelation_                                 :ref:`t3tsref:data-type-boolean`                 FALSE
	manualSorting_                                          :ref:`t3tsref:data-type-boolean`                 FALSE
	useFal_                                                 :ref:`t3tsref:data-type-string`                  fal
	showAdministrationModule_                               :ref:`t3tsref:data-type-boolean`                 FALSE
	showImporter_                                           :ref:`t3tsref:data-type-boolean`                 FALSE
	storageUidImporter_                                     :ref:`t3tsref:data-type-integer`
	resourceFolderImporter_                                 :ref:`t3tsref:data-type-string`                  /news_import
	===================================================== ================================================ ===================


Property details
^^^^^^^^^^^^^^^^^^

.. _settings-removeListActionFromFlexforms:

removeListActionFromFlexforms_
""""""""""""""""""""""""""""""""

This switch enables you to configure the behaviour of the list view if the URL contains the link to a single view.
Basically there are 2 possible variants:

* **List only**: No matter if a single news is defined in the URL, the plugin will still render the list view. You will need a separate detail view somewhere.
* **List & Detail**: If a single news is defined in the URL, the single view will be shown.

.. warning::

If you have created a plugin and change the setting, you need to reselect the new view in the plugin again!


.. _settings-archiveDate::

archiveDate:
""""""""""""""""""""""""""""""""

Define if the archive date field should be rendered as a date field or including the date as well.



.. _settings-pageModuleFieldsCategory::

pageModuleFieldsCategory:
""""""""""""""""""""""""""""""""

Define the fields which should be shown for category records in the page module.
Empty the field if you don't want to show those records in the page module.


.. _settings-pageModuleFieldsNews::

pageModuleFieldsNews:
""""""""""""""""""""""""""""""""

Define the fields which should be shown for news records in the page module. A very simple API makes it possible to render a select box with multiple variants.

The syntax is: ::

	Label1=field1,field2,field3;Label2=field1,field2


.. _settings-showMediaDescriptionField::

showMediaDescriptionField:
""""""""""""""""""""""""""""""""

If set, a description field for media elements is shown.



.. _settings-rteForTeaser::

rteForTeaser:
""""""""""""""""""""""""""""""""

If set, the teaser field will be rendered using a RTE.



.. _settings-tagPid::

tagPid:
""""""""""""""""""""""""""""""""

New tags can be saved directly inside the news record. The given ID is used as page on which the tag records will be saved.

If you want to use TsConfig to define the page, set the tagPid to 0 and use the following syntax in TsConfig: ::

	# Save tags on page with UID 123
	tx_news.tagPid = 123



.. _settings-prependAtCopy::

prependAtCopy:
""""""""""""""""""""""""""""""""

If set and a news record is copied, the news record will be prepended with the string **Copy X**.



.. _settings-categoryRestriction::

categoryRestriction:
""""""""""""""""""""""""""""""""

TODO


.. _settings-contentElementRelation::

contentElementRelation:
""""""""""""""""""""""""""""""""

If set, you can add content elements as relation to a news record. This makes it easy to enrich the news article with further images, plugins, ...



.. _settings-manualSorting::

manualSorting:
""""""""""""""""""""""""""""""""

If set, news records can be manually sorted in the list view by the well known icons "up" and "down".



.. _settings-useFal::

useFal:
""""""""""""""""""""""""""""""""

The following options are available:
* **Yes**: Enables usage of FAL relations
* **No**: Hides the relations to FAL elements
* **Both**: The old and the new media relations are used
* **Fal & Multimedia**: Enables usage of FAL relations + the old media relation, limited to type "multimedia"



.. _settings-showAdministrationModule::

showAdministrationModule:
""""""""""""""""""""""""""""""""

If set, the backend module "News" is shown.This view might be easier for editors who use a very limited set of features in the backend.


.. _settings-showImporter::

showImporter:
""""""""""""""""""""""""""""""""

If set, the backend module "News import" is shown. This is used to import news articles from sources like t3blog, tt_news or custom providers.


.. _settings-storageUidImporter::

storageUidImporter:
""""""""""""""""""""""""""""""""

Define the uid of the storage which is used for importing media elements into FAL relations.


.. _settings-resourceFolderImporter::

resourceFolderImporter:
""""""""""""""""""""""""""""""""

Define the folder which is used for the media elements which are imported.
