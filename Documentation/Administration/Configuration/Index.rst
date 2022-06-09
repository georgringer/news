.. include:: /Includes.rst.txt

.. _configuration:

Configuration
=============

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
