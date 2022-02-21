.. include:: /Includes.rst.txt

.. _predefineValues:

================
Predefine values
================

This section will show you how you can predefine values of fields in records in the TYPO3 backend using TSconfig.

Default values
^^^^^^^^^^^^^^

.. tip:: This is a feature of TYPO3 itself, so you can use this also for all other tables and fields.

If you want to use some default values, you can use this code inside the TSconfig (tab:Resources) of the folder containing the News records:

.. code-block:: typoscript

   # Syntax is always TCAdefaults.<tablename>.<fieldname> = value
   TCAdefaults {
      tx_news_domain_model_news {
         author =  John Doe
         categories = 9
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

EXT:news allows you to use an improved syntax to predefine the archive date by using this Page TSconfig:

.. code-block:: typoscript

   tx_news.predefine.archive = <value>

As value you can use anything which can be interpreted by the php function `strtotime <http://de2.php.net/manual/en/function.strtotime.php>`__.
For example:

- +1 day
- next Monday
- +1 week 2 days 4 hours

Add the characters counter below an input field
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If you want to give a hint to editors so they know how many chars they can use for the Teaser field, you simply add this TSconfig to the folder:

.. code-block:: typoscript

    TCEFORM.tx_news_domain_model_news.teaser.config.max = 200

Modify flexform values
^^^^^^^^^^^^^^^^^^^^^^
The flexform values can be modified by using TSconfig.

.. code-block:: typoscript

    TCEFORM {
        tt_content {
            pi_flexform {
                news_pi1 {
                    sDEF {
                      settings\.orderDirection.disabled = 1
                      settings\.orderBy.addItems {
                        teaser = teaser
                      }
                    }
                }
            }
        }
    }


.. attention::

   The dot inside the field name must be escaped!

To identify the key of the tab (e.g. `sDEF`) and the field name (e.g. `settings.orderBy`)
look either in the source code while checking the flexforms or look into the configuration itself
which can be found at `EXT:news/Configuration/FlexForms/flexform_news.xml`.
