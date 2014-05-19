Be / MultiEditLinkViewHelper
---------------------------------

ViewHelper to create javascript to edit fields of multiple records

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
         \* columns
   :Type:
         string
   :Description:
         column names
   :Default value:
         

 - :Name:
         \* items
   :Type:
         object
   :Description:
         news items
   :Default value:
         



Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""



Code: ::

	 <n:be.buttons.icon uri="#" onclick="{n:be.multiEditLink(items:news,columns:'title')}" icon="actions-document-open" />


Output: ::

	 Onclick event which can be used to create a link to edit all title fields of given news records

