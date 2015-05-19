.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

Simple snippets
---------------
This section contains snippets making EXT:news more awesome which might be useful for your projects as well.

.. only:: html

   .. contents::
        :local:
        :depth: 1

Improved back links
^^^^^^^^^^^^^^^^^^^
The back link on a detail page is a fixed link to a given page. However it might be that you use multiple list views
and want to change the link depending on the given list view.

A nice solution would be to use this JavaScript jQuery snippet:

.. code-block:: javascript

	if ($(".news-backlink-wrap a").length > 0) {
		if(document.referrer.indexOf(window.location.hostname) != -1) {
			$(".news-backlink-wrap a").attr("href","javascript:history.back();").text('Back');
		}
	}


Render category rootline
^^^^^^^^^^^^^^^^^^^^^^^^
If you want to show not only the title of a single category which is related to the news item but the complete category rootline use this snippets.

.. code-block:: xml

	<f:if condition="{category:newsItem.firstCategory}">
		<ul class="category-breadcrumb">
			<f:render section="categoryBreadcrumb" arguments="{category:newsItem.firstCategory}" />
		</ul>
	</f:if>

and

.. code-block:: xml

	<f:section name="categoryBreadcrumb">
		<f:if condition="{category}">
			<f:if condition="{category.parentCategory}">
				<f:render section="categoryBreadcrumb" arguments="{category:category.parentCategory}" />
			</f:if>
			<li>{category.title}</li>
		</f:if>
	</f:section>

Use current content element in the Template
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If you ever need information from the content element itself, you can use ``{contentObjectData.header}``.
