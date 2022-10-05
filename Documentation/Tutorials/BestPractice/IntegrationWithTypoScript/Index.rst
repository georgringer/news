.. include:: /Includes.rst.txt

.. highlight:: typoscript

.. _integrationTypoScript:

============================
Integrations with TypoScript
============================

This page gives you same examples which you can use for integrating EXT:news into a website.

.. only:: html

    .. contents::
        :local:
        :depth: 1


Add news by TypoScript
^^^^^^^^^^^^^^^^^^^^^^

If EXT:news should be integrated by using TypoScript only, you can use this code snippet:

.. code-block:: typoscript

   lib.news = USER
   lib.news {
     userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
     extensionName = News
     pluginName = Pi1
     vendorName = GeorgRinger

     switchableControllerActions {
      News {
        1 = list
      }
     }

     settings < plugin.tx_news.settings
     settings {
      //categories = 49
      limit = 30
      detailPid = 31
      overrideFlexformSettingsIfEmpty := addToList(detailPid)
      startingpoint = 13
     }
   }

Now you can use the object lib.news.

List and detail on the same page using TypoScript
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This is the example of how to display list and detail view on the same page.

.. code-block:: typoscript

   # Basic plugin settings
   lib.news = USER
   lib.news {
         userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
         pluginName = Pi1
         vendorName = GeorgRinger
         extensionName = News
         controller = News
         settings =< plugin.tx_news.settings
         persistence =< plugin.tx_news.persistence
         view =< plugin.tx_news.view
   }

Configure list and detail actions:

.. code-block:: typoscript

   lib.news_list < lib.news
   lib.news_list {
         action = list
         switchableControllerActions.News.1 = list
   }
   lib.news_detail < lib.news
   lib.news_detail {
         action = detail
         switchableControllerActions.News.1 = detail
   }

Insert configured objects to wherever you want to use them, depending on the GET parameter of detail view:

.. code-block:: typoscript

   [traverse(request.getQueryParams(), 'tx_news_pi1/news') > 0]
      page.10.marks.content < lib.news_detail
   [else]
      page.10.marks.content < lib.news_list
   [end]



Add news to breadcrumb menu
^^^^^^^^^^^^^^^^^^^^^^^^^^^

This example has been moved to the
:ref:`Tutorial: Breadcrumb menus <breadcrumbTypoScript>`.

Add HTML to the header part in the detail view.
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

There might be a use case where you need to add specific code to the header part when the detail view is rendered.
There are some possible ways to go.

Plain TypoScript
""""""""""""""""

You could use a code like the following one to render e.g. the title of a news article inside a title-tag.

.. code-block:: typoscript

    [globalVar = TSFE:id = NEWS-DETAIL-PAGE-ID]

    config.noPageTitle = 2

    temp.newsTitle = RECORDS
    temp.newsTitle {
      dontCheckPid = 1
            tables = tx_news_domain_model_news
            source.data = GP:tx_news_pi1|news
            source.intval = 1
            conf.tx_news_domain_model_news = TEXT
            conf.tx_news_domain_model_news {
                field = title
                htmlSpecialChars = 1
            }
            wrap = <title>|</title>
    }
    page.headerData.1 >
    page.headerData.1 < temp.newsTitle

    [global]

If you want to show the categories of a news record, you can use the following code:

.. code-block:: typoscript

    lib.categoryTitle = CONTENT
    lib.categoryTitle {
        if.isTrue.data = GP:tx_news_pi1|news
        table = tx_news_domain_model_news
        select {
            uidInList.data = GP:tx_news_pi1|news
            pidInList = 123
            join = sys_category_record_mm ON tx_news_domain_model_news.uid = sys_category_record_mm.uid_foreign JOIN sys_category ON sys_category.uid = sys_category_record_mm.uid_local
            orderBy = sys_category.sorting
            max = 1
        }
        renderObj = TEXT
        renderObj {
            field = title
            htmlSpecialChars = 1
        }
    }

Usage of a ViewHelper
"""""""""""""""""""""

Use a viewHelper of EXT:news to write any code into the header part. The code could look like this

.. code-block:: xml

    <n:headerData><script>var newsId = {newsItem.uid};</n:headerData>

If you want to set the title tag, you can use a specific viewHelper:

.. code-block:: xml

    <n:titleTag>{newsItem.title}</n:titleTag>


