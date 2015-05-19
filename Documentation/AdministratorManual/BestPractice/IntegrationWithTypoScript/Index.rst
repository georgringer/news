.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Integrations with TypoScript
----------------------------

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

	[globalVar = GP:tx_news_pi1|news > 0]
		page.10.marks.content < lib.news_detail
	[else]
		page.10.marks.content < lib.news_list
	[end]



Add news to breadcrumb menu
^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you want to show the news title in the breadcrumb menu if the single view is currently selected, use a TypoScript like this:

.. code-block:: typoscript

    lib.navigation_breadcrumb = COA
    lib.navigation_breadcrumb {
        stdWrap.wrap = <ul class="breadcrumb">|</ul>

        10 = HMENU
        10 {
            special = rootline
            #special.range =  1

            1 = TMENU
            1 {
                noBlur = 1

                NO = 1
                NO {
                    wrapItemAndSub = <li>|</li>
                    ATagTitle.field = subtitle // title
                    stdWrap.htmlSpecialChars = 1
                }

                CUR <.NO
                CUR {
                    wrapItemAndSub = <li class="active">|</li>
                    doNotLinkIt = 1
                }
            }
        }

        # Add news title if on single view
        20 = RECORDS
        20 {
            stdWrap.if.isTrue.data = GP:tx_news_pi1|news
            dontCheckPid = 1
            tables = tx_news_domain_model_news
            source.data = GP:tx_news_pi1|news
            source.intval = 1
            conf.tx_news_domain_model_news = TEXT
            conf.tx_news_domain_model_news {
                field = title
                htmlSpecialChars = 1
            }
			stdWrap.wrap = <li>|</li>
			stdWrap.required = 1
        }
    }

The relevant part starts with *20 = RECORDS* as this cObject renders the title of the news article. **Important:** Never forget the *source.intval = 1* to avoid SQL injections and the *htmlSpecialChars = 1* to avoid Cross-Site Scripting!

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

Usage of a ViewHelper
"""""""""""""""""""""

Use a viewHelper of EXT:news to write any code into the header part. The code could look like this
    <n:headerData><script>var newsId = {newsItem.uid};</n:headerData>

If you want to set the title tag, you can use a specific viewHelper:

.. code-block:: html

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
				orderBy = datetime
				pidInList = 3
			}
			renderObj = TEXT
			renderObj.field = uid
		}
	}

By using the field *useStdWrap* it is possible to call the full range of stdWrap on any setting. In this case the first news record
from the page with uid 3 is used as fallback.

