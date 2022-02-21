.. include:: /Includes.rst.txt

.. _installation:

Installation
============

.. important::

   Use the versions 3.x for TYPO3 CMS 6.2 LTS and 4.x for TYPO3 CMS 7 LTS.

The extension needs to be installed as any other extension of TYPO3 CMS:

#. Get the extension by one of the following methods:

   #. **Use composer**: Use `composer require georgringer/news`.

   #. **Get it from the Extension Manager:** Switch to the module :guilabel:`Admin Tools > Extensions`.
      Switch to :guilabel:`Get Extensions`
      and search for the extension key *news* and import the
      extension from the repository.

   #. **Get it from typo3.org:** You can always get current version from
      `https://extensions.typo3.org/extension/news/
      <https://extensions.typo3.org/extension/news/>`__ by
      downloading either the t3x or zip version. Upload
      the file afterwards in the Extension Manager.

#. :ref:`Configure <extensionConfiguration>` the extension.

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

#. Switch to :guilabel:`Template > Info/Modify`.

#. Press the link :guilabel:`Edit the whole template record` and switch to the tab :guilabel:`Includes`.

#. Select :guilabel:`News (news)` at the field :guilabel:`Include static (from extensions):`

.. include:: /Images/AutomaticScreenshots/NewsIncludeTypoScript.rst.txt

.. important::

   Include the static template of `EXT:fluid_styled_content` or provide the following TypoScript yourself:

   .. code-block:: typoscript

        plugin.tx_news.settings.detail.media.image.lightbox {
            enabled = 0
            class = lightbox
            width = 800m
            height = 600m
        }
