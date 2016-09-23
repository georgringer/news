.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _recordTag:

Tag
---
Tags are not mandatory but make it easier to structure news records.

|img-record-tag|

Add a tag
^^^^^^^^^
#. Switch to any sysfolder in the backend of your TYPO3 installation
#. Click on the + icon.
#. Select "Tag" which can be found in the section *News system*.

.. tip:: A new tag can also be directly created within a news record. TODO link

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
             Title of the tag. This field is required!

     - :Field:
             Hidden
       :Description:
             Hide a tag.

     - :Field:
             SEO: <title>-Tag
       :Description:
             Can be used to set a special <title>-Tag for category pages.
             This must be enabled in the template:
             .. code-block:: html
         <f:if condition="{tags.0.title}">
           <n:titleTag>
             <f:format.htmlentitiesDecode>{tags.0.title}</f:format.htmlentitiesDecode>
           </n:titleTag>
         </f:if>

 - :Field:
         SEO: Meta-Description
   :Description:
             Can be used to set a special meta description for category pages.
             This must be enabled in the template:
             .. code-block:: html
         <f:if condition="{tags.0.description}">
           <n:metaTag name="description" content="{tags.0.description -> f:format.stripTags()}" />
         </f:if>

 - :Field:
         SEO: Headline
   :Description:
             Can be used to set a special headline for category pages, e.g. for H1 tag
             This must be enabled in the template:
             .. code-block:: html

         <f:if condition="{tags.0.headline}">
           <f:then>
             <h1>{tags.0.headline}</h1>
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

         <f:if condition="{tags.0.text}">
           <f:format.html>{tags.0.text}</f:format.html>
         </f:if>
