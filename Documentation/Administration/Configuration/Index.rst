.. _configuration:

=============
Configuration
=============

.. _configuration-site-set:

Site Sets
=========

..  versionadded:: TYPO3 v13.1 / news v12.0
    If you are working with TYPO3 v12.4, use :ref:`configuration-typoscript-record`.

The extension ships some TypoScript code which can be included in the site
configuration via :ref:`Site sets <t3coreapi/13:site-sets>`:

#.  Got to backend module :guilabel:`Site Management > Sites`.

#.  Edit the configuration of your site.

#.  On the first tab go to :guilabel:`Sets for this Site`.

#.  If you want to news with the plain template choose site set :guilabel:`News`.

#.  If you need bootstrap styles choose the site set :guilabel:`News Twb5`
    instead.

#.  If :composer:`typo3/cms-seo` is installed and you want to feature a sitemap
    containing all news, choose :guilabel:`News Sitemap`.


.. _configuration-typoscript-record:

TypoScript Records
==================

The extension ships some TypoScript code which needs to be included.

#. Switch to the root page of your site.

#. Switch to :guilabel:`Template > Info/Modify`.

#. Press the link :guilabel:`Edit the whole template record` and switch to the
   tab :guilabel:`Includes`.

#. Select :guilabel:`News (news)` at the field :guilabel:`Include static (from extensions):`

   .. include:: /Images/AutomaticScreenshots/NewsIncludeTypoScript.rst.txt

#. Include the static template of `EXT:fluid_styled_content` or provide the
   following TypoScript yourself:

   .. code-block:: typoscript

      plugin.tx_news.settings.detail.media.image.lightbox {
         enabled = 0
         class = lightbox
         width = 800m
         height = 600m
      }

#. **Optional:** If your templates are based on Twitter Bootstrap, add the TWB
   styles as well to get optimized templates.
