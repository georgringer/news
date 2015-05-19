.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _recordCategory:

Category
--------
Categories are not mandatory but make it easier to structure news records.

.. hint::

   EXT:news uses the **System Categories** since version *3.0.0*.


Add a category
^^^^^^^^^^^^^^

|img-record-category|

#. Switch to any page or sysfolder in the backend of your TYPO3 installation
#. Click on the + icon.
#. Select "Category" which can be found in the section *System Records*.

Properties
^^^^^^^^^^

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:
   :Description:
         Description:

 - :Field:
         Title
   :Description:
         Title of the category. This field is required!

 - :Field:
         Parent category
   :Description:
         The parent category is used to build a category tree. Therefore
         select the parent of the current category. If nothing selected, the
         category is used as a root category.

 - :Field:
         Image
   :Description:
         Image of the category which can be shown next to a category
         title.

 - :Field:
         Description
   :Description:
         Description of the category


 - :Field:
         Single-view page for news from this category
   :Description:
         If a page is defined, it is used as page for displaying the news
         record. If a news record belongs to more than one category, only the 1
         :sup:`st` category is checked for this field.

 - :Field:
         News category shortcut
   :Description:
         Optional link of a news category

