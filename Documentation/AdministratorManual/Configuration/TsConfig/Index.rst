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

	====================================    =====================================
	Property                                Data type
	====================================    =====================================
	templateLayouts_                        array
	archive_                                string
	tagPid_                                 integer
	categoryRestrictionForFlexForms_        bool
	showContentElementsInNewsSysFolder_     string
	====================================    =====================================

.. _tsconfigTemplateLayouts:

templateLayouts
^^^^^^^^^^^^^^^
The selectbox “Template Layout” inside a plugin can be easily be extended by using TsConfig

.. code-block:: typoscript

	tx_news.templateLayouts {
			1 = Fobar
			2 = Another one
			3 =  --div--,Group 2
			4 = Blub
	}

will show 2 layout options with 123/456 as keys and Fobar/Blub as values.
Inside the template it is then possible to define conditions with fluid by checking {settings.templateLayout}

By using the configuration `allowedColPos` it is possible to restrict a template layout to a specific list of colPos values.

.. code-block:: typoscript

   tx_news.templateLayouts {
      1 = Fobar
      2 = Another one
      2.allowedColPos = 1,2,3
      3 =  --div--,Group 2
      4 = Blub
      4.allowedColPos = 0,1
   }

.. _tsconfigArchive:

archive
^^^^^^^
Use strtotime (see `http://www.php.net/strtotime <http://www.php.net/strtotime>`_ ) to predefine the archive date

.. code-block:: typoscript

	# Example:
	tx_news.predefine.archive = next friday

will set the archive date on the the next friday.

.. _tsconfigTagPid:

tagPid
^^^^^^
Besides the configuration in the :ref:`Extension Manager <extensionManagerTagPid>` it is also possible to define the pid of tags created directly in the news record by Using TsConfig:

.. code-block:: typoscript

	# Example:
	tx_news.tagPid = 123


.. _tsconfigCategoryRestrictionForFlexForms:

categoryRestrictionForFlexForms
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
After defining the category restriction in the :ref:`Extension Manager <extensionManagerCategoryRestriction>` it is also possible to restrict the categories in the news plugin. This needs to enabled by TsConfig:

.. code-block:: typoscript

	# Example:
	tx_news.categoryRestrictionForFlexForms = 1


.. _tsconfigShowContentElementsInNewsSysFolder:

showContentElementsInNewsSysFolder
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If a sys folder is configured with **Contains PLugin:** `News`, content elements are hidden on those pages in the page and list module. If the content elements should be shown, use the PageTsConfig

.. code-block:: typoscript

	# Example:
	tx_news.showContentElementsInNewsSysFolder = 1


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
	filters_                     array
	localizationView_            bool
	controlPanels_               bool
	allowedCategoryRootIds_      string
	=========================== =====================================

.. _tsconfigPreselect:

preselect
^^^^^^^^^
Predefine the form in the administration module. The possible fields for the preselection are:

- recursive
- timeRestriction
- topNewsRestriction
- sortingField
- sortingDirection
- categoryConjunction

.. code-block:: typoscript

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
If this is not desired, the pid can be defined by using defaultPid.<tablename>:

.. code-block:: typoscript

	# Example
	tx_news.module.defaultPid.tx_news_domain_model_news = 123

News records will be saved on page with ID 123.

localizationView
^^^^^^^^^^^^^^^^

Ability to disable the localizationView in the administration module. Default is 1. Example:

.. code-block:: typoscript

    tx_news.module.localizationView = 0

controlPanels
^^^^^^^^^^^^^

Ability to control panel to sort, hide and delete in the administration module. Default is 0. Example:

.. code-block:: typoscript

    tx_news.module.controlPanels = 1

allowedCategoryRootIds
^^^^^^^^^^^^^^^^^^^^^^

Reduce the shown categories by defining a list of **root* category IDs.

Example:

.. code-block:: typoscript

    # Example category tree (value in brackets is the uid)
    [10] Cat 1
    [12] Cat 2
        [13] Cat 2 b
    [14] Cat 3
    [15] Cat 4

    tx_news.module.allowedCategoryRootIds = 12,15

    # Category tree shown
    [12] Cat 2
        [13] Cat 2 b
    [15] Cat 4

.. _tsconfigRedirectToPageOnStart:

redirectToPageOnStart
^^^^^^^^^^^^^^^^^^^^^
If no page is selected, the user will be redirected to the given page.

.. code-block:: typoscript

	# Example:
	tx_news.module.redirectToPageOnStart = 456

The user will be redirected to the page with the uid 456.

.. _tsconfigAllowedPage:

allowedPage
^^^^^^^^^^^
If defined, the administration module will redirect the user always to the given page, no matter what defined in the page tree.

.. code-block:: typoscript

	# Example:
	tx_news.module.allowedPage = 123

The user will be redirected to the page with the uid 123.

.. _tsconfigAlwaysShowFilter_:

alwaysShowFilter
^^^^^^^^^^^^^^^^
If defined, the administration module will always show the filter opened.

.. code-block:: typoscript

	# Example:
	tx_news.module.alwaysShowFilter = 1

The user will be redirected to the page with the uid 123.

filters
^^^^^^^
Define whether filters should be available or not. By default, all the filters are enabled. The available filters are:

- searchWord
- timeRestriction
- topNewsRestriction
- hidden
- archived
- sortingField
- number
- categories
- categoryConjunction
- includeSubCategories

.. code-block:: typoscript

	# Example:
	tx_news.module {
		filters {
			topNewsRestriction = 0
		}
	}


.. note::
 ``categoryConjunction`` and ``includeSubCategories`` can only be enabled when ``categories`` is enabled.

Additional Configuration
------------------------
This section covers settings which influence the News Plugin

switchableControllerAction
^^^^^^^^^^^^^^^^^^^^^^^^^^
To remove a specific action from the News Plugin selectbox, use this snippet.

.. code-block:: typoscript

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
