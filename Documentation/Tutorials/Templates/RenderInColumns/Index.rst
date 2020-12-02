.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Render news items in columns
----------------------------

If you need to list news next to each other and need some additional CSS classes, you can the following snippet.
The provided example will wrap 3 items into a div with the class "row".

.. code-block:: html

   <f:for each="{news -> n:iterator.chunk(count: 3)}" as="col" iteration="cycle">
      <div class="row">
         <f:for each="{col}" as="newsItem">
            <div class="col-md-4">
               <f:render partial="List/Item" arguments="{newsItem: newsItem, settings:settings}"/>
            </div>
         </f:for>
      </div>
   </f:for>
