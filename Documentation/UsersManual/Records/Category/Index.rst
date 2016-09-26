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

     - :Field:
             SEO: <title>-Tag
       :Description:
             Can be used to set a special <title>-Tag for category pages.
             This must be enabled in the template:
             .. code-block:: html

         <f:if condition="{categories.0.title}">
           <n:titleTag>
             <f:format.htmlentitiesDecode>{categories.0.title}</f:format.htmlentitiesDecode>
           </n:titleTag>
         </f:if>

 - :Field:
         SEO: Meta-Description
   :Description:
             Can be used to set a special meta description for category pages.
             This must be enabled in the template:
             .. code-block:: html

         <f:if condition="{categories.0.description}">
           <n:metaTag name="description" content="{categories.0.description -> f:format.stripTags()}" />
         </f:if>

 - :Field:
         SEO: Headline
   :Description:
             Can be used to set a special headline for category pages, e.g. for H1 tag
             This must be enabled in the template:
             .. code-block:: html

         <f:if condition="{categories.0.headline}">
           <f:then>
             <h1>{categories.0.headline}</h1>
           </f:then>
           <f:else>
             <h1>News Category</h1>
           </f:else>
         </f:if>

 - :Field:
         SEO: Text
   :Description:
             Can be used to add additional content text for category pages
             This must be enabled in the template:
             .. code-block:: html

         <f:if condition="{categories.0.text}">
           <f:format.html>{categories.0.text}</f:format.html>
         </f:if>
