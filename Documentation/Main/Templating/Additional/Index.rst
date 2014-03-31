.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Custom Templates by using the Layout selector
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
This entry should help you to use different templates for different (list) views.

Using the following Page TsConfig the editor can select the layouts in the news plugin: ::

	tx_news.templateLayouts {
		1 = A custom layout
		99 = LLL:fileadmin/somelocallang/locallang.xml:someTranslation
	}

You can use any number to identify your layout and any label to describe it.

Now it is possible to use a condition in the template to change the layouts, and e.g. load a different partial: ::

	<f:if condition="{news}">
		<f:then>
			<f:if condition="{settings.templateLayout} == 99">
				<f:then>
					<div class="news well news-special">
						<f:for each="{news}" as="newsItem">
							<f:render partial="List/Item-special" arguments="{newsItem: newsItem, settings:settings}"/>
						</f:for>
					</div>
				</f:then>
				<f:else>
					<div class="news news-list-view">
						<n:widget.paginate objects="{news}" as="paginatedNews" configuration="{settings.list.paginate}">
								<f:for each="{paginatedNews}" as="newsItem">
									<f:render partial="List/Item" arguments="{newsItem: newsItem, settings:settings}"/>
								</f:for>
						</n:widget.paginate>
					</div>
				</f:else>
			</f:if>
		</f:then>
		<f:else>
			<div class="no-news-found"><f:translate key="list_nonewsfound" /></div>
		</f:else>
	</f:if>

As you can see in this example a different partial is loaded if the layout 99 is used.


Custom Templates by using TypoScript
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can define a custom TypoScript setting which you can check in the view later on.

The TypoScript could look like: ::

	plugin.tx_news {
		settings {
			isLatest = 1
		}
	}

And then you can use a condition like this: ::

	<f:if condition="{settings.isLatest}">
		<f:then>
			do something if it is set
		</f:then>
		<f:else>
			do something if it is not set
		</f:else>
	<f/:if>



Use current content element in the Template
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you ever need information from the content element itself, you can use: ::

	{contentObjectData.header}


This will get you the header of the content element, but of course you can use any other field of tt_content too.

Cycle through 3 css classes
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Sometimes it is useful to iterate over news items and use different classes for every news item.
This examples shows how you can cycle through 3 css classes and repeat those. ::

	<n:widget.paginate objects="{news}" as="paginatedNews" configuration="{settings.list.paginate}">
		<f:for each="{paginatedNews}" as="newsItem" iteration="iterator">
			<f:cycle values="{0: 'left', 1: 'center', 2: 'right'}" as="cycle">
				<li class="{cycle}">
					<strong>position: {cycle} // newsUID: {newsItem.uid}</strong>
					<f:render partial="List/Item" arguments="{newsItem: newsItem, settings:settings, className:className, view:'list'}"/>
				</li>
			</f:cycle>
		</f:for>
	</n:widget.paginate>


FLUID Condition for categories
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you want to create a condition based on a specific category, you can use something like this: ::

	<f:for each="{newsItem.categories}" as="category">
		<f:if condition="{category.uid} == 1">
			do something if category is uid 1
		</f:if>
	</f:for>

It is of course also possible to write a custom ViewHelper.

Change template of pagination
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you want to customize the Paginate ViewHelper Template you can customize it by following this example: ::

	plugin.tx_news {
		view {
			widget.Tx_News_ViewHelpers_Widget_PaginateViewHelper.templateRootPath = fileadmin/pathtocustomnews/Templates/
		}
	}

The template needs to be then saved in this place: ::

	fileadmin/pathtocustomnews/Templates/ViewHelpers/Widget/Paginate/Index.html

**Attention:**: This works since TYPO3 4.6 (see http://forge.typo3.org/issues/10823)

