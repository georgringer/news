.. _routing:

===========================
Use Routing to rewrite URLs
===========================

This section will show you how you can rewrite the URLs for news using
**Routing Enhancers and Aspects**. TYPO3 Explained has a chapter
:ref:`Introduction to routing <t3coreapi:routing-introduction>` that you can read
if you are not familiar with the concept yet. You will no
longer need third party extensions like RealURL or CoolUri to rewrite and
beautify your URLs.

.. Important::
   To reduce the possibilities of errors, one of the following configuration options
   should always be used if no fallback is used.

   - :yaml:`limitToPages`: Limit the routing configuration to specific pages
   - :yaml:`routePath: '/article/{news-title}'`: Use a prefix for routes

    Read more about it :ref:`here <routing_fallbacks>`!

..  _routing_quickstart:

Quick start
===========

This section explains in short how to rewrite the URLs for the detail page in a
project where there is only one detail view page on the whole site and where
rewriting of things like pagination is not desired or needed.

Open the configuration of the site. You should find it at
:file:`/config/sites/<your_identifier>/config.yaml`.

At the bottom of the file include the following:

.. code-block:: yaml
   :caption: /config/sites/<your_identifier>/config.yaml

   routeEnhancers:
     News:
       type: Extbase
       limitToPages:
         - <uid of single page>
       extension: News
       plugin: Pi1
       routes:
         - routePath: '/article/{news-title}'
           _controller: 'News::detail'
           _arguments:
             news-title: news
       aspects:
         news-title:
           type: NewsTitle

Save the file, delete all caches and try it out

Troubleshooting
---------------

*   Did you save the site configuration file?
*   Did you delete all caches?
*   In the format YAML indentation matters. The code above **must** be indentated exactly
    as shown, the keyword `routeEnhancers` **must not** be indeted.
*   The configuration above is limited to only one page containing a single view of news.
    Did you put the correct pid of page containing the news plugin displaying single news?


..  _routing_detailed:

Detailed explaination and advanced use cases
============================================

.. _how_to_rewrite_urls:

How to rewrite URLs with news parameters
----------------------------------------

On setting up your page you should already have created a **site configuration**.
You can do this in the backend module :guilabel:`Site Managements > Sites`.

Your site configuration will be stored in
:file:`/config/sites/<your_identifier>/config.yaml`. The following
configurations have to be applied to this file.

Any URL parameters can be rewritten with the Routing Enhancers and Aspects.
These are added manually in the :file:`config.yaml`:

#. Add a section :yaml:`routeEnhancers`, if one does not already exist.
#. Choose an unique identifier for your Routing Enhancer. It doesn't have
   to match any extension key.
#. :yaml:`type`: For news, the Extbase Plugin Enhancer (:yaml:`Extbase`)
   is used.
#. :yaml:`extension`: the extension key, converted to :code:`UpperCamelCase`.
#. :yaml:`plugin`: the plugin name of news is just *Pi1*.
#. After that you will configure individual routes and aspects depending on
   your use case.

.. code-block:: yaml
   :linenos:
   :caption: :file:`/config/sites/<your_identifier>/config.yaml`

   routeEnhancers:
     News:
       type: Extbase
       extension: News
       plugin: Pi1
       # routes and aspects will follow here

.. tip::

   If your routing doesn't work as expected, check the **indentation** of your
   configuration blocks.
   Proper indentation is crucial in YAML.

Using limitToPages
~~~~~~~~~~~~~~~~~~

It is recommended to limit :yaml:`routeEnhancers` to the pages where they are needed.
This will speed up performance for building page routes of all other pages.

.. code-block:: yaml
   :caption: :file:`/config/sites/<your_identifier>/config.yaml`
   :linenos:
   :emphasize-lines: 4-7

   routeEnhancers:
     News:
       type: Extbase
       limitToPages:
         - 8
         - 10
         - 11
       extension: News
       plugin: Pi1
       # routes and aspects will follow here

.. warning::

   Not setting the `limitToPages` parameter may lead to unwanted side effects, e. g. not working error handling!

