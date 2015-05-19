Be / ClickmenuViewHelper
-----------------------------

ViewHelper to create a clickmenu

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
         Name of the table
   :Default value:
         

 - :Name:
         \* uid
   :Type:
         integer
   :Description:
         uid of the record
   :Default value:
         



Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""



Code: ::

	 <n:be.clickmenu table="tx_news_domain_model_news" uid="{newsItem.uid}">
	<n:be.buttons.iconForRecord table="tx_news_domain_model_news" uid="{newsItem.uid}" title="" />
	 </n:be.clickmenu>


Output: ::

	 Linked icon (<n:be.button.iconForRecord /> with a click menu for the given record (table + uid)

