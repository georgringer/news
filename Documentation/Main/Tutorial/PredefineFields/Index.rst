.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Predefine fields of records
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This tutorial will show you how you can predefine values of fields in records in the TYPO3 backend.


Default values
"""""""""""""""""

.. tip::

	This is a feature from the TYPO3 core, so you can use this also for all other tables and fields.


If you want to use some default values, you can use this code inside the TsConfig: ::

	TCAdefaults {
		tx_news_domain_model_news {
			author =  John Doe
		}
	}

The syntax is always the same: TCAdefaults.<tablename>.<fieldname> = value.


Select fields
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you want to preselect a value from a select field, you need to set the value of the option field as value.

If you want to remove an option from a select field you can take a look at this example which removes the
option "Images" from the media selection dropdown: ::

	TCEFORM.tx_news_domain_model_media {
		type.removeItems = 0
	}


Archive date
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

EXT:news allows you to use an improved syntax to predefine the archive date by using this PagesTsConfig: ::

	tx_news.predefine.archive = <value>

As value you can use anything which can be interpreted by the php function strtotime (http://de2.php.net/manual/en/function.strtotime.php).
For example:

* +1 day
* next Monday
* +1 week 2 days 4 hours

