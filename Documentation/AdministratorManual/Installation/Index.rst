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

   #. **Get it from the Extension Manager:** Press the “Retrieve/Update”
      button and search for the extension key *news* and import the
      extension from the repository.

   #. **Get it from typo3.org:** You can always get current version from
      `http://typo3.org/extensions/repository/view/news/current/
      <http://typo3.org/extensions/repository/view/news/current/>`_ by
      downloading either the t3x or zip version. Upload
      the file afterwards in the Extension Manager.

   #. **Use composer**: Use `composer require georgringer/news`.

#. The Extension Manager offers some basic configuration which is
   explained :ref:`here <extensionManager>`.

Latest version from git
-----------------------
You can get the latest version from git by using the git command:

.. code-block:: bash

   git clone git://git.typo3.org/TYPO3CMS/Extensions/news.git

.. important::

   The master branch supports TYPO3 CMS 7 only. Use the branch ``6x`` if you are using TYPO3 CMS 6.2!

Preparation: Include static TypoScript
--------------------------------------

The extension ships some TypoScript code which needs to be included.

#. Switch to the root page of your site.

#. Switch to the **Template module** and select *Info/Modify*.

#. Press the link **Edit the whole template record** and switch to the tab *Includes*.

#. Select **News (news)** at the field *Include static (from extensions):*

|img-plugin-ts|