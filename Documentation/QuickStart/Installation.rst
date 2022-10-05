.. include:: /Includes.rst.txt

.. _quickInstallation:

==================
Quick installation
==================

In a :ref:`composer-based TYPO3 installation <t3start:install>` you can install
the extension EXT:news via composer:

.. code-block:: bash

   composer require georgringer/news

In TYPO3 installations above version 11.5 the extension will be automatically
installed. You do not have to activate it manually.

If you are using an older version of TYPO3 or have a legacy installation
without composer, have a look at the
:ref:`Extended installation <installation>` chapter.


Update the database scheme
--------------------------

Open your TYPO3 backend with :ref:`system maintainer <t3start:system-maintainer>`
permissions.

In the module menu to the left navigate to :guilabel:`Admin Tools > Maintanance`,
then click on :guilabel:`Analyze database` and create all.

.. include:: /Images/AutomaticScreenshots/AnalyzeDatabase.rst.txt

Clear all caches
----------------

In the same module :guilabel:`Admin Tools > Maintanance` you can also
conveniently clear all caches by clicking the button :guilabel:`Flush cache`.

.. include:: /Images/AutomaticScreenshots/FlushCache.rst.txt
