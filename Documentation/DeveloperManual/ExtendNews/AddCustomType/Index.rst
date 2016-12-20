.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _addCustomType:

Add a custom type
=================
Out of the box news comes with three types built in:

- "News": Default news record
- "Internal Page": The news record is linked to a regular page.
- "External Page": The news record is linked to an external URL.

To add your custom type, you have to follow the steps below.

.. note::
In this example the new type will be called 'myCustomNewsType' and is configured to only show the fields 'title' and 'bodytext'.

1) Typoscript
-------------

.. code-block:: typoscript

  config.tx_extbase.persistence.classes {
    GeorgRinger\News\Domain\Model\News {
      subclasses {
        3 = Vendor\ExtName\Domain\Model\MyCustomNewsType
      }
    }

    Vendor\ExtName\Domain\Model\MyCustomNewsType {
      mapping {
        recordType = 3
          tableName = tx_news_domain_model_news
        }
      }
    }
  }

2) TCA
------

In this example, the new type is configured to show the fields `bodytext` and `title`.
Therefore, create the file ``Configuration/TCA/Overrides/tx_news_domain_model_news.php``.

.. code-block:: php

  <?php
    if (!defined('TYPO3_MODE')) {
      die ('Access denied.');
    }

    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['3'] =
      ['myCustomNewsType', 3] ;

    $GLOBALS['TCA']['tx_news_domain_model_news']['types']['3'] = [
      'showitem' => 'title, bodytext'
    ];

3) Custom class
---------------

.. code-block:: php

	<?php

	namespace Vendor\ExtName\Domain\Model;

	class MyCustomNewsType extends \GeorgRinger\News\Domain\Model\News {

	}

.. hint:: This is a very basic example.
It would also be possible to use your custom news type to show custom fields.
How to add custom fields to news is documented :ref:`here <proxyClassGenerator>`.
