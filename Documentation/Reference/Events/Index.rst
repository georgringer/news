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

Controller
----------

* :php-short:`\GeorgRinger\News\Event\NewsDetailActionEvent`
* :php-short:`\GeorgRinger\News\Event\NewsListActionEvent`
* :php-short:`\GeorgRinger\News\Event\NewsListPostPaginationEvent`
* :php-short:`\GeorgRinger\News\Event\NewsListSelectedActionEvent`
* :php-short:`\GeorgRinger\News\Event\NewsSearchFormActionEvent`
* :php-short:`\GeorgRinger\News\Event\NewsSearchResultActionEvent`
* :php-short:`\GeorgRinger\News\Event\NewsDateMenuActionEvent`
* :php-short:`\GeorgRinger\News\Event\CategoryListActionEvent`
* :php-short:`\GeorgRinger\News\Event\TagListActionEvent`
* :php-short:`\GeorgRinger\News\Event\CreateDemandObjectFromSettingsEvent`
* :php-short:`\GeorgRinger\News\Event\NewsControllerOverrideSettingsEvent`
* :php-short:`\GeorgRinger\News\Event\NewsCheckPidOfNewsRecordFailedInDetailActionEvent`
* :php-short:`\GeorgRinger\News\Event\ModifyCacheTagsFromDemandEvent`
* :php-short:`\GeorgRinger\News\Event\ModifyCacheTagsFromNewsEvent`

Repository
----------

* :php-short:`\GeorgRinger\News\Event\ModifyDemandRepositoryEvent`

Update wizard
-------------

* :php-short:`\GeorgRinger\News\Event\PluginUpdaterListTypeEvent`

Backend
-------

* :php-short:`\GeorgRinger\News\Event\PluginPreviewSummaryEvent`

Import
------

* :php-short:`\GeorgRinger\News\Event\NewsImportPreHydrateEvent`
* :php-short:`\GeorgRinger\News\Event\NewsImportPostHydrateEvent`
* :php-short:`\GeorgRinger\News\Event\NewsPostImportEvent`
* :php-short:`\GeorgRinger\News\Event\NewsPreImportEvent`
* :php-short:`\GeorgRinger\News\Event\CategoryImportPostHydrateEvent`
