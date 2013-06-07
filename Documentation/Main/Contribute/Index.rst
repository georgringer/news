.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Contribute to EXT:news
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you would like to contribute to this extension, it would be simply awesome!

Step by Step instructions
""""""""""""""""""""""""""""""

Get the latest version from git
*********************************

It is assumed you have set up ssh (username and port) and git (username and email) already. You can copy and paste the following commands directly: ::

	# Clone the news repository into the folder extension_builder.
	git clone git://git.typo3.org/TYPO3v4/Extensions/news.git
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