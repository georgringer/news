Format / DateViewHelper
----------------------------

**Type:** Basic


General properties
^^^^^^^^^^^^^^^^^^^^^^^

.. t3-field-list-table::
 :header-rows: 1

	- :Name:
		Name:

	:Type:
		Type:

	:Default value:
		Default value:


	- :Name:
		currentDate
		if true, the current date is used
	:Type:
		bool
	:Default value:
		FALSE


	- :Name:
		date
		DateTime object or a string that is accepted by DateTime constructor
	:Type:
		mixed
	:Default value:
		NULL


	- :Name:
		format
		Format String which is taken to format the Date/Time
	:Type:
		string
	:Default value:
		"%Y-%m-%d" (8 chars)


	- :Name:
		strftime
		if true, the strftime is used instead of date()
	:Type:
		bool
	:Default value:
		TRUE

