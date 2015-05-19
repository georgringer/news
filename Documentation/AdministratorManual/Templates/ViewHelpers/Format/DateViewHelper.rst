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



Examples
^^^^^^^^^^^^^

Basic example using default strftime
"""""""""""""""""""""""""""""""""""""""""



Code: ::

	 <n:format.date>{newsItem.dateTime}</n:format.date>


Output: ::

	 2013-06-08



Basic example using default strftime and a format
""""""""""""""""""""""""""""""""""""""""""""""""""""""



Code: ::

	 <n:format.date format="%B">{newsItem.dateTime}</n:format.date>


Output: ::

	 June



Basic example using datetime
"""""""""""""""""""""""""""""""""



Code: ::

	 <n:format.date format="c" strftime="0">{newsItem.crdate}</n:format.date>


Output: ::

	 2004-02-12T15:19:21+00:00



Render current time
""""""""""""""""""""""""



Code: ::

	 <n:format.date format="c" strftime="0" currentDate="1">{newsItem.crdate}</n:format.date>


Output: ::

	 2013-06-12T15:19:21+00:00

