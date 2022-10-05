.. include:: /Includes.rst.txt

.. _newsAdminModule:

==========================
News administration module
==========================

The :guilabel:`News Administration` module is a simple backend module that
enables you to create, edit and search for news records. It can also be used
to create categories or tags.

Go module :guilabel:`Web > News Administration`. If it is not chosen yet,
choose the folder that your news records are stored in.

.. include:: /Images/AutomaticScreenshots/AdminModule.rst.txt

.. _newsModuleCreateNews:

Add a news record
=================

Click the button :guilabel:`Create new news record` on the top of the
:guilabel:`News Administration` module.

.. include:: /Images/AutomaticScreenshots/AddNewsInAdminModule.rst.txt

Fill in all necessary fields. In a standard setup only the title is a
required field. Depending on your project there might be other required fields
defined (for example at least on category chosen, author filled out etc..).

All required fields are marked in red and with an exclamation mark as is
standard in TYPO3 backend forms.

When you are done, click the :guilabel:`Save` button.

If enabled by your integrator you are able to use the :guilabel:`View` button
to preview your results. The integrator can follow this tutorial to
:ref:`enable the view button <viewButton>`.

Depending on the settings of your project you might have to empty the cache
before the news will be displayed in the list views. Your integrator can
follow this tutorial, to :ref:`automate the cache clearing <cacheClearing>`.


Search for news record
=======================

Use the filter symbol on the top of the module to open the search. You can use
multiple fields to filter the news.


.. _newsModuleCreateCategory:

Create a category
=================

The category records are provided by the TYPO3 Core. They are however closely
integrated into EXT:news. You can create a category by clicking the button
:guilabel:`Create a new category` on the top of the
:guilabel:`News Administration` module.

.. include:: /Images/AutomaticScreenshots/AddCategoryInAdminModule.rst.txt

Fill in a title and possible a parent category and some other fields and
click the :guilabel:`Save` button. Once you have created categories you can
select them when editing news records.

You can also chose categories in the plugin to filter by categories. Or you can
display a category menu.

.. _newsModuleCreateTag:

Create a tag
============

.. include:: /Images/AutomaticScreenshots/AddTagInAdminModule.rst.txt

You can create a tag by clicking the button
:guilabel:`Create a new tag` on the top of the
:guilabel:`News Administration` module. Fill in a title and
click the :guilabel:`Save` button.

Once you created some tags you can add to your news records on editing them.

Support \& Donate
=================

You can find some information about how to support the main author of this
extension on this page. Click the :guilabel:`Support & Donate` button
on the top right of the module.
