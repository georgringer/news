LinkViewHelper
-------------------

ViewHelper to render links from news records to detail view or page

**Type:** Tag Based


General properties
^^^^^^^^^^^^^^^^^^^^^^^

.. t3-field-list-table::
 :header-rows: 1

 - :Name: Name:
   :Type: Type:
   :Description: Description:
   :Default value: Default value:

 - :Name:
         accesskey
   :Type:
         string
   :Description:
         Keyboard shortcut to access this element
   :Default value:
         

 - :Name:
         additionalAttributes
   :Type:
         array
   :Description:
         Additional tag attributes. They will be added directly to the resulting HTML tag.
   :Default value:
         

 - :Name:
         class
   :Type:
         string
   :Description:
         CSS class(es) for this element
   :Default value:
         

 - :Name:
         configuration
   :Type:
         array
   :Description:
         optional typolink configuration
   :Default value:
         Array

 - :Name:
         dir
   :Type:
         string
   :Description:
         Text direction for this HTML element. Allowed strings\: "ltr" (left to right), "rtl" (right to left)
   :Default value:
         

 - :Name:
         id
   :Type:
         string
   :Description:
         Unique (in this file) identifier for this HTML element.
   :Default value:
         

 - :Name:
         lang
   :Type:
         string
   :Description:
         Language for this element. Use short names specified in RFC 1766
   :Default value:
         

 - :Name:
         \* newsItem
   :Type:
         Tx\_News\_Domain\_Model\_News
   :Description:
         current news object
   :Default value:
         

 - :Name:
         onclick
   :Type:
         string
   :Description:
         JavaScript evaluated for the onclick event
   :Default value:
         

 - :Name:
         settings
   :Type:
         array
   :Description:
         
   :Default value:
         Array

 - :Name:
         style
   :Type:
         string
   :Description:
         Individual CSS styles for this element
   :Default value:
         

 - :Name:
         tabindex
   :Type:
         integer
   :Description:
         Specifies the tab order of this element
   :Default value:
         

 - :Name:
         title
   :Type:
         string
   :Description:
         Tooltip text of element
   :Default value:
         

 - :Name:
         uriOnly
   :Type:
         boolean
   :Description:
         return only the url without the a-tag
   :Default value:
         



Examples
^^^^^^^^^^^^^

Basic link
"""""""""""""""



Code: ::

	 <n:link newsItem="{newsItem}" settings="{settings}">
	 	{newsItem.title}
	 </n:link>


Output: ::

	 A link to the given news record using the news title as link text



Set an additional attribute
""""""""""""""""""""""""""""""""

Available: class, dir, id, lang, style, title, accesskey, tabindex, onclick

Code: ::

	 <n:link newsItem="{newsItem}" settings="{settings}" class="a-link-class">fo</n:link>


Output: ::

	 <a href="link" class="a-link-class">fo</n:link>



Return the link only
"""""""""""""""""""""""""



Code: ::

	 <n:link newsItem="{newsItem}" settings="{settings}" uriOnly="1" />


Output: ::

	 The uri is returned
	 
	 
	 
Add additional parameters to the url
""""""""""""""""""""""""""""""""""""



Code: ::

	 <n:link newsItem="{newsItem}" settings="{settings}" configuration="{additionalParams:'&tx_news_pi1[category]=111'}">fo</n:link>


Output: ::

	 <a href="link&tx_news_pi1[category]=111">fo</n:link>


