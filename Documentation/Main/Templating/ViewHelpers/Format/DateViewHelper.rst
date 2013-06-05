Format / DateViewHelper
----------------------------

ViewHelper to format a date, using strftime

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
         currentDate
   :Type:
         bool
   :Description:
         if true, the current date is used
   :Default value:
         

 - :Name:
         date
   :Type:
         mixed
   :Description:
         DateTime object or a string that is accepted by DateTime constructor
   :Default value:
         

 - :Name:
         format
   :Type:
         string
   :Description:
         Format String which is taken to format the Date/Time
   :Default value:
         %Y-%m-%d

 - :Name:
         strftime
   :Type:
         bool
   :Description:
         if true, the strftime is used instead of date()
   :Default value:
         1

