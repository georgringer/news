.. include:: /Includes.rst.txt

.. _recordNews:

====
News
====

The news record is obviously the most important record in this extension.


Add news record
===============

News can be added in the :ref:`News administration <newsModuleCreateNews>`
or :ref:`List <listAddFirstRecord>` module.

By default there are three types of news records:

*  Standard news: These contain the content directly as text or content
   elements
*  Internal links: The content is stored on a standard typo3 page
*  External page: The content is found outside of the news installation

Standard news
=============

In most projects when you create a new news record it will be automatically
a standard news record. To confirm this check that the field :guilabel:`Type`
is set to :guilabel:`News`. Standard news should usually have a text
in the field :guilabel:`Text` field, some content elements in the
:guilabel:`Content elements` tab and or some media in the :guilabel:`Media`
tab.

News as internal link
=====================

In some projects the text of the news itself is stored on a standard page. The
news can still be listed in the list view of the news plugin.

Click the button :guilabel:`Create new news record` on the top of the
:ref:`News Administration <newsModuleCreateNews>` module. Then switch the select filed
:guilabel:`Type` to :guilabel:`Internal link`. Fill in the title field and
other required or desired fields.

Then click the button to edit the :guilabel:`Internal link`.

When you are done, click the :guilabel:`Save` button. Depending on the settings
of your project you might have to empty the cache
before the news will be displayed in the list views.


News as external page
=====================

In some projects the text of the news itself is stored on an external page. The
news can still be listed in the list view of the news plugin.

Creating an external page works just like an internal link. However you chose
the type :guilabel:`External page` and enter the external link in the field
:guilabel:`Link to External URL`.


.. only:: html

   .. contents::
        :local:
        :depth: 2

News record
===========

The visible fields depend on

*  The news type (see above)
*  Your user permissions
*  The configuration of your project
*  Third party extensions if applicable

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:
   :Description:
         Description:
 - :Field:
         Header
   :Description:
         Required! Title of a news record
 - :Field:
         Top news
   :Description:
         News records can be marked as top news if it is an important one. This field can be used for filtering and ordering.
 - :Field:
         Type
   :Description:
         A news record can belong to one of the following types:

         - "News": Default news record
         - "Internal Page": The news record is linked to a regular page.
         - "External Page": The news record is linked to an external URL.

         Some fields are only available for a special type.
 - :Field:
         Teaser
   :Description:
         A teaser text which is shown in the list view and explains the content
         of the news record in some sentences. Depending on the configuration
         it is possible that not complete text is shown in the frontend but
         just a part of it.
 - :Field:
         Author name
   :Description:
         Name of the author
 - :Field:
         Author email
   :Description:
         Email address of the author
 - :Field:
         Date & Time
   :Description:
         Date of the news record
 - :Field:
         Archive
   :Description:
         Depending on the configuration this date is used to define if the
         record is still shown. It is e.g. possible to show only records with
         an archive date in the past or in the future.
 - :Field:
         Text
   :Description:
         Main content of the news record.
 - :Field:
         Rich text editor disable
   :Description:
         If set, the RTE is disabled and the field "Text" is shown as plain textarea.
 - :Field:
         Content elements
   :Description:
         Add content elements to a news records. This field can be
         hidden by disabling the setting in :ref:`extensionConfiguration`.
 - :Field:
         Link to this Page
   :Description:
         Link to a regular page. This field is only shown with the type
         "Internal Page".
 - :Field:
         Link to External URL
   :Description:
         Link to an external url. This field is only shown with the type
         "External Page".
 - :Field:
         Categories
   :Description:
         Selection of categories the news record belongs to.
 - :Field:
         Tags
   :Description:
         Add tags to the news record. Use the suggest wizard to search for existing tags and to insert new tags.
         The pid can be set in the Extension Configuration :ref:`extensionConfigurationTagPid` or in :ref:`TsConfig <tsconfigTagPid>`.
 - :Field:
         Related News
   :Description:
         Define news records which are related to the current one.
 - :Field:
         Keywords
   :Description:
         Set keywords of this news record, separated with a comma (',')
 - :Field:
         Description
   :Description:
         Define an additional description
 - :Field:
         Alternative title
   :Description:
         If used, this field is used instead of the default tile.
 - :Field:
         Speaking URL path segment
   :Description:
         This field can be used for various scenarios, e.g. in your realurl configuration to set up the URL to the news record.


Relations
^^^^^^^^^

.. only:: html

   .. contents::
        :local:
        :depth: 2


Media file
""""""""""
This relation handles all media files you want to attach to a news record.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:
   :Description:
         Description:
 - :Field:
         Show in list views
   :Description:
         If set, this media element will be rendered in the list view (or where it is desired by changing the templates).
 - :Field:
         Title
   :Description:
         Additional title
 - :Field:
         Alternative Title
   :Description:
         The alternative title is e.g. used for the alt attribute of images
 - :Field:
         Link
   :Description:
         Additional link
 - :Field:
         Caption
   :Description:
         Caption


Video & audio file
""""""""""""""""""
TODO

Related files
"""""""""""""
This relation handles related files which are handled by FAL (File Abstraction Layer).

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:
   :Description:
         Description:
 - :Field:
         Title
   :Description:
         Additional title
 - :Field:
         Description
   :Description:
         Additional description



Related links
"""""""""""""
This relation handles links to any kind of URLs.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:
   :Description:
         Description:
 - :Field:
         URL
   :Description:
         Required! URL can be a page id, email address, external URL, ...
 - :Field:
         Title
   :Description:
         Additional title
 - :Field:
         Description
   :Description:
         Additional description
