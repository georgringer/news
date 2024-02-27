.. _renderContentElements:

=======================
Render content elements
=======================

If news is configured to use relations to content elements, those are shown
by default in the detail view.

There are two options how to render those elements

.. _using_typoscript:

Using TypoScript
================

This is the default way in EXT:news. A basic TypoScript configuration is used to render those. This look like this:

.. code-block:: typoscript

   lib.tx_news.contentElementRendering = RECORDS
   lib.tx_news.contentElementRendering {
       tables = tt_content
       source.current = 1
       dontCheckPid = 1
   }

If you need to extend this, the best way is to introduce your own TypoScript which can be saved anywhere.
This needs then to be referenced in the template.

.. code-block:: html

   <f:if condition="{newsItem.contentElements}">
       <f:cObject typoscriptObjectPath="lib.yourownTypoScript">{newsItem.contentElements}</f:cObject>
   </f:if>

Using TypoScript with nested content from b13/container
=======================================================

If nested content should be rendered as news content elements with
`b13/container <https://extensions.typo3.org/extension/container>`_ the lib.tx_news.contentElementRendering
should be adapted.

With the default lib.tx_news.contentElementRendering using the RECORDS CObject (see :ref:`Using TypoScript <using_typoscript>`)
all content elements are fetched from the database and nested child content in containers is rendered twice.
One time as standalone element and a second time in the container column.

To fix this the following lib can be used to fetch the content which will exclude all child content
in containers:

.. code-block:: typoscript

   lib.tx_news.contentElementRendering = CONTENT
   lib.tx_news.contentElementRendering {
       table = tt_content
       select {
           pidInList = 0
           uidInList.current = 1
           where = {#tx_container_parent}<1
           orderBy = sorting
           languageField = sys_language_uid
       }
   }

Changing ``lib.tx_news.contentElementRendering = RECORDS`` to ``lib.tx_news.contentElementRendering = CONTENT`` can have some side effects for the sorting of translated content elements. You can also fix this by using Fluid only:

.. code-block:: html

   <f:if condition="{newsItem.contentElements}">
       <!-- content elements -->
       <f:cObject typoscriptObjectPath="lib.tx_news.contentElementRendering">{newsItem.nonNestedContentElementIdList}</f:cObject>
   </f:if>

Using Fluid
===========

You can also use Fluid render the content elements. As an example:

.. code-block:: html

   <f:if condition="{newsItem.contentElements}">
       <f:for each="{newsItem.contentElements}" as="element">
           <h3>{element.title}</h3>
           <f:if condition="{element.CType} == 'text'">
           {element.bodytext -> f:format.html()}
           </f:if>
       </f:for>
   </f:if>

