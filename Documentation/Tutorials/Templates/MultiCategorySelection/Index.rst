.. include:: /Includes.rst.txt

.. _templatesMultipleCats:

==================================
Filter news by multiple categories
==================================

The default category template `Category/List` allows only filtering by a single category. If you need to filter by multiple categories, you can use such template:

.. code-block:: html

    <html xmlns:n="http://typo3.org/ns/GeorgRinger/News/ViewHelpers"
          xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers" data-namespace-typo3-fluid="true">

    <f:layout name="General" />
    <!--
        =====================
            Templates/Category/List.html
    -->

    <f:section name="content">
        <f:if condition="{categories}">
            <f:then>


                <f:render section="categoryTree" arguments="{categories:categories,overwriteDemand:overwriteDemand}" />
            </f:then>
            <f:else>
                <f:translate key="list_nocategoriesfound" />
            </f:else>
        </f:if>
    </f:section>

    <f:section name="categoryTree">
        <ul>
            <f:for each="{categories}" as="category">
                <li>
                    <n:multiCategoryLink.isCategoryActive list="{overwriteDemand.categories}" item="{category.item.uid}">
                        <f:then>
                            <f:link.page title="{category.item.title}" class="active" pageUid="{settings.listPid}"
                                additionalParams="{tx_news_pi1:{overwriteDemand:{categories: category.item.uid}}}">{category.item.title}
                            </f:link.page>

                            (<f:link.page title="{category.item.title}" class="active" pageUid="{settings.listPid}"
                                          additionalParams="{n:multiCategoryLink.arguments(mode:'remove',item:category.item.uid,list:overwriteDemand.categories)}">remove
                        </f:link.page>)

                        </f:then>
                        <f:else>
                            <f:link.page title="{category.item.title}" pageUid="{settings.listPid}"
                                additionalParams="{tx_news_pi1:{overwriteDemand:{categories: category.item.uid}}}">{category.item.title}
                            </f:link.page>

                            (<f:link.page title="{category.item.title}" class="active" pageUid="{settings.listPid}"
                                         additionalParams="{n:multiCategoryLink.arguments(mode:'add',item:category.item.uid,list:overwriteDemand.categories)}">add
                            </f:link.page>)
                        </f:else>
                    </n:multiCategoryLink.isCategoryActive>

                    <f:if condition="{category.children}">
                        <f:render section="categoryTree" arguments="{categories: category.children,overwriteDemand:overwriteDemand}" />
                    </f:if>
                </li>
            </f:for>
        </ul>
    </f:section>
    </html>
