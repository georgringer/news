.. include:: /Includes.rst.txt

.. _quickConfiguration:

===================
Quick configuration
===================

Include TypoScript template
===========================

It is necessary to include at least the basic TypoScript provided by this
extension.

Go module :guilabel:`Web > Template` and chose your root page. It should
already contain a TypoScript template record. Switch to view
:guilabel:`Info/Modify` and click on :guilabel:`Edit the whole template record`.

.. include:: /Images/AutomaticScreenshots/NewsIncludeTypoScript.rst.txt

Switch to tab :guilabel:`Includes` and add the following templates from the list
to the right: :guilabel:`News (news)`. It is possible to include additional
templates provided by the news extension depending on your use case. For example
you can additionally chose :guilabel:`News Styles Twitter Bootstrap V5 (news)`
to use templates for the css framework Bootstrap in version 5.

Read more about possible configurations via TypoScript in the
:ref:`Reference <typoscript>` section.

Further reading
===============

*  :ref:`Global extension configuration <extensionConfiguration>`
*  :ref:`TypoScript <typoscript>`, mainly configuration for the frontend
*  :ref:`TsConfig <tsconfig>`, configuration for the backend
*  :ref:`Routing <routing>` for human readable URLs
*  :ref:`Templating <quickTemplating>` customize the templates
