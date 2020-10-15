.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

.. _installation:

Installation
============

.. important::

   Use the versions 3.x for TYPO3 CMS 6.2 LTS and 4.x for TYPO3 CMS 7 LTS.

The extension needs to be installed as any other extension of TYPO3 CMS:

#. Switch to the module “Extension Manager”.

#. Get the extension
   #. **Use composer**: Use `composer require georgringer/news`.

   #. **Get it from the Extension Manager:** Press the “Retrieve/Update”
      button and search for the extension key *news* and import the
      extension from the repository.

   #. **Get it from typo3.org:** You can always get current version from
      `https://extensions.typo3.org/extension/news/
      <https://extensions.typo3.org/extension/news/>`_ by
      downloading either the t3x or zip version. Upload
      the file afterwards in the Extension Manager.

#. The Extension Manager offers some basic configuration which is
   explained :ref:`here <extensionManager>`.

Latest version from git
-----------------------
You can get the latest version from git by using the git command:

.. code-block:: bash

   git clone https://github.com/georgringer/news.git

.. important::

   The master branch supports TYPO3 CMS 9 and 10 only.

Preparation: Include static TypoScript
--------------------------------------

The extension ships some TypoScript code which needs to be included.

#. Switch to the root page of your site.

#. Switch to the **Template module** and select *Info/Modify*.

#. Press the link **Edit the whole template record** and switch to the tab *Includes*.

#. Select **News (news)** at the field *Include static (from extensions):*

|img-plugin-ts|