Fallback in Detail view if no news found
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If the detail view is called without a news uid given, an error is thrown (depending on the setting **settings.errorHandling**).
If the desired behaviour is to show a different news record this can be set in the plugin with the field "singleNews".

The drawback would be that the alternative news record would be always the same. If this should be kind of dynamic, take a
look at the given TypoScript snippet:

.. code-block:: typoscript

   plugin.tx_news.settings {
      overrideFlexformSettingsIfEmpty = singleNews,cropMaxCharacters,dateField,timeRestriction,orderBy,orderDirection,backPid,listPid,startingpoint
      useStdWrap = singleNews

      singleNews.stdWrap.cObject = CONTENT
      singleNews.stdWrap.cObject {
         table = tx_news_domain_model_news
         select {
            max = 1
            orderBy = datetime desc
            pidInList = 3
         }
         renderObj = TEXT
         renderObj.field = uid
      }
   }

By using the field *useStdWrap* it is possible to call the full range of stdWrap on any setting. In this case the first news record
from the page with uid 3 is used as fallback.


Show news items with same category in Detail.html
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you want to show in the detail action articles with the same category as the current one, you can use a snippet like this one:

Add this to the ``Detail.html`` which will pass the first category uid to the TypoScript object ``lib.tx_news.relatedByFirstCategory``.

.. code-block:: html

   <f:if condition="{newsItem.firstCategory}">
      <f:cObject typoscriptObjectPath="lib.tx_news.relatedByFirstCategory">{newsItem.firstCategory.uid}</f:cObject>
   </f:if>

.. code-block:: typoscript

    lib.tx_news.relatedByFirstCategory = USER
    lib.tx_news.relatedByFirstCategory {
        userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
        extensionName = News
        pluginName = Pi1
        vendorName = GeorgRinger

        switchableControllerActions {
            News {
                1 = list
            }
        }

        settings < plugin.tx_news.settings
        settings {
            relatedView = 1
            detailPid = 31
            useStdWrap := addToList(categories)
            categories.current = 1
            categoryConjunction = or
            overrideFlexformSettingsIfEmpty := addToList(detailPid)
            startingpoint = 78
        }
    }

Important is the line ``categories.current = 1`` which will set the category configuration.
Of course you need to adopt the snippet to your own needs, like setting the ``detailPid``, ``startingPoint``, ...

By defining a custom property like ``relatedView = 1`` you can differ in the ``List.html`` if it is called by this snippet or by a regular plugin.

Show news items with same tags in Detail.html
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Similar to the example above it is also possible to render news records with the same tags as the current one.

.. code-block:: typoscript

   lib.tx_news.relatedByTags = USER
   lib.tx_news.relatedByTags {
       userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
       extensionName = News
       pluginName = Pi1
       vendorName = GeorgRinger
       switchableControllerActions {
           News {
               1 = list
           }
       }

       settings < plugin.tx_news.settings
       settings {
           # custom tag to use in List.html
           relatedView = 1
           # limit number of news
           limit = 6
   #        startingpoint = 1

           useStdWrap := addToList(tags)
           tags.current = 1
           categoryConjunction = or
           overrideFlexformSettingsIfEmpty := addToList(detailPid)
           excludeAlreadyDisplayedNews = 1
       }
   }

In your overridden Detail.html template place the following code after displaying detailed news.

.. code-block:: html

   <f:if condition="{newsItem.tags}">
        <f:cObject typoscriptObjectPath="lib.tx_news.relatedByTags">{newsItem.tags->v:iterator.extract(key:'uid')->v:iterator.implode(glue: ',')}</f:cObject>
   </f:if>

The Fluid tags supply comma-separated list of tags' UIDs to the Typoscript code.

Either write custom ViewHelper or install vhs extension and add its namespace :html:`xmlns:v="http://typo3.org/ns/FluidTYPO3/Vhs/ViewHelpers"` to the `Detail.html` template.

Show Category Menu with Typoscript
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: typoscript

   lib.categoryMenu = USER
   lib.categoryMenu {
      userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
      extensionName = News
      pluginName = Pi1
      vendorName = GeorgRinger

      action = category
      switchableControllerActions {
         Category {
            1 = list
         }
      }

      settings < plugin.tx_news.settings
      settings {
         listPid = 11
      }
   }
