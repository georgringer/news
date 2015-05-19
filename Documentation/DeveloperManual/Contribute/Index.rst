.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

.. _contribute:

Contribute
----------

Contributions are essential for the success of open-source projects but certainly not limited to contribute code!
Read below how you can simply improve the documentation of this extension!

.. only:: html

	.. contents::
		:local:
		:depth: 1

Contribute documentation
^^^^^^^^^^^^^^^^^^^^^^^^

This documentation is the first place where people expect to find the needed information.
Therefore it is improved all the time.

To make it easy for **everyone** to contribute to the documentation, it has been separated from the extension and
is maintained on github at https://github.com/georgringer/news-documentation!

.. important::
	After after every commit, the documentation is rendered on http://docs.typo3.org/typo3cms/extensions/news/. Select the version "latest" in the dropdown!

How to start
""""""""""""

Fork the mentioned repository or even use the powerful editor of github to create pull requests.

Those will be merged ASAP.

reST
""""

The documentations of TYPO3 are based on reST. The syntax might be a bit strange in the beginning

If you want to know more about reST, see http://wiki.typo3.org/Rest.

A nice online editor at http://rst.ninjs.org/ can help you as well to get started.


Contribute Code
^^^^^^^^^^^^^^^

If you would like to contribute code for bugfixes, features or documentation, please follow the next sections.

Get the latest version from git
"""""""""""""""""""""""""""""""

It is assumed you have set up SSH (username and port) and GIT (username and email) already. You may use this handy tool,
which provides the console commands to setup your repository: http://www.wwwision.de/githelper/#TYPO3CMS/Extensions/news.git


Create a change
"""""""""""""""

Whenever you want to change code, please create an issue at http://forge.typo3.org/projects/extension-news/issues before.
This is also the place to discuss how a change should be done if you are unsure about it.

Create a change in your local git repository and push it to gerrit. Please use the same commit message as rules as in TYPO3 CMS, e.g. ::

	[BUGFIX] Change this and that

	This change fixes this and that by ...

	Resolves: #<issue number of forge>


If everything is ok, the change will be merged.


Further reading
^^^^^^^^^^^^^^^

More information about working with git and gerrit for TYPO3 can be found in the TYPO3 wiki:
http://wiki.typo3.org/Git

... and in the forge wiki:
http://forge.typo3.org/projects/9/wiki/Working_with_Git_and_Gerrit

There's also a special chapter for contributors:
http://wiki.typo3.org/Contribution_Walkthrough_Tutorials

The project uses FLOW3 commit message rules:
http://flow3.typo3.org/documentation/coding-guidelines/?user_staticdocinclude_pi2[filepath]=flow3.codingguidelines&cHash=533b192f067e518dfb7d67008cb870db#id36288854
