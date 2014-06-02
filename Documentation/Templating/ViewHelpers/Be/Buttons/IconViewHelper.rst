Be / Buttons / IconViewHelper
----------------------------------

Viewhelper which returns save button with icon

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
         icon
   :Type:
         string
   :Description:
         Icon to be used
   :Default value:
         closedok

 - :Name:
         onclick
   :Type:
         string
   :Description:
         onclick setting
   :Default value:


 - :Name:
         title
   :Type:
         string
   :Description:
         Title attribute of the resulting link
   :Default value:


 - :Name:
         uri
   :Type:
         string
   :Description:
         the target URI for the link
   :Default value:




Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""



Code: ::

	 <f:be.buttons.icon uri="{f:uri.action()}" />


Output: ::

	 An icon button as known from the TYPO3 backend, skinned and linked
	 with the default action of the current controller.



Basic example II
"""""""""""""""""""""



Code: ::

	 <n:be.buttons.icon uri="{f:uri.action(action:'index')}" icon="tcarecords-tx_news_domain_model_news-default"
	 title="{f:translate(key:'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:module.newsListing')}" />


Output: ::

	 A linked button with the icon of a news record which is linked to the index action

