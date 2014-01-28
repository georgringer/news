.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Contribute to EXT:news
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Contributions are essential for the success of open-source projects but certainly not limited to contribute code.
There are plenty of things you can do to make this extension even better:

* Share the great experience you got while using this extension
* Improve the documentation
* Help others who got questions in mailinglists, ...

Step by Step instructions
""""""""""""""""""""""""""""""

If you would like to contribute code for bugfixes, features or documentation, please follow the next sections.


Get the latest version from git
*********************************

It is assumed you have set up ssh (username and port) and git (username and email) already. You can copy and paste the following commands directly: ::

	# Clone the news repository into the folder extension_builder.
	git clone git://git.typo3.org/TYPO3CMS/Extensions/news.git
	cd news
	# Install the gerrit commit-msg hook for the distribution clone
	scp -p -P 29418 <USERNAME>@review.typo3.org:hooks/commit-msg .git/hooks/
	# Configure review.typo3.org as default push target for submitting code for review
	git config remote.origin.pushurl ssh://review.typo3.org:29418/TYPO3v4/Extensions/news.git
	git config remote.origin.push HEAD:refs/for/master


Create a change
******************

Whenever you want to change code, please create an issue at http://forge.typo3.org/projects/extension-news/issues before.
This is also the place to discuss how a change should be done if you are unsure about it.

Create a change in your local git repository and push it to gerrit. Please use the same commit message as rules as in TYPO3 CMS, e.g. ::

	[BUGFIX] Change this and that

	This change fixes this and that by ...

	Resolves: #<issue number of forge>


If everything is ok, the change will be merged.

Improve documentation
*************************

This documentation is the first place where people expect to find the needed information. Therefore it is improved all the time.

However, if you still miss things, please open an issue at Forge. As this documentation is based on reST it is very easy to improve it.
The documentation is located in the directory **Documentation/Main/** of the extension.

If you want to know more about reST, see http://wiki.typo3.org/Rest.


Further reading
""""""""""""""""""""""""""""""""""

More information about working with git and gerrit for TYPO3 can be found in the TYPO3 wiki:
http://wiki.typo3.org/Git

... and in the forge wiki:
http://forge.typo3.org/projects/9/wiki/Working_with_Git_and_Gerrit

There's also a special chapter for contributors:
http://wiki.typo3.org/Contribution_Walkthrough_Tutorials

The project uses FLOW3 commit message rules:
http://flow3.typo3.org/documentation/coding-guidelines/?user_staticdocinclude_pi2[filepath]=flow3.codingguidelines&cHash=533b192f067e518dfb7d67008cb870db#id36288854