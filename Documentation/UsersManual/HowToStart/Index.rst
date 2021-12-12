.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt

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

#. :guilabel:`List` view: Use the icon in the topbar :guilabel:`Create new
   record` and search for :guilabel:`News
   system` and its records. You should see :guilabel:`News`,
   :guilabel:`News category` and :guilabel:`News Tag`.

   #. Click on :guilabel:`News category` to create a new category. Insert as many
      categories as you want and use the field :guilabel:`Parent Category` to build up
      a category tree.

   #. Click on :guilabel:`News` to create a new news record. Fill as many fields you
      want, a required one is only the header.

#. **Administration module:** Use the custom administration module which can be found
   in the module menu at :guilabel:`Web > News Administration`

.. hint::

   More information about the records can be found here: :ref:`news record <recordNews>`, :ref:`category record <recordCategory>`, :ref:`tag record <recordTag>`.


.. _howToStartAddPlugin:

Add a plugin to a page
----------------------
A plugin is used to render a defined selection of records in the frontend.
Follow these steps to add a plugin respectively for detail- and list-view to a page:

Detail page
^^^^^^^^^^^

#. Create a new page named "Detail" which will be used to show the full news
   record. Insert the plugin :guilabel:`News system`. The 2 :sup:`nd` tab :guilabel:`Plugin`
   is used to configure the extension.

#. Add a new content element and select the entry :guilabel:`News system`

#. Switch to the tab :guilabel:`Plugin` where you can define the plugins settings.
   The most important settings are :guilabel:`What to display` and :guilabel:`Startingpoint`.

   #. Change the 1 :sup:`st` select box :guilabel:`What to display` to :guilabel:`Detail view`.

   #. Save the plugin.
   
List page
^^^^^^^^^

#. Create a new page named "List" (or however you want to name it) and insert
   the plugin :guilabel:`News system` there again.

   #. The selected view is already :guilabel:`List view`. This is fine.

   #. Fill the field :guilabel:`Startingpoint` by selecting the :guilabel:`sysfolder` you created
      in the beginning of the tutorial.

   #. Switch to the 2 :sup:`nd` tab :guilabel:`Additional` and fill the field :guilabel:`PageId
      for single news display` with the page you just created before.

   #. Save the plugin.

Adopt the frontend
------------------
Load the List page in the frontend and you should see the news records
as output. A click on the title should show the news record on the
detail page.
