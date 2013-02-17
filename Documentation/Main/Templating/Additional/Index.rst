.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


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


