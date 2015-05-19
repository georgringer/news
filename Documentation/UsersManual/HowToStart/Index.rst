.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

.. _howToStart:

How to start
============
This walkthrough will help you to implement the extension news at your
TYPO3 site. The installation is covered :ref:`here <installation>`.

.. TODO: add screenshots

.. only:: html

.. contents::
        :local:
        :depth: 1

.. _howToStartCreateRecords:

Create the records
------------------
Before any news record can be shown in the frontend those need to be
created.

#. Create a new sysfolder and switch to the list module. (Of
   course you can also use an existing sysfolder or normal page).

#. **List view:** Use the icon in the topbar “Create new record” and search for “News
   system” and its records. You should see “News”, “News category” and
   “News Tag”.

   #. Click on “News category” to create a new category. Insert as many
      categories as you want and use the field “Parent Category” to build up
      a category tree.

   #. Click on “News” to create a new news record. Fill as many fields you
      want to field, a required one is only the header.

#. **Administration module:** Use the custom administration module which can be found
   in the module menu inside the section "*Web*".

.. hint::

   More information about the records can be found here: :ref:`news record <recordNews>`, :ref:`category record <recordCategory>`, :ref:`tag record <recordTag>`.


.. _howToStartAddPlugin:

Add a plugin to a page
----------------------
A plugin is used to render a defined selection of records in the frontend.
Follow this steps to add a plugin respectively for detail- and list-view to a page:

Detail page
^^^^^^^^^^^

#. Create a new page “Detail” which will be used to show the full news
   record. Insert the plugin “News system”. The 2 :sup:`nd` tab “Plugin”
   is used to configure the extension.

#. Add a new content element and select the entry “News system”

#. Switch to the tab “Plugin” where you can define the plugin's settings.
   The most important settings are “What to display” and “Startingpoint”.

   #. Change the 1 :sup:`st` select box “What to display” to “Detail view”.

   #. Save the plugin.
   
List page
^^^^^^^^^

#. Create a new page “List” (or however you want to name it) and insert
   the plugin “News system” there again.

   #. The selected view is already “List view”. This is fine.

   #. Fill the field “Startingpoint” by selecting the sysfolder you created
      in the beginning of the tutorial.

   #. Switch to the 2 :sup:`nd` tab “Additional” and fill the field “PageId
      for single news display” with the page you just created before.

   #. Save the plugin.

Adopt the frontend
------------------
Load the List page in the frontend and you should see the news records
as output. A click on the title should show the news record on the
detail page.
