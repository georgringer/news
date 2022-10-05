.. include:: /Includes.rst.txt

.. _installation:

Installation
============

The extension needs to be installed as any other extension of TYPO3 CMS. Get the
extension by one of the following methods:

#. **Use composer**: Run

   .. code-block:: bash

      composer require georgringer/news

   in your TYPO3 installation.

#. **Get it from the Extension Manager:** Switch to the module :guilabel:`Admin Tools > Extensions`.
   Switch to :guilabel:`Get Extensions` and search for the extension key
   *news* and import the extension from the repository.

#. **Get it from typo3.org:** You can always get current version from `TER`_
   by downloading the zip version. Upload the file afterwards in the Extension
   Manager.

and :ref:`configure <extensionConfiguration>` it.

.. _TER: https://extensions.typo3.org/extension/news/

Compatibility
-------------

Ensure the compatibility of the extension with your TYPO3 installation by
considering this compatibility matrix:

=========== =========== =========== ======================================
  News       TYPO3       PHP         Support / Development
=========== =========== =========== ======================================
  dev-main   10 - 11     7.4 - 8.1   unstable development branch
  9          10 - 11     7.4 - 8.1   features, bugfixes, security updates
  8          9.5 - 10    7.2 - 7.4   none
  7.x        8.7 - 9.x   7.0 - 7.2   none
  6.x        7.6 - 8.7   5.6 - 7.2   none
  5.x        7.6 - 8.7   5.6 - 7.2   none
  4.x        7.6         5.5 - 5.6   none
  3.x        6.2         5.5 - 5.6   security updates
=========== =========== =========== ======================================

Versioning
----------

This project uses `semantic versioning <https://semver.org/>`_, which means that

*  **bugfix updates** (e.g. 1.0.0 => 1.0.1) just include small bugfixes or
   security relevant stuff without breaking changes,
*  **minor updates** (e.g. 1.0.0 => 1.1.0) include new features and smaller
   tasks without breaking changes and
*  **major updates** (e.g. 1.0.0 => 2.0.0) contain breaking changes which can be
   refactorings, features or bugfixes.

as can be seen by reading the project's :ref:`change log <changelog>`.
