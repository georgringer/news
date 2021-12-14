MetaTagViewHelper
----------------------

ViewHelper to render meta tags

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
         additionalAttributes
   :Type:
         array
   :Description:
         Additional tag attributes. They will be added directly to the resulting HTML tag.
   :Default value:


 - :Name:
         content
   :Type:
         string
   :Description:
         Content of meta tag
   :Default value:


 - :Name:
         forceAbsoluteUrl
   :Type:
         boolean
   :Description:
         If set, absolute url is forced
   :Default value:


 - :Name:
         property
   :Type:
         string
   :Description:
         Property of meta tag
   :Default value:


 - :Name:
         useCurrentDomain
   :Type:
         boolean
   :Description:
         If set, current domain is used
   :Default value:


 - :Name:
         name
   :Type:
         string
   :Description:
         If set, the meta tag is built by using the attribute name="" instead of property
   :Default value:




Examples
^^^^^^^^^^^^^

Basic Example: News title as og:title meta tag
"""""""""""""""""""""""""""""""""""""""""""""""""""



Code: ::

    <n:metaTag property="og:title" content="{newsItem.title}" />


Output: ::

    <meta property="og:title" content="TYPO3 is awesome" />



Force the attribute "name"
"""""""""""""""""""""""""""""""



Code: ::

    <n:metaTag name="keywords" content="{newsItem.keywords}" />


Output: ::

    <meta name="keywords" content="news 1, news 2" />

