.. include:: /Includes.rst.txt


Known problems
--------------
The following issues are known problems. However those are either not fixable inside EXT:news or not too easy to solve!

Category images on root page
============================
Due to restrictions of the File Abstraction Layer (FAL), it is not possible to add images to categories which are
saved on the root page (uid 0). Documented at http://forge.typo3.org/issues/57515

Multilanguage
=============
The strategy of Extbase is currently quite simple. First records are fetched and afterwards an overlay is added to get the correct translated data. Problems can be:

- sorting by a translated field is not possible
- Translation of FAL records, see https://forge.typo3.org/issues/57272

DBAL
====
Extbase does not fully support DBAL, therefore it might be that things fail!

Versioning & Workspaces
=======================
It is not possible to create a version in the frontend by Extbase. As long as the versioning happens in the backend, everything should be fine.
