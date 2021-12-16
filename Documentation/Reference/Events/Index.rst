.. include:: /Includes.rst.txt

.. _events:
.. _referenceEvents:

======
Events
======

Several events can be used to modify the behaviour of EXT:news. Check out the
:ref:`Events tutorial <eventsTutorial>` for examples on how to use them.


Available Events
================

When register to an event you can always access the class where the event is
fired. For additional items see column "Access to" in the table below.

.. todo: automatically document events

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
