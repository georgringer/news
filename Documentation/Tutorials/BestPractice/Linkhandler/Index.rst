.. include:: /Includes.rst.txt

.. _linkhandler:

===========
LinkHandler
===========

**LinkHandler** is the synonym for making it possible for editors to create links to custom records.
Until 8 LTS a 3rd party extension has been required but since then it is integrated into the Core. Read at https://docs.typo3.org/typo3cms/extensions/core/8.7/Changelog/8.6/Feature-79626-IntegrateRecordLinkHandler.html about the feature.

.. tip::
   This tutorial is also valid for creating links to any other record.


Configuration for the backend
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

PageTsConfig is used to configure the link browser in the backend.

.. code-block:: typoscript

   # tx_news is an identifier, don't change it after links have been created
   TCEMAIN.linkHandler.tx_news {
      handler = TYPO3\CMS\Recordlist\LinkHandler\RecordLinkHandler
      # A translatable label can be used with LLL:EXT:theme/locallang.xml:label
      label = News
      configuration {
         table = tx_news_domain_model_news
         # Default storage pid
         storagePid = 123
         # Hide the page tree by setting it to 1
         hidePageTree = 0
      }
      scanAfter = page
   }

Configuration for the frontend
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The links are now stored in the database with the syntax `<a href="t3://record?identifier=tx_news&amp;uid=456">A link</a>`.
By using TypoScript, the link is transformed into an actual link.


.. code-block:: typoscript

   config.recordLinks.tx_news {
      typolink {
         # Detail page
         parameter = 123
         additionalParams.data = field:uid
         additionalParams.wrap = &tx_news_pi1[controller]=News&tx_news_pi1[action]=detail&tx_news_pi1[news]=|
      }
   }


Dynamic target page from category field single_pid
""""""""""""""""""""""""""""""""""""""""""""""""""

if the detail page is provided by the category, the following code can be used to retrieve the target page:

.. code-block:: typoscript

    config.recordLinks.tx_news {
        typolink {
            # Detail page
            parameter.cObject = USER
            parameter.cObject {
                userFunc = GeorgRinger\News\Service\LinkHandlerTargetPageService->process
                news.data = field:uid
                # Page used if no detail page is set in the category
                fallback = 123
            }
            additionalParams.data = field:uid
            additionalParams.wrap = &tx_news_pi1[controller]=News&tx_news_pi1[action]=detail&tx_news_pi1[news]=|
        }
    }
