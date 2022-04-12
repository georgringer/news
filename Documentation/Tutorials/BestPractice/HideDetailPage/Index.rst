.. include:: /Includes.rst.txt

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

The URL of a news record should howeverb be `domain.tld/blog/news-record`.

The page *Blog* typically contains not only the news plugin with the list view
but also additional regular content elements which must **not** be rendered in the detail view.

The following TypoScript will make this possible:

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
            pluginName = Pi1
            vendorName = GeorgRinger

            switchableControllerActions {
                News {
                    1 = detail
                }
            }

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
