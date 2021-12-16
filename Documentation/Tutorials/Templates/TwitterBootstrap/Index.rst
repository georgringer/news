.. include:: /Includes.rst.txt

.. _templatesBootstrap:

===================
Bootstrap templates
===================

The following Twitter Bootstrap Template variants are available:

- v3
- v5

To be able to use it, include **additionally** the entry **News Styles Twitter Bootstrap** or **News Styles Twitter Bootstrap V5** in the section "Include Static (from extensions)" in the template record.

By changing the following constants, you can use those templates as base for your work.

.. code-block:: typoscript

    # v5
    plugin.tx_news {
        view.twb5 {
            templateRootPath = EXT:news/Resources/Private/Templates/Styles/Twb5/Templates
            partialRootPath = EXT:news/Resources/Private/Templates/Styles/Twb5/Partials/
            layoutRootPath = EXT:news/Resources/Private/Templates/Styles/Twb5/Layouts/
        }
    }

    # v3
    plugin.tx_news {
        view.twb {
            templateRootPath = EXT:news/Resources/Private/Templates/Styles/Twb/Templates
            partialRootPath = EXT:news/Resources/Private/Templates/Styles/Twb/Partials/
            layoutRootPath = EXT:news/Resources/Private/Templates/Styles/Twb/Layouts/
        }
    }
