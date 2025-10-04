.. _configuration-site-set:

============================
Configuration with Site-Sets
============================

..  versionadded:: TYPO3 v13.4.15 / news v13.0
    Use at least EXT:news 13.0.0 to get the full features of site sets.

The extension ships sets which can are used to configure various features.

.. seealso::
    Read more about Site Sets in the general documentation: :ref:`Site sets <t3coreapi/13:site-sets>`!


#.  Got to backend module :guilabel:`Site Management > Sites`.

#.  Edit the configuration of your site.

#.  On the first tab go to :guilabel:`Sets for this Site`.

#.  Choose the sets you need. For the basic setup choose site set :guilabel:`EXT:news :: Basic Setup`.

#.  After saving, you can switch to the backend module :guilabel:`Site Management > Settings` to adopt the configurations.

    .. figure:: /Images/References/Sets/set-overview.png
       :class: with-shadow

#.  Click on :guilabel:`Edit settings` to open the settings module. Each selected site set can provide its own settings.

    .. figure:: /Images/References/Sets/set-settings.png
       :class: with-shadow

#.  All settings are persisted in :file:`/config/sites/<your-site-identifier>/settings.yaml`.

Available Site Sets
===================

.. note::
    Depending on your setup, it is possible that not all site sets are available!

..  contents::
    :local:
    :depth: 1

.. _siteset-basic:

EXT:news :: Basic Setup
-----------------------

This is the basic site set. It is loaded **automatically** by all other site sets of this extension.

Available settings
~~~~~~~~~~~~~~~~~~

..  confval-menu::
    :name: confval-group-basic
    :type:
    :required:
    :display: table

    .. confval:: news.view.templateRootPath
       :type: string
       :required: false
       :default: EXT:news/Resources/Private/Templates/

       Path to the templates

    .. confval:: news.view.partialRootPath
       :type: string
       :required: false
       :default: EXT:news/Resources/Private/Partials/

       Path to the partials

    .. confval:: news.view.layoutRootPath
       :type: string
       :required: false
       :default: EXT:news/Resources/Private/Layouts/

       Path to the layouts

    .. confval:: news.pages.detail
       :type: page
       :required: false
       :default: 0

       Page with detail view. This can be used as a site wide configuration for the detail view, used for all links to news records.

    .. confval:: news.preview.page
       :type: page
       :required: false
       :default: 0

       Page with detail plugin used for previews.
       Allow editors to "save & view" a news record by providing the detail page

    .. confval:: news.preview.mode
       :type: enum
       :required: false
       :default: news

       Either select "news" or "news_preview" as used argument name.
       The latter is required if preview should also work for hidden or restricted records.

       .. seealso::
        Read more about the required additional configuration at :ref:`tsPreviewHiddenRecords`.


.. _siteset-twb5:

EXT:news :: Twitter Bootstrap 5 Styles
--------------------------------------
Load the default template variants using Twitter Bootstrap v5 styles.


.. _siteset-twb4:

EXT:news :: Twitter Bootstrap 4 Styles
--------------------------------------
Load the default template variants using Twitter Bootstrap v4 styles.

.. _siteset-record-links:

EXT:news :: Record Links
------------------------

Record links (or historically known as linkhandler) allow editors to link to
news records by selecting the record without needing to know on which page the
record will be rendered.

.. warning::
    Never link to records by using an external url. Always use the record links!

More information can be found at :ref:`linkhandler`.



Available settings
~~~~~~~~~~~~~~~~~~

..  confval-menu::
    :name: confval-group-recordlinks
    :type:
    :required:
    :display: table

    .. confval:: news.recordLinks.label
       :type: string
       :required: true
       :default: LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:recordLinks.label

       The label used in the Link Browser. Use either a localizable string starting with LLL:EXT or directly a label

    .. confval:: news.recordLinks.storagePid
       :type: page
       :required: false
       :default: 0

       The link browser starts with the given page. Ease the workflow by preselecting the news article storage page.

    .. confval:: news.recordLinks.hidePageTree
       :type: bool
       :required: false
       :default: false

       Hide the page tree in the link browser. If only one storage page is used, the page tree can be hidden with this setting.

    .. confval:: news.recordLinks.detail
       :type: page
       :required: true
       :default: 0

       Detail page. Select the page used for the detail view of the link.

.. _siteset-sitemap:

EXT:news :: Sitemap
-------------------

This site sets provides a configuration to generate a sitemap for news records.

.. note::
    This sitemap requires the core extension `seo` to be installed!

Available settings
~~~~~~~~~~~~~~~~~~

..  confval-menu::
    :name: confval-group-sitemap
    :type:
    :required:
    :display: table

    .. confval:: news.sitemap.detail
       :type: page
       :required: true
       :default: 0

       Select the page used to link the sitemap entries to.

    .. confval:: news.sitemap.startingpoint
       :type: page
       :required: true
       :default: 0

       Select the page containing the news records (Starting point)


    .. confval:: news.sitemap.recursive
       :type: int
       :required: false
       :default: 0

       Levels used to fetch pages containing the news records


    .. confval:: news.sitemap.additionalWhere
       :type: string
       :required: false
       :default:

       Optional constraint to limit news records used in the sitemap
