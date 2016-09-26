.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Render news items in columns
----------------------------

If you need to list news next to each other and need some additional CSS classes, you can use the modulo operator to achieve this.
The provided example will wrap 3 items into a div with the class "row".

.. code-block:: html

	<f:for each="{paginatedNews}" as="newsItem" iteration="iterator">
		<f:if condition="{iterator.isFirst}">
			<div class="row">
		</f:if>

		<f:if condition="{iterator.cycle} % 3">
			<f:then>
				<div class="col-md-4">
					<f:render partial="List/Item" arguments="{newsItem: newsItem, settings:settings, className:className, view:'list'}"/>
				</div>
			</f:then>

			<f:else>
				<div class="col-md-4">
					<f:render partial="List/Item" arguments="{newsItem: newsItem, settings:settings, className:className, view:'list'}"/>
				</div>

				<f:if condition="{iterator.isLast}">
					<f:then></f:then>
					<f:else>
						</div><div class="row">
					</f:else>
				</f:if>
			</f:else>
		</f:if>

		<f:if condition="{iterator.isLast}">
			</div>
		</f:if>
	</f:for>

By using the extension "vhs" you can achieve this in far less lines:

.. code-block:: html

	<f:for each="{foo -> v:iterator.chunk(count: 3)}" as="bar" iteration="cycle">
	    <li>
	        <f:for each="{bar}" as="user">
	            <f:render section="yourTarget" arguments="{_all}" />
	        </f:for>
	    </li>
	</f:for>
