.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Predefine fields of records
---------------------------
This section will show you how you can predefine values of fields in records in the TYPO3 backend.

Default values
^^^^^^^^^^^^^^

.. tip:: This is a feature of TYPO3 itself, so you can use this also for all other tables and fields.

If you want to use some default values, you can use this code inside the TsConfig:

.. code-block:: typoscript

	# Syntax is always TCAdefaults.<tablename>.<fieldname> = value
	TCAdefaults {
		tx_news_domain_model_news {
			author =  John Doe
			# Foreign record syntax <tablename>_<uid>
			categories = sys_category_2, sys_category_5
		}
	}


Select fields
^^^^^^^^^^^^^
If you want to preselect a value from a select field, you need to set the value of the option field as value.
In this example the language with the uid 3 will be preselected:

.. code-block:: typoscript

	TCAdefaults.tx_news_domain_model_news {
        	sys_language_uid = 3
	}

If you want to remove an option from a select field you can take a look at this example which removes the
option "Images" from the media selection dropdown:

.. code-block:: typoscript

	TCEFORM.tx_news_domain_model_media {
		type.removeItems = 0
	}


Author name & email
^^^^^^^^^^^^^^^^^^^

By using the following code in the PageTsConig, the fields `Author` and `Author Email` are prefilled with the name and email address of the current backend user

.. code-block:: typoscript

	tx_news.predefine.author  = 1

Archive date
^^^^^^^^^^^^

EXT:news allows you to use an improved syntax to predefine the archive date by using this PagesTsConfig:

.. code-block:: typoscript

	tx_news.predefine.archive = <value>

As value you can use anything which can be interpreted by the php function strtotime (http://de2.php.net/manual/en/function.strtotime.php).
For example:

- +1 day
- next Monday
- +1 week 2 days 4 hours

