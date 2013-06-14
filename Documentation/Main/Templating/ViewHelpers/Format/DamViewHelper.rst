Format / DamViewHelper
---------------------------

ViewHelper to get full dam record

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
         name of element which is used for the dam record
   :Default value:
         

 - :Name:
         \* uid
   :Type:
         integer
   :Description:
         uid of media element.
   :Default value:
         



Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""



Code: ::

	 <n:format.dam as="dam" uid="123">
	<f:image src="{dam.file_path}{dam.file_name}"
		title="{dam.title}"
		alt="{dam.alt_text}"
		maxWidth="200" />
	 </n:format.dam>


Output: ::

	 Will output the dam record with uid 123 by using the image ViewHelper
	 Be aware that the file could be anything, e.g. a doc file or video,
	 so also check {dam.file_mime_type}



Debug the whole record
"""""""""""""""""""""""""""



Code: ::

	 <f:debug>{dam}</f:debug>


Output: ::

	 Will output the whole record

