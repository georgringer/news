.. _configuration:

=============
Configuration
=============

The extension ships some TypoScript code which needs to be included.
If you are using TYPO3 v13, use the :ref:`site sets <configuration-site-set>`.
If you use TYPO3 v12, use the :ref:`TypoScript includes <configuration-typoscript-includes>`

.. _configuration-site-set:

EXT:news configuration via site set (TYPO3 v13 and above)
=========================================================

..  versionadded:: news 12-13

If you are using TYPO3 v13, include the :ref:`Site sets <t3coreapi:site-sets>`.

Edit your site configuration to add the site set `georgringer/news`:

*   Got to backend module :guilabel:`Site Management > Site`
*   Edit your site configuration
*   Add the desired set or sets to :guilabel:`General > Sets for this Site`

..  figure:: /Images/ManualScreenshots/SiteSets.png

The according site configuration will be changed like this:

..  literalinclude:: _site_config_set.diff
    :caption: config/sites/sitepackage/config.yaml

It is recommended though not enforced to either also use the site set
`typo3/fluid-styled-content`, `typo3/fluid-styled-content-css` or an alternative
templating extension like :composer:`bk2k/bootstrap-package`.

The following site sets are available in this extension:

`georgringer/news`
    All basic configuration you need to use news.
`georgringer/news-seo-sitemap`
    The above plus a seo sitemap configuration. :composer:`typo3/cms-seo` needs
    to be installed.
`georgringer/news-twb4`
    Templates optimized for Twitter Bootstrap 4.
`georgringer/news-twb5`
    Templates optimized for Twitter Bootstrap 5.

If your site depends on a custom site package, you can also include the
`georgringer/news` set in your site package's set:

..  literalinclude:: _site_package_set.diff
    :caption: EXT:my_site_package/Configuration/Sets/MySite/config.yaml

.. _configuration-typoscript-includes:

EXT:news configuration via TypoScript includes (TYPO3 v12)
==========================================================

..  versionchanged:: TYPO3 13
    Including the TypoScript of news via TypoScript includes is deprecated.

#. Switch to the root page of your site.

#. Switch to :guilabel:`Site Management > TypoScript`.

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
