ObjectViewHelper
---------------------

ViewHelper to render extended objects

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
         \* as
   :Type:
         string
   :Description:
         output variable
   :Default value:
         

 - :Name:
         \* className
   :Type:
         string
   :Description:
         custom class which handles the new objects
   :Default value:
         

 - :Name:
         extendedTable
   :Type:
         string
   :Description:
         table which is extended
   :Default value:
         tx\_news\_domain\_model\_news

 - :Name:
         \* newsItem
   :Type:
         Tx\_News\_Domain\_Model\_News
   :Description:
         current newsitem
   :Default value:
         



Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""



Code: ::

	 <n:object newsItem="{newsItem}"
	 		as="out"
	 		className="Tx_Myext_Domain_Model_CustomModel" >
	 {out.fo}
	 </n:link>


Output: ::

	 Property "fo" from model Tx_Myext_Domain_Model_CustomModel
	 which extends the table tx_news_domain_model_news

	 !!Be aware that this needs a mapping in TS!!
	    config.tx_extbase.persistence.classes {
	        Tx_Myext_Domain_Model_CustomModel {
	             mapping {
	                tableName = tx_news_domain_model_news
	            }
	        }
	    }

