Format / FileDownloadViewHelper
------------------------------------

ViewHelper to render a download link of a file using $cObj->filelink()

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
         configuration
   :Type:
         array
   :Description:
         configuration used to render the filelink cObject
   :Default value:
         Array

 - :Name:
         \* file
   :Type:
         string
   :Description:
         Path to the file
   :Default value:
         

 - :Name:
         hideError
   :Type:
         boolean
   :Description:
         define if an error should be displayed if file not found
   :Default value:
         



Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""



Code: ::

	 <n:format.fileDownload file="uploads/tx_news/{relatedFile.file}" configuration="{settings.relatedFiles.download}">
	{relatedFile.title}
	 </n:format.fileDownload>


Output: ::

	  Link to download the file "uploads/tx_news/{relatedFile.file}"

