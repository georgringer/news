.. _hideDetailPage:

=======================
Hide detail page in URL
=======================

This tutorials covers the use case of having the following page structure:

.. code-block:: none

   .
   └── Root
       ├── Home
       └── Blog <= news

The URL of a news record should however be `domain.tld/blog/news-record`.

The page *Blog* typically contains not only the news plugin with the list view
but also additional regular content elements which must **not** be rendered in the detail view.

..  contents::
    :local:
    :depth: 1

Using FLUIDTEMPLATE
-------------------

Use the following TypoScript:

.. code-block:: typoscript

    # Override content rendering if the news record is requested
    [traverse(request.getQueryParams(), 'tx_news_pi1/news') > 0]

        # typically having something like: page.10 = FLUIDTEMPLATE
        # optional to use a custom page template
        page.10.templateName = NewsDetail

        lib.dynamicContent >
        lib.dynamicContent = USER
        lib.dynamicContent {
            userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
            extensionName = News
            pluginName = NewsDetail
            vendorName = GeorgRinger

            settings < plugin.tx_news.settings
            settings {
                # fully optional but can be used to react on it in
                # the news templates
                templateLayout = renderedByTs
            }
        }
    [end]


The **page template** `NewsDetail.html` must contain the following code to
render the news detail view:

.. code-block:: html

    <f:cObject typoscriptObjectPath="lib.dynamicContent" />

Some further explanations
^^^^^^^^^^^^^^^^^^^^^^^^^

* The TypoScript condition will only be true if a news record path is in the URL
* It overrides the used page template to be able to use a custom one
* It set the variable `lib.dynamicContent` to contain the rendered news detail view
* The dedicated page template can be fully adopted
* If needed, the News detail template can be adopted by either use a settings variable or by providing a custom view path.

Using PAGEVIEW
--------------

..  versionadded:: TYPO3 v13
    The cObject `PAGEVIEW` should be used since version 13 to define pate templates.

Use the following TypoScript to define a variable depending if the news detail view is rendere

.. code-block:: typoscript

    [traverse(request.getQueryParams(), 'tx_news_pi1/news') > 0]
        page.10.variables.isDetail = TEXT
        page.10.variables.isDetail.value = 1
    [end]

The page template (which can be typically found in `Pages/Default.html`) can now
be modified to render only the plugin of EXT:news.

.. code-block:: html

    <f:if condition="{isDetail}">
        <f:then>
            <f:for each="{content.main.records}" as="contentElement">
                <f:if condition="{contentElement.CType} == news_pi1">
                    <f:cObject
                        typoscriptObjectPath="{contentElement.mainType}"
                        table="{contentElement.mainType}"
                        data="{contentElement}"
                    />
                </f:if>
            </f:for>
        </f:then>
        <f:else>
            <f:for each="{content.main.records}" as="contentElement">
                <f:cObject
                    typoscriptObjectPath="{contentElement.mainType}"
                    table="{contentElement.mainType}"
                    data="{contentElement}"
                />
            </f:for>
        </f:else>
    </f:if>

Alternative solution

.. code-block:: html

    <f:if condition="{isDetail}">
        <f:then>
            <f:cObject typoscriptObjectPath="lib.tx_news.myNewsDetail" />
        </f:then>
        <f:else>
            <f:for each="{content.main.records}" as="contentElement">
                <f:cObject
                    typoscriptObjectPath="{contentElement.mainType}"
                    table="{contentElement.mainType}"
                    data="{contentElement}"
                />
            </f:for>
        </f:else>
    </f:if>

and

.. code-block:: typoscript

    lib.tx_news.myNewsDetail = USER
    lib.tx_news.myNewsDetail {
        userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
        extensionName = News
        pluginName = NewsDetail
        vendorName = GeorgRinger

        mvc {
            callDefaultActionIfActionCantBeResolved = 1
        }

        settings < plugin.tx_news.settings
        settings {
            myCustomView = 1
        }
    }
