.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Known problems
--------------

The following issues are known bugs. However those are either not fixable inside EXT:news or not too easy to solve!

Category images on root page
============================

Due to restrictions of the File Abstraction Layer (FAL), it is not possible to add images to categories which are
saved on the root page (uid 0). Documented at http://forge.typo3.org/issues/57515


Multilanguage
=============

.. tip::

	This has been resolved in TYPO3 CMS 6.2.3 & 6.3!

The strategy of extbase is currently quite simple. Imagine the default language "English" and a 2nd language "German".
If the german news records should be rendered, the following (pseudo query) is used: ::

	SELECT *
	FROM tx_news_domain_model_news
	WHERE
		sys_language IN (german,-1) // all records which are either in german only or in "all languages"
		OR sys_language = 0 AND not yet translated

see the class Typo3DbQueryParser->addSysLanguageStatement().

After that, an overlay is done to correct the records by calling PageRepository::getRecordOverlay, see the class Typo3DbBackend->doLanguageAndWorkspaceOverlay()

If you are using a language mode like strict, this is the time when the record is removed from the resultset because there might be now translation available.

Out of this reasons, the following things don't work out of the box:
* Sorting records in a translation by a translated field
* Using strict mode and a pagination

DBAL
====

Extbase does not fully support DBAL, therefore it might be that things fail!