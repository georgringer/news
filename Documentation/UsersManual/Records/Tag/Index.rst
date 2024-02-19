.. _recordTag:

===
Tag
===

Tags are not mandatory but make it easier to structure news records.

.. include:: /Images/AutomaticScreenshots/AddTagInAdminModule.rst.txt

Add a tag
=========

Tags can be added in the :ref:`News administration <newsModuleCreateTag>`
or :ref:`List <listAddFirstRecord>` module.

Properties
==========

.. confval:: Title
   :Required: true

   Title of the tag.

.. confval:: Hidden

   Hide a tag.

.. confval:: SEO: <title>-Tag

   Can be used to set a special <title>-Tag for category pages.
   This must be enabled in the template:

   .. code-block:: html

      <f:if condition="{tags.0.title}">
         <n:titleTag>
            <f:format.htmlentitiesDecode>{tags.0.title}</f:format.htmlentitiesDecode>
         </n:titleTag>
      </f:if>


.. confval:: SEO: Meta-Description

   Can be used to set a special meta description for category pages.
   This must be enabled in the template:

   .. code-block:: html

      <f:if condition="{tags.0.description}">
         <n:metaTag name="description" content="{tags.0.description -> f:format.stripTags()}" />
      </f:if>


.. confval:: SEO: Headline

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


.. confval:: SEO: Text

   Can be used to add additional content text for category pages
   This must be enabled in the template:

   .. code-block:: html

      <f:if condition="{tags.0.text}">
         <f:format.html>{tags.0.text}</f:format.html>
      </f:if>
