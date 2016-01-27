.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Using Twitter Bootstrap templates
=================================

Since version *4.1.0* a template variant based on Twitter Bootstrap is available.

To be able to use it, include **additionally** the entry **News Styles Twitter Bootstrap** in the section "Include Static (from extensions)" in the template record.

By changing the following constants, you can use those templates as base for your work.

.. code-block:: typoscript

	plugin.tx_news {
		view.twb {
			templateRootPath = EXT:news/Resources/Private/Templates/Styles/Twb/Templates
			partialRootPath = EXT:news/Resources/Private/Templates/Styles/Twb/Partials/
			layoutRootPath = EXT:news/Resources/Private/Templates/Styles/Twb/Layouts/
		}
	}

.. Hint::

	The templates don't list the deprecated non-fal relations for images.
