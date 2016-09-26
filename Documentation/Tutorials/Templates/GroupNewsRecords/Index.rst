.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Group news records
------------------

.. tip::
	This is a feature delivered by fluid, so you can use it also in other extensions and projects.


The following example will group all given news records by the property "firstCategory".

.. code-block:: html

	<f:if condition="{news}">
		<f:then>
			<div style="border:1px solid red">
				<f:groupedFor each="{news}" as="groupedNews" groupBy="firstCategory" groupKey="cat">
					<div style="border:1px solid blue;padding:10px;margin:10px;">
						<h1>{cat.title}</h1>
						<f:for each="{groupedNews}" as="newsItem">
							<div style="border:1px solid pink;padding:5px;margin:5px;">
								{newsItem.title}
							</div>
						</f:for>
					</div>
				</f:groupedFor>
			</div>
		</f:then>
		<f:else>
			<div class="no-news-found">
				<f:translate key="list_nonewsfound"/>
			</div>
		</f:else>
	</f:if>


Keep an eye on performance!
~~~~~~~~~~~~~~~~~~~~~~~~~~~

To be able to group the records, fluid will load every record itself and groups those afterwards.
If you plan to group many records just for getting something like a count, maybe it is better to fire the query directly and don't use fluid for that.

However if the result is on a cacheable page, the issue is only relevant on the first hit.