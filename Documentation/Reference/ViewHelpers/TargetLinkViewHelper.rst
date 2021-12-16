TargetLinkViewHelper
-------------------------

ViewHelper to get the target out of the typolink

**Type:** Basic


General properties
^^^^^^^^^^^^^^^^^^^^^^^

.. t3-field-list-table::
 :header-rows: 1

 - :Name: Name:
   :Type: Type:
   :Description: Description:
   :Default value: Default value:

 - :Name:
         \* link
   :Type:
         string
   :Description:

   :Default value:




Examples
^^^^^^^^^^^^^

Basic Example
""""""""""""""""""

{relatedLink.uri} is defined as "123 _blank"

Code: ::

    <f:link.page pageUid="{relatedLink.uri}" target="{n:targetLink(link:relatedLink.uri)}">Link</Link>


Output: ::

    A link to the page with uid 123 and target set to "_blank"

