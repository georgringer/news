.. include:: /Includes.rst.txt

.. _quickContent:
.. _howToStart:

===========================
Create some initial content
===========================

.. _quickPageStructure:

Recommended page structure
==========================

Create at least the following pages:

*  "Home": Root page of the site, containing the root TypoScript template record and
   the start page content: Normal page.
*  "News Storage": A folder to store the news in: Folder

Usually you will also need

*  "News list": A list page to display all news on: Normal page
*  "News display": A single page to display the news detail view on:
   Normal page, hidden in menu

Your page tree could, for example look like that:

.. code-block:: none

   Home
   ├── Some page
   ├── ...
   ├── News list
   │  └── News display
   ├── ...
   └── Storage
      ├── Other storage
      ├── ...
      └── News Storage


.. _quickNewsRecords:
.. _howToStartCreateRecords:

Create news records
===================

Before any news record can be shown in the frontend those need to be
created.

.. include:: /Images/AutomaticScreenshots/AddNewsInAdminModule.rst.txt

#. Go to the module :guilabel:`Web > News administration`

#. Go to the "News Storage" Folder that you created in the first step.

#. Use the icon in the topbar :guilabel:`Create new news record`.

#. Fill out all desired fields and click :guilabel:`Save`.

More information about the records can be found here:
:ref:`news record <recordNews>`, :ref:`category record <recordCategory>`,
:ref:`tag record <recordTag>`.


.. _howToStartAddPlugin:

Add plugins: display the news in the frontend
=============================================

A plugin is used to render a defined selection of records in the frontend.
Follow these steps to add a plugin respectively for detail and list view to
a page:

Detail page
-----------

#. Go to module :guilabel:`Web > Page` and to the previously created page
   "News display".

#. Add a new content element and select the entry
   :guilabel:`Plugins > News system`.

#. Switch to the tab :guilabel:`Plugin` where you can define the plugins settings.
   The most important settings are :guilabel:`What to display` and :guilabel:`Startingpoint`.

   #. Change the 1 :sup:`st` select box :guilabel:`What to display` to :guilabel:`Detail view`.

   #. Save the plugin.

List page
---------

.. include:: /Images/AutomaticScreenshots/Plugin.rst.txt

#. Go to module :guilabel:`Web > Page` and to the previously created page
   "News list".

#. Add a new content element and select the entry
   :guilabel:`Plugins > News system`.

#. Switch to the tab :guilabel:`Plugin` where you can define the plugins settings.
   The most important settings are :guilabel:`What to display` and :guilabel:`Startingpoint`.

   #. The selected view is already :guilabel:`List view`. This is fine.

   #. Fill the field :guilabel:`Startingpoint` by selecting the :guilabel:`sysfolder` you created
      in the beginning of the tutorial.

   #. Switch to tab :guilabel:`Additional` and fill the field :guilabel:`PageId
      for single news display` with the page you just created before.

      .. include:: /Images/AutomaticScreenshots/PluginAdditional.rst.txt

   #. Save the plugin.

Read more about the plugin configuration in chapter :ref:`Plugin <plugin>`.

Have a look at the frontend
===========================

Load the "News list" page in the frontend and you should now see the news records
as output. A click on the title should show the news record on the
detail page. You want to change the way the records are displayed? Have a look
at the chapter :ref:`Templating <quickTemplating>`
