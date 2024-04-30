.. _infiniteScroll:

=====================
Ajax based pagination
=====================

Using ajax for the pagination can be achieved by using little bit of JavaScript

.. hint::
   There are various discussions if endless scrolling and ajax based pagination
   is actually a good idea as there are also some disadvantages as well.

This tutorial uses the implementation of https://infinite-scroll.com/.
**Be aware** that this code might require to buy a `license <https://infinite-scroll.com/#license>`__!

Templating
^^^^^^^^^^

The following template can be used as a replacement of `Templates/News/List.html`

.. code-block:: html

   <html xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
         data-namespace-typo3-fluid="true">
   <f:layout name="General" />

   <f:section name="content">
       <!--TYPO3SEARCH_end-->
       <f:if condition="{news}">
           <f:then>
               <div class="news-list-view" id="news-container-{contentObjectData.uid}">
                   <f:if condition="{settings.hidePagination}">
                       <f:then>
                           <f:for each="{news}" as="newsItem" iteration="iterator">
                               <f:render partial="List/Item" arguments="{newsItem: newsItem,settings:settings,iterator:iterator}" />
                           </f:for>
                       </f:then>
                       <f:else>
                           <f:if condition="{settings.list.paginate.insertAbove}">
                               <f:render partial="List/Pagination" arguments="{pagination: pagination.pagination, paginator: pagination.paginator}" />
                           </f:if>
                           <div class="articles" data-infinite-scroll='{ "path": ".f3-widget-paginator .next a", "append": ".article", "hideNav": ".f3-widget-paginator", "button": ".view-more-button", "scrollThreshold": false}'>
                               <f:for each="{pagination.paginator.paginatedItems}" as="newsItem" iteration="iterator">
                                   <f:render partial="List/Item" arguments="{newsItem: newsItem,settings:settings,iterator:iterator}" />
                               </f:for>
                           </div>
                           <f:if condition="{settings.list.paginate.insertBelow}">
                               <f:render partial="List/Pagination" arguments="{pagination: pagination.pagination, paginator: pagination.paginator}" />
                           </f:if>
                       </f:else>
                   </f:if>
               </div>
               <button class="view-more-button">View more</button>
           </f:then>
           <f:else>
               <div class="no-news-found">
                   <f:translate key="list_nonewsfound" />
               </div>
           </f:else>
       </f:if>
       <!--TYPO3SEARCH_begin-->
   </f:section>
   </html>

Configuration
^^^^^^^^^^^^^
All options are described at https://infinite-scroll.com/.

After the inclusion of the library the configuration can either take place in HTML directly

.. code-block:: html

   <div class="articles" data-infinite-scroll='{ "path": ".f3-widget-paginator .next a", "append": ".article", "hideNav": ".f3-widget-paginator"}'>

or by using JavaScript

.. code-block:: javascript

   let elem = document.querySelector('.articles');
   let infScroll = new InfiniteScroll( elem, {
     // options
     path: '.f3-widget-paginator .next a',
     append: '.article',
     hideNav: '.f3-widget-paginator'
   });
