.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _tsconfig:

TsConfig
========
This section covers all configurations which can be set with TsConfig.
Every configuration starts with ``tx_news.``.

.. note::
 Just for clarification: TsConfig is in TYPO3 only used for configurations inside the backend!

General configuration
---------------------
The general configuration covers options available during the creation and editing of news records.

Properties
^^^^^^^^^^

.. container:: ts-properties

	==================================      =====================================
	Property                                Data type
	==================================      =====================================
	templateLayouts_                        array
	archive_                                string
	tagPid_                                 integer
	categoryRestrictionForFlexForms_        bool
	==================================      =====================================

.. _tsconfigTemplateLayouts:

templateLayouts
^^^^^^^^^^^^^^^
The selectbox “Template Layout” inside a plugin can be easily be extended by using TsConfig.::

	# Example:
	tx_news.templateLayouts {
			1 = Fobar
			2 = Another one
			3 =  --div--,Group 2
			4 = Blub
	}

will show 2 layout options with 123/456 as keys and Fobar/Blub as values.
Inside the template it is then possible to define conditions with fluid by checking {settings.templateLayout}

.. _tsconfigArchive:

archive
^^^^^^^
Use strtotime (see `http://www.php.net/strtotime <http://www.php.net/strtotime>`_ ) to predefine the archive date.::

	# Example:
	tx_news.predefine.archive = next friday

will set the archive date on the the next friday.

.. _tsconfigTagPid:

tagPid
^^^^^^
Besides the configuration in the :ref:`Extension Manager <extensionManagerTagPid>` it is also possible to define the pid of tags created directly in the news record by Using TsConfig: ::

	# Example:
	tx_news.tagPid = 123


.. _tsconfigCategoryRestrictionForFlexForms:

categoryRestrictionForFlexForms
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
After defining the category restriction in the :ref:`Extension Manager <extensionManagerCategoryRestriction>` it is also possible to restrict the categories in the news plugin. This needs to enabled by TsConfig: ::

	# Example:
	tx_news.categoryRestrictionForFlexForms = 1

.. _tsconfigAdministration:

Administration module
---------------------

Properties
^^^^^^^^^^
.. container:: ts-properties

	=========================== =====================================
	Property                    Data type
	=========================== =====================================
	preselect_                   array
	columns_                     string
	defaultPid_                  integer
	redirectToPageOnStart_       integer
	allowedPage_                 integer
	alwaysShowFilter_            bool
	=========================== =====================================

.. _tsconfigPreselect:

preselect
^^^^^^^^^
Predefine the form in the administration module. The possible fields for the preselection are:

- recursive
- timeRestriction
- topNewsRestriction
- limit
- offset
- sortingField
- sortingDirection
- categoryConjunction

::

	# Example:
	tx_news.module {
		preselect {
			topNewsRestriction = 1
		}
	}


.. _tsconfigColumns:

columns
^^^^^^^

Define a list of columns which are displayed in the administration module. Default is `teaser,istopnews,datetime,categories`. Example:

.. code-block:: typoscript

    tx_news.module.columns = datetime,archive,author

.. _tsconfigDefaultPid:

defaultPid
^^^^^^^^^^
If no page is selected in the page tree, any record created in the administration module would be saved on the root page.
If this is not desired, the pid can be defined by using defaultPid.<tablename>::

	# Example
	tx_news.module.defaultPid.tx_news_domain_model_news = 123

News records will be saved on page with ID 123.

.. _tsconfigRedirectToPageOnStart:

redirectToPageOnStart
^^^^^^^^^^^^^^^^^^^^^
If no page is selected, the user will be redirected to the given page. ::

	# Example:
	tx_news.module.redirectToPageOnStart = 456

The user will be redirected to the page with the uid 456.

.. _tsconfigAllowedPage:

allowedPage
^^^^^^^^^^^
If defined, the administration module will redirect the user always to the given page, no matter what defined in the page tree. ::

	# Example:
	tx_news.module.allowedPage = 123

The user will be redirected to the page with the uid 123.

.. _tsconfigAlwaysShowFilter_:

alwaysShowFilter
^^^^^^^^^^^^^^^^
If defined, the administration module will always show the filter opened.

	# Example:
	tx_news.module.alwaysShowFilter = 1

The user will be redirected to the page with the uid 123.

Additional Configuration
------------------------
This section covers settings which influence the News Plugin

switchableControllerAction
^^^^^^^^^^^^^^^^^^^^^^^^^^
To remove a specific action from the News Plugin selectbox, use this snippet. ::

	# Example:
	TCEFORM.tt_content.pi_flexform.news_pi1.sDEF.switchableControllerActions.removeItems = Tag->list

The possible values for the switchableControllerActions are:

- News->list
- News->detail
- News->dateMenu
- News->searchForm
- News->searchResult
- Category->list
- Tag->list