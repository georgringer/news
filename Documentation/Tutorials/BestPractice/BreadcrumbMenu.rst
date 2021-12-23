FlexForm
.. include:: /Includes.rst.txt

.. _breadcrumb:

===============
Breadcrumb menu
===============

There are currently two suggested ways to make a breadcrumb menu containing
the detail page of the current news: Based on data processing and Fluid template
and based on pure TypoScript. Use the first method if you have no breadcrumb yet
or your breadcrumb is already based on data processors. Use the second if
your breadcrumb is already based on TypoScript and you do not wish to change it
for now.

.. _breadcrumbFluid:

Breadcrumb based on data processing and Fluid
=============================================

.. versionadded:: 7.2.0
   With version 7.2 a new data processor, :php:`AddNewsToMenuProcessor` has
   been added which is useful for detail pages to add the news record as
   fake menu entry.

To use the data processor :php:`AddNewsToMenuProcessor` add the following
TypoScript to the setup section in your site package extension. We assume
here that your main :typoscript:`FLUIDTEMPLATE` can be found in
:typoscript:`page.10`.

.. code-block:: typoscript

   page.10 = FLUIDTEMPLATE
   page.10 {
       # [...] template settings
       dataProcessing {
           # [...] Other data processors
           50 = TYPO3\CMS\Frontend\DataProcessing\MenuProcessor
           50 {
               as = breadcrumbMenu
               special = rootline
           }
           60 = GeorgRinger\News\DataProcessing\AddNewsToMenuProcessor
           60.menus = breadcrumbMenu
       }
   }

The property :typoscript:`menus` of the :php:`AddNewsToMenuProcessor` must
contain the key of the :php:`MenuProcessor` containing your breadcrumb. If you
You can use more then one menu here by supplying several keys as comma-separated
list. For example: :typoscript:`60.menus = breadcrumbMenu,myOtherBreadcrumb`.

The data array containing your breadcrumb will now contain an additional entry
if you are on a news detail page. You can debug this data with the following
Fluid snippet:

.. code-block:: html

   <f:debug>{breadcrumbMenu}</f:debug>

The array will then contain something like that:

.. code-block:: none

   array(4 items)
      0 => array(7 items)
      1 => array(7 items)
      2 => array(7 items)
         data => array(84 items)
         title => 'All News' (17 chars)
         link => '/portal/news/' (34 chars)
         target => '' (0 chars)
         active => 1 (integer)
         current => 0 (integer)
         spacer => 0 (integer)
      3 => array(6 items)
         data => array(87 items)
         title => 'Test news' (13 chars)
         active => 1 (integer)
         current => 1 (integer)
         link => 'https://my-page.ddev.site/portal/news/articel/test-news' (101 chars)
         isNews => TRUE

You can use code like the following in your sites Fluid template.

.. code-block:: html

   <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <f:for each="{breadcrumbMenu}" as="item" iteration="iterator">
                <li class="breadcrumb-item ">
                    <a href="{item.link}" title="{item.title}">
                        <f:if condition="{item.isNews}"><i class="fas fa-newspaper"></i></f:if>
                        {item.title}
                    </a>
                </li>
            </f:for>
            </ol>
        </nav>
   </div>

The result (using Bootstrap 5 and Fontawesome 5 Free) could use like this:

.. figure:: /Images/Frontend/Breadcrumb.png
   :class: with-shadow

   A breadcrumb containing the current news record.

.. hint::
   I you are displaying the news on a single page that should not be displayed
   without a valid news record, unset the flag :guilabel:`Page enabled in menus`
   in the single pages page properties. This way the page alone does not appear
   in the breadcrumb.


See also chapter :ref:`AddNewsToMenuProcessor <dataProcessing_AddNewsToMenuProcessor>`.


.. _breadcrumbTypoScript:

Breadcrumb based on TypoScript (legacy)
=======================================

If you already have a breadcrumb menu based on TypoScript in your project,
you can continue to use it and add the news record to it.

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

The relevant part starts with :typoscript:`20 = RECORDS` as this cObject
renders the title of the news article.

.. Important::
   Never forget the :typoscript:`source.intval = 1` to avoid SQL injections
   and the :typoscript:`htmlSpecialChars = 1` to avoid Cross-Site Scripting.
   See :ref:`security in TypoScript in TYPO3 Explained
   <t3coreapi:security-typoscript>`.

