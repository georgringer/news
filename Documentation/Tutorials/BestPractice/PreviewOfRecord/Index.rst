.. include:: /Includes.rst.txt

.. _viewButton:

===========
View button
===========

It is possible to activate the :guilabel:`View` Button for news records.

This button is displayed on the top of the edit record page:

.. figure:: /Images/ManualScreenshots/ViewButton.png
   :class: with-shadow

   The :guilabel:`View` button

Add the following page TSconfig to your root page. It is recommended to use
a site package extension, see
:doc:`Official Tutorial: Site Package <t3sitepackage:Index>` for this purpose.

.. code-block:: typoscript

   TCEMAIN.preview {
      tx_news_domain_model_news {
         previewPageId = 123
         useDefaultLanguageRecord = 0
         fieldToParameterMap {
            uid = tx_news_pi1[news_preview]
         }
         additionalGetParameters {
            tx_news_pi1.controller = News
            tx_news_pi1.action = detail
         }
      }
   }

By using the given example, a link will be generated which leads to the
page with the id `123`.

If a news plugin is placed on this page, the news article will be shown.

.. hint::
   This TSconfig configuration can be used to display any record with with any
   kind of parameters. Read more about it in
   :ref:`TSconfig Reference: TCEMAIN.preview <t3tsconfig:pagetcemain-preview>`.

