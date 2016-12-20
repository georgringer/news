.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Render content elements
-----------------------

If news is configured to use relations to content elements, those are shown by default in the detail view.

There are two options how to render those elements

Using TypoScript
^^^^^^^^^^^^^^^^

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

   <f:if condition="{newsItem.contentElements">
           <f:cObject typoscriptObjectPath="lib.yourownTypoScript">{newsItem.contentElements}</f:cObject>
   </f:if>


Using Fluid
^^^^^^^^^^^

You can also use fluid render the content elements. As an example:

.. code-block:: html

	<f:if condition="{newsItem.contentElements">
		<f:for each="{newsItem.contentElements}" as="element">
			<h3>{element.title}</h3>
			<f:if condition="{element.CType} == 'text'">
			{element.bodytext -> f:format.html()}
			</f:if>
		</f:for>
	</f:if>

