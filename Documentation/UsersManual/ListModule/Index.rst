.. include:: /Includes.rst.txt

.. _listModule:

===========
List module
===========

You can also use the :guilabel:`List` module to create and edit news records.
The choice depends on what modules you have permissions to use, your personal
experience or choice.

The :guilabel:`List` module is more powerful then then the
:ref:`News Administration <newsAdminModule>` module in some aspects,
however it might be less tailored to the specific use case. This depends on
how the project was set up.

.. todo: insert screenshot here.

Create a news storage folder
============================

News records are usually stored in pages of the type :guilabel:`folder`. A
folder itself cannot be displayed in the frontend. Its content can however
be displayed on other pages through a plugin or TypoScript configuration.

Create a page of type folder by dragging a the folder symbol on top of the page
tree into the desired position. Then give it a name and enable the folder in
the context menu.

The folder can be displayed with a certain symbol such that folders containing
news can be easily recognized.

Open the page properties by choosing :guilabel:`Edit` from the context menu.

Then go to the field :guilabel:`Behaviour > Contains Plugin` and chose
:guilabel:`News`. After saving the folder record the symbol of the folder
changes. If this field is not visible to you, you might lack permissions. In
this case an administrator needs to set the field.

.. _listAddFirstRecord:

Add the first news record to a new folder
=========================================

When there is no news record found in the folder yet, the table
:guilabel:`News` is not visible yet. In that case click the plus button
:guilabel:`Create a new record` on the top left of the list module area.

Chose :guilabel:`News system > News` from the list. Edit and save the news
record as usual. The same strategy can be used to add
:guilabel:`News system > Tags` and :guilabel:`News system > Tags`.


Add subsequent news records to a folder
=======================================

When a folder contains at least one news record it displays a table
called :guilabel:`News` in the list module.

At the top of this table you can find the :guilabel:`New record` button.
Click this button to create a news record directly.

Edit a news record
==================

At the right of each listed news record you can edit, hide / unhide or
delete a record by clicking the corresponding button. By clicking on the
icon of the news you can also find a context menu with more options.

Download news as CSV or JSON
============================

Starting with TYPO3 version 11 you can conveniently download all or a
selection of news records to a comma-separated (CSV) file format that
can be easily imported into excel. Click the :guilabel:`Download` button
on the top right of the news table in the list module, make your choice
click :guilabel:`Download`.

Mass editing
============

Mass editing can come handy when you want to change many news records at
once. The process differs between TYPO3 11.5 and older versions however.

Read the chapter on :ref:`mass editing <t3editors:mass-editing>` in the T
YPO3 editors manual and chose the version of TYPO3 you are using from
the top left version chooser.
