.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


"Save & Preview" for news records
---------------------------------

It is possible to activate the action "Save & Preview" for news records by using some lines of page TsConfig.

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

By using the given example, a link will be generated which leads to the page with the id ``123``.
If a news plugin is placed on this page, the news article will be shown.

.. Hint::

	This feature is part of TYPO3 CMS 7 LTS and can be used for any record of any extension.

.. Hint::

	If the setting ``[FE][disableNoCacheParameter]`` is enabled, this won't work as the cHash is not set in the URL.
