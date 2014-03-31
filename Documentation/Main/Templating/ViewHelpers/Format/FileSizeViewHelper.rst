Format / FileSizeViewHelper
--------------------------------

ViewHelper to render the filesize

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
         \* file
   :Type:
         string
   :Description:
         Path to the file
   :Default value:


 - :Name:
         format
   :Type:
         string
   :Description:
         Labels for bytes, kilo, mega and giga separated by vertical bar (\|) and possibly encapsulated in "". Eg\: " \| K\| M\| G" (which is the default value)
   :Default value:


 - :Name:
         hideError
   :Type:
         boolean
   :Description:
         Define if an error should be displayed if file not found
   :Default value:




Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""

If format is empty, the default from \TYPO3\CMS\Core\Utility\GeneralUtility:::formatSize() is taken.

Code: ::

	 <n:format.fileSize file="uploads/tx_news/{relatedFile.file}" format="' | K| M| G'" />


Output: ::

	  3 M

