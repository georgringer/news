.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Template selector
=================

This entry should help you to use different templates for different (list) views.

Using the following Page TsConfig the editor can select the layouts in the news plugin: ::

	tx_news.templateLayouts {
		1 = A custom layout
		99 = LLL:fileadmin/somelocallang/locallang.xlf:someTranslation
	}

You can use any number to identify your layout and any label to describe it.

Now it is possible to use a condition in the template to change the layouts, and e.g. load a different partial:

.. code-block:: html

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
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can define a custom TypoScript setting which you can check in the view later on.

The TypoScript could look like: ::

	plugin.tx_news {
		settings {
			isLatest = 1
		}
	}

And then you can use a condition like this:

.. code-block:: html

	<f:if condition="{settings.isLatest}">
		<f:then>
			do something if it is set
		</f:then>
		<f:else>
			do something if it is not set
		</f:else>
	</f:if>



