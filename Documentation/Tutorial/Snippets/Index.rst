.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Simple snippets making EXT:news more awesome
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This section contains snippets which might be useful for your projects as well.

.. tip::

	If you miss a snippet, please open an issue at http://forge.typo3.org/projects/extension-news/issues and report it there, thanks!


Improved back links
"""""""""""""""""""""

The back link on a detail page is a fixed link to a given page. However it might be that you use multiple list views
and want to change the link depending on the given list view.

A nice solution would be to use this JavaScript jQuery snippet: ::

	if ($(".news-backlink-wrap a").length > 0) {
		if(document.referrer.indexOf(window.location.hostname) != -1) {
			$(".news-backlink-wrap a").attr("href","javascript:history.back();").text('Back');
		}
	}


This snippet has been provided by d.ros at http://forge.typo3.org/issues/51966. Thanks!

Float news items next to each other
"""""""""""""""""""""""""""""""""""""""

If you need to list news next to each other and need some additional CSS classes, you can use the modulo operator to achieve this. ::

	<f:for each="{paginatedNews}" as="newsItem" iteration="iterator">
		<f:if condition="{iterator.isFirst}">
			<div class="row-fluid">
		</f:if>

		<f:if condition="{iterator.cycle} % 3">
			<f:then>
				<div class="span4">
					<f:render partial="List/Item" arguments="{newsItem: newsItem, settings:settings, className:className, view:'list'}"/>
				</div>
			</f:then>

			<f:else>
				<div class="span4">
					<f:render partial="List/Item" arguments="{newsItem: newsItem, settings:settings, className:className, view:'list'}"/>
				</div>

				<f:if condition="{iterator.isLast}">
					<f:then></f:then>
					<f:else>
						</div><div class="row-fluid">
					</f:else>
				</f:if>
			</f:else>
		</f:if>

		<f:if condition="{iterator.isLast}">
			</div>
		</f:if>
	</f:for>


The provided example will wrap 3 items into a div with the class "row-fluid".


Render category rootline in the Detail view
"""""""""""""""""""""""""""""""""""""""""""""""""

If you want to show not only the title of a single category which is related to the news item but the complete category rootline use this snippets. ::


	<f:if condition="{category:newsItem.firstCategory}">
		<ul class="category-breadcrumb">
			<f:render section="categoryBreadcrumb" arguments="{category:newsItem.firstCategory}" />
		</ul>
	</f:if>

and ::

	<f:section name="categoryBreadcrumb">
		<f:if condition="{category}">
			<f:if condition="{category.parentCategory}">
				<f:render section="categoryBreadcrumb" arguments="{category:category.parentCategory}" />
			</f:if>
			<li>{category.title}</li>
		</f:if>
	</f:section>


Extension linkhandler sample configuration
"""""""""""""""""""""""""""""""""""""""""""

You could use ext:linkhandler to link to an single news record. Here is a simple example.

``TypoScript-Setup``::

	plugin.tx_linkhandler {
		news {
			forceLink = 0
			listTables = tx_news_domain_model_news
			typolink {
				parameter = {$pids.newsSinglePid}
				additionalParams = &amp;tx_news_pi1[news]={field:uid}
				additionalParams.insertData = 1
				useCacheHash = 1
			}
		}
	}

The related``TSConfig``::

	mod.tx_linkhandler {
		news {
			label=News
			listTables=tx_news_domain_model_news
			previewPageId = 1
		}
	}
	RTE.default
		tx_linkhandler {
			news {
				label=News
				listTables=tx_news_domain_model_news
			}
		}
	}
	RTE.default.FE.tx_linkhandler < RTE.default.tx_linkhandler
