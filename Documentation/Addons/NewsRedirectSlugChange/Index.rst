.. _newsRedirectSlugChange:

=============================
EXT:news_redirect_slug_change
=============================

The system extension `redirects` creates redirects automatically if the slug of a page is changed.
This feature is now available for EXT:news records as well by using the extension `news_redirect_slug_change`.

Get all available information at https://github.com/georgringer/news_redirect_slug_change!

Pricing
-------

This extension is free for use!

Usage
-----

Configuration
^^^^^^^^^^^^^

By using the site configuration (and optionally the page TsConfig) the auto creation of redirects can be configured

.. code-block:: yaml

   settings:
      redirectsNews:
        # Detail page id which can be overruled py pageTsConfig tx_news.redirect.pageId = 456
        pageId: 123
        # Automatically create redirects for news with a new slug (works only in LIVE workspace)
        # (default: true)
        autoCreateRedirects: true
        # Time To Live in days for redirect records to be created - `0` disables TTL, no expiration
        # (default: 0)
        redirectTTL: 30
        # HTTP status code for the redirect, see
        # https://developer.mozilla.org/en-US/docs/Web/HTTP/Redirections#Temporary_redirections
        # (default: 307)
        httpStatusCode: 307
