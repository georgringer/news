.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

Examples
========

Headline 2
----------

Headline 3
^^^^^^^^^^

Headline 4
""""""""""

Headline 5
~~~~~~~~~~

http://wiki.typo3.org/ReST_Syntax

http://docs.typo3.org/typo3cms/drafts/github/marble/DocumentationStarter/Chapter1/SubChapter/TopicC.html

.. note:: This is a note admonition.

	These notes are similar to tips, but usually contain information you should pay attention to. It might be details about a step that a whole operation hinges on or it may highlight an essential sequence of tasks.

	- The note contains all indented body elements following.
	- It includes this bullet list.

.. tip::

   Take a break from time to time!

.. important::

   Remember to always say "please" when asking your software to do something.

.. attention::

   Some directives of the Docutils have different
   names in Sphinx or work differently.

.. warning::

	These notes draw your attention to things that can interrupt your service or website if not done correctly. Some actions can be difficult to undo.


Properties
^^^^^^^^^^

.. container:: ts-properties

	=========================== ===================================== ======================= ====================
	Property                    Data type                             :ref:`t3tsref:stdwrap`  Default
	=========================== ===================================== ======================= ====================
	fobar_                      :ref:`t3tsref:data-type-wrap`         yes                     :code:`<div>|</div>`
	`subst\_elementUid`_        :ref:`t3tsref:data-type-boolean`      no                      0
	=========================== ===================================== ======================= ====================

.. _fobar:

Property details
^^^^^^^^^^^^^^^^

.. only:: html

	.. contents::
		:local:
		:depth: 1


.. _subst_elementUid`:

:typoscript:`plugin.tx_extensionkey.wrapItemAndSub =` :ref:`t3tsref:data-type-wrap`

Wraps the whole item and any submenu concatenated to it.


.. _ts-plugin-tx-extensionkey-substelementUid:

subst_elementUid
""""""""""""""""

:typoscript:`plugin.tx_extensionkey.subst_elementUid =` :ref:`t3tsref:data-type-boolean`

text text text text text text text text text text text text text text text text text text
text text text text text text text text text text text text text text text text text text


API
---

How to use the API...

.. code-block:: php

	$stuff = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
		'\\Foo\\Bar\\Utility\\Stuff'
	);
	$stuff->do();

or some other language:

.. code-block:: javascript
   :linenos:
   :emphasize-lines: 2-4

	$(document).ready(
		function () {
			doStuff();
		}
	);


Tables
------

+------------+------------+-----------+
| Header 1   | Header 2   | Header 3  |
+============+============+===========+
| body row 1 | column 2   | column 3  |
+------------+------------+-----------+
| body row 3 | Cells may  | - Cells   |
+------------+ span rows. | - contain |
| body row 4 |            | - blocks. |
+------------+------------+-----------+
