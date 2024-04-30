.. _general_tsconfig_examples:

======================
TSconfig code snippets
======================

Page TSconfig from can be used to influence display and behaviour of fields and
forms in the backend. The following examples are for your convenience only, they
are possible with any record type in TYPO3. Read more about what you can do with
page TSconfig in the :doc:`TSconfig Reference <t3tsconfig:Index>`.

You want to add your example here? Hit the "Edit on Github" button in the top
right and make a pull request on Github.

Setting default values
======================

It is possible to set default values for each field in the backend:

.. code-block:: typoscript

   TCAdefaults.tx_news_domain_model_news {
       author = Jon Doe
       notes = Someone forgot to change the notes....
   }

Categories
==========

Set a default category
----------------------

Setting default values also works for categories:

.. code-block:: typoscript

   TCAdefaults.tx_news_domain_model_news {
       categories = 9
   }

Limit categories to just one or several roots
---------------------------------------------

In many installations categories are used for multiple purposes. If you want
to use certain category trees only in certain page trees or only for your news
records, you can set the following

.. versionadded:: TYPO3 11.4
   :typoscript:`treeConfig.startingPoints` was added with TYPO3 11.4

.. code-block:: typoscript

   TCEFORM.tx_news_domain_model_news.categories.config.treeConfig.startingPoints = 8,42

.. deprecated:: TYPO3 11.4
   Before TYPO3 11.4 it was only possible to set one value as category root:

.. code-block:: typoscript

   TCEFORM.tx_news_domain_model_news.categories.config.treeConfig.rootUid = 42

See also :ref:`TSconfig Reference, treeConfig <t3tsconfig:pageTsConfigTceFormConfigTreeConfig>`.


Reduce allowed content elements inside news records
---------------------------------------------

By default, a news entry in TYPO3 allows you to add any available content element inside a news record as additional news content. However, sometimes the website design only allows specific content types to be displayed in a news entry. To restrict the allowed content elements, you can use TsConfig.

To do this, add the following code to your TsConfig:

.. code-block:: typoscript

   TCEFORM.tt_content {
      CType {
         # Remove all CTypes from allowed additional news content field 'content_elements'
         removeItems = *

         # allow
         keepItems = mask_news_text, mask_news_video
      }
   }


By default, when you add a new content element inside a news record, TYPO3 sets the CType to the first found content element text. However, since we have restricted the allowed content types, this CType is not allowed here. To set the default CType to mask_news_text, which is one of the allowed content types, you can use the following code:

.. code-block:: typoscript

   TCAdefaults {
      tt_content {
         CType = mask_news_text
         colPos = 1
      }
   }
