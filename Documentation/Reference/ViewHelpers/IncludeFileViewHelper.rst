IncludeFileViewHelper
--------------------------

ViewHelper to include a css/js file

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
         compress
   :Type:
         boolean
   :Description:
         Define if file should be compressed
   :Default value:


 - :Name:
         \* path
   :Type:
         string
   :Description:
         Path to the CSS/JS file which should be included
   :Default value:




Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""



Code: ::

    <n:includeFile path="{settings.cssFile}" />


Output: ::

    This will include the file provided by {settings} in the header

