.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Integrations with TypoScript
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This section gives you same examples which you can use when integrating EXT:news into a website.

Add news by TypoScript
"""""""""""""""""""""""""""""

If EXT:news should be integrated by using TypoScript only, you can use this code snippet: ::

	lib.news < USER
	lib.news {
	  userFunc = tx_extbase_core_bootstrap->run
	  extensionName = News
	  pluginName = Pi1

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

Add news to breadcrumb menu
"""""""""""""""""""""""""""""

If you want to show the news title in the breadcrumb menu if the single view is currently selected, use a TypoScript like this: ::

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
            if.isTrue.data = GP:tx_news_pi1|news
            dontCheckPid = 1
            tables = tx_news_domain_model_news
            source.data = GP:tx_news_pi1|news
            source.intval = 1
            conf.tx_news_domain_model_news = TEXT
            conf.tx_news_domain_model_news {
                field = title
                htmlSpecialChars = 1
            }
            wrap =  <li>|</li>
        }
    }

The relevant part starts with *20 = RECORDS* as this cObject renders the title of the news article. **Important:** Never forget the *source.intval = 1* to avoid SQL injections and the *htmlSpecialChars = 1* to avoid Cross-Site Scripting!

Add HTML to the header part in the detail view.
"""""""""""""""""""""""""""""""""""""""""""""""

There might be a use case where you need to add specific code to the header part when the detail view is rendered.
There are some possible ways to go.

Plain TypoScript
****************

You could use a code like the following one to render e.g. the title of a news article inside a title-tag. ::

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
**********************

Use a viewHelper of EXT:news to write any code into the header part. The code could look like this
    <n:headerData><script>var newsId = {newsItem.uid};</n:headerData>

If you want to set the title tag, you can use a specific viewHelper: ::
    <n:titleTag>{newsItem.title}</n:titleTag>