Multiple routeEnhancers for news
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you use the news extension for different purposes on the same website
(for example news and events), you may want different URL paths for
them (for example */article/* and */event/*).
It is possible to configure more than one routing enhancer for the news plugin
on the same website.

Use :yaml:`limitToPages` to assign the appropriate configuration to the
desired pages.

.. code-block:: yaml
   :caption: :file:`/config/sites/<your_identifier>/config.yaml`
   :linenos:
   :emphasize-lines: 2,11

   routeEnhancers:
     News:
       type: Extbase
       limitToPages:
         - 8
         - 10
         - 11
       extension: News
       plugin: Pi1
       # etc.
     NewsEvents:
       type: Extbase
       limitToPages:
         - 17
         - 18
       extension: News
       plugin: Pi1
       # etc.

About routes and aspects
~~~~~~~~~~~~~~~~~~~~~~~~

In a nutshell:

* :yaml:`routes` will extend an existing route (means: your domain and page
   path) with arguments from GET parameters, like the following
   controller/action pair of the news detail view.
* :yaml:`aspects` can be used to modify these arguments. You could for
   example map the title (or better: the optimized path segment) of the
   current news.
   Different types of *Mappers* and *Modifiers* are available, depending on
   the case.

1. URL of detail page without routing:

.. code-block:: none

   https://www.example.com/news/detail?tx_news_pi1[action]=detail&tx_news_pi1[controller]=News&tx_news_pi1[news]=5&cHash=

2. URL of detail page with routes:

.. code-block:: none

   https://www.example.com/news/detail/5?cHash=

3. URL of detail page with routes and aspects:

.. code-block:: none

   https://www.example.com/news/detail/title-of-news-article

The following example will only provide routing for the detail view:

.. code-block:: yaml
   :caption: :file:`/config/sites/<your_identifier>/config.yaml`
   :linenos:

   routeEnhancers:
     News:
       type: Extbase
       extension: News
       plugin: Pi1
       routes:
         - routePath: '/article/{news-title}'
           _controller: 'News::detail'
           _arguments:
             news-title: news
       aspects:
         news-title:
           type: NewsTitle

Please note the placeholder :code:`{news-title}`:

#. First, you assign the value of the news parameter (:code:`tx_news_pi1[news]`)
   in :yaml:`_arguments`.
#. Next, in :yaml:`routePath` you add it to the existing route.
#. Last, you use :yaml:`aspects` to map the :code:`path_segment` of the
   given argument.

Both routes and aspects are only available within the current Routing Enhancer.

The names of placeholders are freely selectable.

Common routeEnhancer configurations
-----------------------------------

Basic setup (including categories, tags and the RSS/Atom feed)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The order of the config does matter!
If you want to have  categories+pagination, that configuration has to stand before the part for categories alone

**Result:**

* Detail view: ``https://www.example.com/news/detail/the-news-title``
* Pagination: ``https://www.example.com/news/page-2``
* Category filter: ``https://www.example.com/news/category/my-category``
* Category filter + pagination: ``https://www.example.com/news/category/my-category/page-2``
* Tag filter: ``https://www.example.com/news/tag/my-tag``
* Tag filter + pagination: ``https://www.example.com/news/tag/my-tag/page-2``

.. code-block:: yaml
   :caption: :file:`/config/sites/<your_identifier>/config.yaml`
   :linenos:

   routeEnhancers:
     News:
       type: Extbase
       extension: News
       plugin: Pi1
       routes:
         - routePath: '/'
           _controller: 'News::list'
         # Pagination
         - routePath: '/page-{page}'
           _controller: 'News::list'
           _arguments:
             page: 'currentPage'
         # Category + pagination:
         - routePath: '/category/{category-name}/page-{page}'
           _controller: 'News::list'
           _arguments:
             category-name: overwriteDemand/categories
             page: 'currentPage'
         # Category
         - routePath: '/category/{category-name}'
           _controller: 'News::list'
           _arguments:
             category-name: overwriteDemand/categories
         # Tagname + pagination
         - routePath: '/tag/{tag-name}/page-{page}'
           _controller: 'News::list'
           _arguments:
             tag-name: overwriteDemand/tags
             page: 'currentPage'
         # Tagname
         - routePath: '/tag/{tag-name}'
           _controller: 'News::list'
           _arguments:
             tag-name: overwriteDemand/tags
         # Detail
         - routePath: '/article/{news-title}'
           _controller: 'News::detail'
           _arguments:
             news-title: news
       defaultController: 'News::list'
       defaults:
         page: '0'
       aspects:
         news-title:
           type: NewsTitle
         page:
           type: StaticRangeMapper
           start: '1'
           end: '100'
         category-name:
           type: NewsCategory
         tag-name:
           type: NewsTag
     PageTypeSuffix:
       type: PageType
       map:
         'feed.xml': 9818
         'calendar.ical': 9819

.. tip::
   If you are using the routing for pagination,
   be sure it is in the code before the configuration
   for the detail view! Otherwise you can run into trouble on pages
   with plugin view "List articles with detail view".

Localized pagination
~~~~~~~~~~~~~~~~~~~~

**Prerequisites:**

The website provides several frontend languages.

**Result:**

* English: ``https://www.example.com/news/page-2``
* Danish: ``https://www.example.com/da/news/side-2``
* German: ``https://www.example.com/de/news/seite-2``

.. code-block:: yaml
   :caption: :file:`/config/sites/<your_identifier>/config.yaml`
   :linenos:
   :emphasize-lines: 21-27

   routeEnhancers:
     News:
       type: Extbase
       extension: News
       plugin: Pi1
       routes:
         - routePath: '/{page-label}-{page}'
           _controller: 'News::list'
           _arguments: {'page': 'currentPage'}
       defaultController: 'News::list'
       defaults:
         page: ''
       requirements:
         page: '\d+'
       aspects:
         page:
           type: StaticRangeMapper
           start: '1'
           end: '100'
         page-label:
           type: LocaleModifier
           default: 'page'
           localeMap:
             - locale: 'da_DK.*'
               value: 'side'
             - locale: 'de_DE.*'
               value: 'seite'

**Explanation:**

The :yaml:`LocaleModifier` aspect type will set a default value for the
English language.
You're then able to add as many :yaml:`localeMap` configurations as you
need for the page translations of your website.
The value of :yaml:`locale` refers to the value in your site configuration.

Human readable dates
~~~~~~~~~~~~~~~~~~~~

**Prerequisites:**

For :guilabel:`List View` with a :guilabel:`Date Menu` plugin, to filter
by date. Also includes configuration for the pagination.

**Result:**

* ``https://www.example.com/news/2018/march``
* ``https://www.example.com/news/2018/march/page-2``

.. code-block:: yaml
   :caption: :file:`/config/sites/<your_identifier>/config.yaml`
   :linenos:

   routeEnhancers:
     DateMenu:
       type: Extbase
       extension: News
       plugin: Pi1
       routes:
          # Date year/month + pagination:
         - routePath: '/{date-year}/{date-month}/page-{page}'
           _controller: 'News::list'
           _arguments:
             date-month: 'overwriteDemand/month'
             date-year: 'overwriteDemand/year'
             page: 'currentPage'
         # Date year/month:
         - routePath: '/{date-year}/{date-month}'
           _controller: 'News::list'
           _arguments:
             date-month: 'overwriteDemand/month'
             date-year: 'overwriteDemand/year'
             page: 'currentPage'
         # Date year + pagination:
         - routePath: '/{date-year}/page-{page}'
           _controller: 'News::list'
           _arguments:
             date-year: 'overwriteDemand/year'
             page: 'currentPage'
         # Date year:
         - routePath: '/{date-year}'
           _controller: 'News::list'
           _arguments:
             date-month: 'overwriteDemand/month'
             date-year: 'overwriteDemand/year'
             page: 'currentPage'
         # Pagination:
         - routePath: '/'
           _controller: 'News::list'
         - routePath: '/page-{page}'
           _controller: 'News::list'
           _arguments:
             page: 'currentPage'
         - routePath: '/article/{news-title}'
           _controller: 'News::detail'
           _arguments:
             news-title: news
       defaultController: 'News::list'
       defaults:
         page: '0'
         date-month: ''
         date-year: ''
       requirements:
         date-month: '\d+'
         date-year: '\d+'
         page: '\d+'
       aspects:
         news-title:
           type: NewsTitle
         page:
           type: StaticRangeMapper
           start: '1'
           end: '25'
         date-month:
           type: StaticValueMapper
           map:
             january: '01'
             february: '02'
             march: '03'
             april: '04'
             may: '05'
             june: '06'
             july: '07'
             august: '08'
             september: '09'
             october: '10'
             november: '11'
             december: '12'
         date-year:
           type: StaticRangeMapper
           start: '2000'
           end: '2030'

**Explanation:**

You will need a new :yaml:`routePath` for every possible combination of
arguments (pagination, month with/without pagination, ...).

**Potential errors:**

If you want :code:`2018/march` but get :code:`2018/3` instead, compare your
:yaml:`StaticValueMapper` for months with your date arguments.
Are you using different date formats (with/without leading zeros)?

You can either remove the leading zero in your :yaml:`aspects` or adapt the
TypoScript setting:

.. code-block:: typoscript
   :caption: TypoScript setup
   :linenos:
   :emphasize-lines: 6

   plugin.tx_news.settings.link {
       hrDate = 1
       hrDate {
           day = j
           // 'n' for 1 through 12. 'm' for 01 through 12.
           month = m
           year = Y
       }
   }

You can configure each argument (day/month/year) separately by using the
configuration of PHP function `date <http://www.php.net/date>`__.

.. warning::

   | **Oops, an error occurred!**
   | Possible range of all mappers is larger than 10000 items

   Using the :yaml:`StaticRangeMapper` is strictly limited to 1000 items per
   a single range and 10000 items per routing enhancer.

   That means you'll have to multiply all possible combinations in a routing
   enhancer, for example:

   12 months × 30 years *(2000-2030)* × 25 pages *(pagination)* = 9000 possible
   items

   If you exceed this limit, you'll either have to build a custom and more
   specific mapper, or reduce the range in one of your :yaml:`StaticRangeMapper`.

..  _routing_fallbacks:

Fallbacks & Pitfalls
--------------------
Understanding routing is sometimes not that easy and straight forward.

The aspect :yaml:`NewsTitle` is not only shorter to use but more important, it
automatically configures a default value. This is needed to do an error handling
within the extension instead of showing the general "Page not found error".
More information can be found in the official manual: `Aspect fallback value handling <https://docs.typo3.org/permalink/t3coreapi:routing-aspect-fallback-handling>`_
and :ref:`here <tsDetailErrorHandling>` regarding the error handling.

However, defining a fallback value (either manually or using the mentioned aspect)
can interfere with your general error handling configuration.
Therefore it is important to configure one of the following options

1. A prefix like :yaml:`/news-detail/`

.. code-block:: yaml
   :caption: Prefix in path
   :linenos:
   :emphasize-lines: 1

    - routePath: '/news-detail/{news-title}'
      _controller: 'News::detail'
      _arguments:
        news-title: news

2. Use :yaml:`limitToPages`

.. code-block:: yaml
   :caption: Limit to pages
   :linenos:
   :emphasize-lines: 4-6

   routeEnhancers:
     News:
       type: Extbase
       limitToPages:
         - 123
         - 456


How to create URLs in PHP
-------------------------

The following snippet is a good example how an URL can be generated properly

.. code-block:: php
   :caption: PHP Code
   :linenos:

   protected function generateUrl(SiteInterface $site, int $recordId, int $detailPageId): string
       {
           $additionalQueryParams = [
               'tx_news_pi1' => [
                   'action' => 'detail',
                   'controller' => 'News',
                   'news' => $recordId
               ]
           ];
           return (string)$site->getRouter()->generateUri(
               (string)$detailPageId,
               $additionalQueryParams
           );
       }


References
----------

*  :ref:`TYPO3 Documentation: Routing <t3coreapi:routing-introduction>`
*  :ref:`TYPO3 Documentation: Site Handling <t3coreapi:sitehandling>`
*  `TYPO3 CMS Core Changelog 9.5: Feature: #86365 - Routing Enhancers and Aspects <https://docs.typo3.org/typo3cms/extensions/core/Changelog/9.5/Feature-86365-RoutingEnhancersAndAspects.html>`__
*  `TYPO3 CMS Core Changelog 9.5: Feature: #86160 - PageTypeEnhancer for mapping &type parameter <https://docs.typo3.org/typo3cms/extensions/core/Changelog/9.5/Feature-86160-PageTypeEnhancerForMappingTypeParameter.html>`__
