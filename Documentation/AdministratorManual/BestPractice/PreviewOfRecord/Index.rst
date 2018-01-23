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
			# Available with latest 8.7+ only
			# see https://forge.typo3.org/issues/78336
			useCacheHash = 1
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

.. Hint::

	Watch out for the Breaking Change "#78002 - Enforce cHash argument for Extbase actions" (https://docs.typo3.org/typo3cms/extensions/core/8.7/Changelog/8.5/Breaking-78002-EnforceCHashArgumentForExtbaseActions.html)  if you're using TYPO3 >=8.5. You need to set ``plugin.tx_news.features.requireCHashArgumentForActionArguments  = 0`` if you want to use this feature. Otherwise you'll receive a "cHash empty" validation error and most likely see a 404, if you have ``[FE] [pageNotFoundOnCHashError]`` enabled.
