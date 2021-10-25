.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt

.. _events:

Events
======
Several events can be used to modify the behaviour of EXT:news. 

.. only:: html

	.. contents::
		:local:
		:depth: 1

Connect to Event
----------------

To connect to an event, you need to register an event listener in your custom extension. All what it needs is an entry in your ``Configuration\Services.yaml`` file:

.. code-block:: yaml

	services:
	  Vendor\Extension\EventListener\YourListener:
	    tags:
	      - name: event.listener
		identifier: 'your-self-choosen-identifier'
		method: 'methodToConnectToEvent'
		event: GeorgRinger\News\Event\NewsListActionEvent

Write your EventListener
------------------------

An example event listener can look like this:

.. code-block:: php

	<?php

	declare(strict_types=1);

	namespace Vendor\Extension\EventListener;

	use GeorgRinger\News\Event\NewsListActionEvent;

	/**
	 * Use NewsListActionEvent from ext:news
	 */
	class YourListener
	{
	    /**
	     * Do what you want...
	     */
	    public function methodToConnectToEvent(NewsListActionEvent $event): void
	    {
		$values = $event->getAssignedValues();

		// Do some stuff

		$event->setAssignedValues($values);
	    }
	}

Available Events
----------------

When register to an event you can always access the class where the event is fired. For additional items see column "Access to" in the table below.

.. csv-table:: Events
   :header: "Event class", "Fired in class", "Access to", "Old Signal"

    "NewsCheckPidOfNewsRecordFailedInDetailActionEvent", "NewsController", "getNews()", "checkPidOfNewsRecordFailedInDetailAction"
    "NewsDateMenuActionEvent", "NewsController", "getAssignedValues()", "dateMenuAction (NewsController::SIGNAL_NEWS_DATEMENU_ACTION)"
    "NewsDetailActionEvent", "NewsController", "getAssignedValues()", "detailAction (NewsController::SIGNAL_NEWS_DETAIL_ACTION)"
    "NewsListActionEvent", "NewsController", "getAssignedValues()", "listAction (NewsController::SIGNAL_NEWS_LIST_ACTION)"
    "NewsListSelectedActionEvent", "NewsController", "getAssignedValues()", "selectedListAction (NewsController::SIGNAL_NEWS_LIST_SELECTED_ACTION)"
    "NewsSearchFormActionEvent", "NewsController", "getAssignedValues()", "searchFormAction (NewsController::SIGNAL_NEWS_SEARCHFORM_ACTION)"
    "NewsSearchResultActionEvent", "NewsController", "getAssignedValues()", "searchResultAction (NewsController::SIGNAL_NEWS_SEARCHRESULT_ACTION)"
    "AdministrationIndexActionEvent", "AdministrationController", "getAssignedValues()", "indexAction (AdministrationController::SIGNAL_ADMINISTRATION_INDEX_ACTION)"
    "AdministrationNewsPidListingActionEvent", "AdministrationController", "getRawTree();getTreeLevel()", "newsPidListingAction (AdministrationController::SIGNAL_ADMINISTRATION_NEWSPIDLISTING_ACTION)"
	"AdministrationExtendMenuEvent", "AdministrationController", "getMenu()", "createMenu"
    "CategoryListActionEvent", "CategoryController", "getAssignedValues()", "listAction (CategoryController::SIGNAL_CATEGORY_LIST_ACTION)"
    "TagListActionEvent", "TagController", "getAssignedValues()", "listAction (TagController::SIGNAL_TAG_LIST_ACTION)"
    "NewsImportPostHydrateEvent", "NewsImportService", "getImportItem();getNews()", "postHydrate"
    "NewsImportPreHydrateEvent", "NewsImportService", "getImportItem()", "preHydrate"
    "CategoryImportPostHydrateEvent", "CategoryImportService", "getImportItem();getCategory()", "postHydrate"
