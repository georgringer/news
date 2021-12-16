.. include:: /Includes.rst.txt

.. _routing:

===========================
Use Routing to rewrite URLs
===========================

This section will show you how you can rewrite the URLs for news using
**Routing Enhancers and Aspects**. TYPO3 Explained has an capter
:ref:`Introduction to routing <t3coreapi:routing-introduction>` that you can read
if you are not familiar with the concept yet. You will no
longer need third party extensions like RealURL or CoolUri to rewrite and
beautify your URLs.

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
         - routePath: '/{news-title}'
           _controller: 'News::detail'
           _arguments:
             news-title: news
       aspects:
         news-title:
           type: PersistedAliasMapper
           tableName: tx_news_domain_model_news
           routeFieldName: path_segment

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

**Prerequisites:**

The plugins for :guilabel:`List View` and :guilabel:`Detail View` are on
separate pages.

If you use the :guilabel:`Category Menu` or :guilabel:`Tag List` plugins to
filter news records, their titles (slugs) are used.

**Result:**

* Detail view: ``https://www.example.com/news/detail/the-news-title``
* Pagination: ``https://www.example.com/news/page-2``
* Category filter: ``https://www.example.com/news/my-category``
* Tag filter: ``https://www.example.com/news/my-tag``

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
         - routePath: '/page-{page}'
           _controller: 'News::list'
           _arguments:
             page: 'currentPage'
         - routePath: '/{news-title}'
           _controller: 'News::detail'
           _arguments:
             news-title: news
         - routePath: '/{category-name}'
           _controller: 'News::list'
           _arguments:
             category-name: overwriteDemand/categories
         - routePath: '/{tag-name}'
           _controller: 'News::list'
           _arguments:
             tag-name: overwriteDemand/tags
       defaultController: 'News::list'
       defaults:
         page: '0'
       aspects:
         news-title:
           type: PersistedAliasMapper
           tableName: tx_news_domain_model_news
           routeFieldName: path_segment
         page:
           type: StaticRangeMapper
           start: '1'
           end: '100'
         category-name:
           type: PersistedAliasMapper
           tableName: sys_category
           routeFieldName: slug
         tag-name:
           type: PersistedAliasMapper
           tableName: tx_news_domain_model_tag
           routeFieldName: slug
     PageTypeSuffix:
       type: PageType
       map:
         'feed.xml': 9818
         'calendar.ical': 9819

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
         # Pagination:
         - routePath: '/'
           _controller: 'News::list'
         - routePath: '/page-{page}'
           _controller: 'News::list'
           _arguments:
             page: 'currentPage'
           requirements:
             page: '\d+'
         - routePath: '/{news-title}'
           _controller: 'News::detail'
           _arguments:
             news-title: news
         # Date year:
         - routePath: '/{date-year}'
           _controller: 'News::list'
           _arguments:
             date-month: 'overwriteDemand/month'
             date-year: 'overwriteDemand/year'
             page: 'currentPage'
           requirements:
             date-year: '\d+'
         # Date year + pagination:
         - routePath: '/{date-year}/page-{page}'
           _controller: 'News::list'
           _arguments:
             date-year: 'overwriteDemand/year'
             page: 'currentPage'
           requirements:
             date-year: '\d+'
             page: '\d+'
         # Date year/month:
         - routePath: '/{date-year}/{date-month}'
           _controller: 'News::list'
           _arguments:
             date-month: 'overwriteDemand/month'
             date-year: 'overwriteDemand/year'
             page: 'currentPage'
           requirements:
             date-month: '\d+'
             date-year: '\d+'
          # Date year/month + pagination:
         - routePath: '/{date-year}/{date-month}/page-{page}'
           _controller: 'News::list'
           _arguments:
             date-month: 'overwriteDemand/month'
             date-year: 'overwriteDemand/year'
             page: 'currentPage'
           requirements:
             date-month: '\d+'
             date-year: '\d+'
             page: '\d+'
       defaultController: 'News::list'
       defaults:
         page: '0'
         date-month: ''
         date-year: ''
       aspects:
         news-title:
           type: PersistedAliasMapper
           tableName: tx_news_domain_model_news
           routeFieldName: path_segment
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

References
----------

*  :ref:`TYPO3 Documentation: Routing <t3coreapi:routing-introduction>`
*  :ref:`TYPO3 Documentation: Site Handling <t3coreapi:sitehandling>`
*  `TYPO3 CMS Core Changelog 9.5: Feature: #86365 - Routing Enhancers and Aspects <https://docs.typo3.org/typo3cms/extensions/core/Changelog/9.5/Feature-86365-RoutingEnhancersAndAspects.html>`__
*  `TYPO3 CMS Core Changelog 9.5: Feature: #86160 - PageTypeEnhancer for mapping &type parameter <https://docs.typo3.org/typo3cms/extensions/core/Changelog/9.5/Feature-86160-PageTypeEnhancerForMappingTypeParameter.html>`__
