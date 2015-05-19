ExcludeDisplayedNewsViewHelper
-----------------------------------

ViewHelper to exclude news items in other plugins

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
         \* newsItem
   :Type:
         Tx\_News\_Domain\_Model\_News
   :Description:
         current news item
   :Default value:
         



Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""



Code: ::

	 <n:excludeDisplayedNews newsItem="{newsItem}" />


Output: ::

	 None

