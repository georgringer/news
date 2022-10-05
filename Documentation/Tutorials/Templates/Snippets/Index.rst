.. include:: /Includes.rst.txt

.. _templatesSnippets:

=================
Assorted snippets
=================

This section contains snippets making EXT:news more awesome which might be useful for your projects as well.

.. only:: html

   .. contents::
        :local:
        :depth: 1

Show FAL properties in fluid
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Every property of a file, e.g. the copyright (available via EXT:filemetadata) can be rendered in templates by using e.g. `{file.originalResource.properties.copyright}`.

A full example can look like this

.. code-block:: html

    <f:for each="{newsItem.mediaNonPreviews}" as="mediaElement">
        <div class="thumbnail">
            <f:media file="{mediaElement}" class="img-fluid" />
            <f:if condition="{mediaElement.originalResource.properties.copyright}">
                <small>{mediaElement.originalResource.properties.copyright}</small>
            </f:if>
        </div>
    </f:for>

Use `<f:debug>{mediaElement.originalResource.properties}</f:debug>` to get all available properties

Improved back links
^^^^^^^^^^^^^^^^^^^
The back link on a detail page is a fixed link to a given page. However it might be that you use multiple list views
and want to change the link depending on the given list view.

A nice solution would be to use this JavaScript jQuery snippet:

.. code-block:: javascript

   if ($(".news-backlink-wrap a").length > 0) {
      if(document.referrer.indexOf(window.location.hostname) != -1) {
         $(".news-backlink-wrap a").attr("href","javascript:history.back();");
      }
   }

Creating links with Fluid
^^^^^^^^^^^^^^^^^^^^^^^^^

Besides the ViewHelper :html:`<n:link />` you can also use the ViewHelpers of Fluid itself:

.. code-block:: html

   <f:link.page pageUid="13" additionalParams="{tx_news_pi1: {controller: 'News',action: 'detail', news:newsItem.uid}}">{newsItem.title}</f:link.page>
   <a href="{f:uri.page(pageUid:13,additionalParams:'{tx_news_pi1:{controller:\'News\',action:\'detail\',news:newsItem.uid}}')}">{newsItem.title}</a>

Set n:link target page in Fluid
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If the detail page should not be set in the plugin or by a category, it can also be set within the template:

.. code-block:: html

   <n:link
      newsItem="{newsItem}"
      configuration=“{parameter:settings.somePid}"
      settings="{settings}" title="{newsItem.title}"><f:translate key="more-link"/></n:link>

The setting `settings.somePid` can e.g. set with `plugin.tx_news.settings.somePid=123`.

Render category rootline
^^^^^^^^^^^^^^^^^^^^^^^^
If you want to show not only the title of a single category which is related to the news item but the complete category rootline use this snippets.

.. code-block:: html

   <f:if condition="{category:newsItem.firstCategory}">
      <ul class="category-breadcrumb">
         <f:render section="categoryBreadcrumb" arguments="{category:newsItem.firstCategory}" />
      </ul>
   </f:if>

and

.. code-block:: html

   <f:section name="categoryBreadcrumb">
      <f:if condition="{category}">
         <f:if condition="{category.parentCategory}">
            <f:render section="categoryBreadcrumb" arguments="{category:category.parentCategory}" />
         </f:if>
         <li>{category.title}</li>
      </f:if>
   </f:section>

Use current content element in the template
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If you ever need information from the content element itself, you can use :html:`{contentObjectData.header}`.

Use current page in the template
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If you ever need information from the current page, you can use :html:`{pageData.uid}`.

Sort tags
^^^^^^^^^
If you want to sort the tags of a news item, you can use a custom ViewHelper or :file:`EXT:vhs`:

.. code-block:: html

   <ul>
      <f:for each="{newsItem.tags->v:iterator.sort(order: 'ASC', sortBy: 'title')}" as="tag">
         <li>{tag.title}</li>
      </f:for>
   </ul>


Render news items in columns
----------------------------

If you need to list news next to each other and need some additional CSS
classes, you can the following snippet.
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

