Be / Buttons / IconForRecordViewHelper
-------------------------------------------

ViewHelper to show sprite icon for a record

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
         \* table
   :Type:
         string
   :Description:
         table name
   :Default value:
         

 - :Name:
         \* title
   :Type:
         string
   :Description:
         title
   :Default value:
         

 - :Name:
         \* uid
   :Type:
         integer
   :Description:
         uid of record
   :Default value:
         



Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""



Code: ::

	 <n:be.buttons.iconForRecord table="tx_news_domain_model_news" uid="{newsItem.uid}" title="" />


Output: ::

	 Icon of the news record with the given uid

