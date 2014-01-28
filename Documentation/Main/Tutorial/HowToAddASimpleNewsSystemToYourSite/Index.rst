.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt
.. include:: Images.txt


How to add a simple news system to your site
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This walkthrough will help you to implement the extension news at your
TYPO3 installation.


Installation
""""""""""""

The extension needs to be installed as any other extension of TYPO3:

#. Switch to the module “Extension Manager”.

#. Get the extension

   #. **Get it from the Extension Manager:** Press the “Retrieve/Update”
      button and search for the extension key  *news* and import the
      extension from the repository.

   #. **Get it from typo3.org:** You can always get current version from
      `http://typo3.org/extensions/repository/view/news/current/
      <http://typo3.org/extensions/repository/view/news/current/>`_ by
      clicking on the link “Download compressed extension .T3X file”. Upload
      the file in the Extension Manager.

#. The Extension Manager offers some basic configuration which is
   explained in a later chapter. You can ignore those for the 1 :sup:`st`
   moment.


Latest version from git
~~~~~~~~~~~~~~~~~~~~~~~

You can get the latest version from git by using the git command::

   git clone git://git.typo3.org/TYPO3CMS/Extensions/news.git


Preparation: Include static TypoScript
""""""""""""""""""""""""""""""""""""""

Be aware that before any plugin can be rendered in the frontend it is
necessary to include the static TypoScript of news. This is very easy:

#. Switch to the template module and to the your template record.

#. Add the news extension to the “Include Static” list as shown in the
   screenshot

|img-8|


Create the records
""""""""""""""""""

Before any news record can be shown in the frontend those need to be
created.

#. Therefore create a new sysfolder and switch to the list module. (Of
   course you can also use an existing sysfolder or normal page).

#. Use the icon in the topbar “Create new record” and search for “News
   system” and its records. You should see “News”, “News category” and
   “News Tag”.

   #. Click on “News category” to create a new category. Insert as many
      categories as you want and use the field “Parent Category” to build up
      a category tree.

   #. Click on “News” to create a new news record. Fill as many fields you
      want to field, a required one is only the header.


Add a plugin to a page
""""""""""""""""""""""

Follow this steps to add a plugin to a page:

#. Create a new page “Detail” which will be used to show the full news
   record. Insert the plugin “News system”. The 2 :sup:`nd` tab “Plugin”
   is used to configure the extension.

#. Add a new content element and select the entry “News system”

|img-9|

#. Switch to the tab “Plugin” where you can define the plugin's settings.
   The most important settings are “What to display” and “Startingpoint”.

   #. Change the 1 :sup:`st` select box “What to display” to “Detail view”.

   #. Save the plugin.

#. Create a new page “List” (or however you want to name it) and insert
   the plugin “News system” there again.

   #. The selected view is already “List view”. This is fine.

   #. Fill the field “Startingpoint” by selecting the sysfolder you created
      in the beginning of the tutorial.

   #. Switch to the 2 :sup:`nd` tab “Additional” and fill the field “PageId
      for single news display” with the page you just created before.

   #. Save the plugin.


Check the frontend
""""""""""""""""""

Load the List page in the frontend and you should see the news records
as output. A click on the title should show the news record on the
detail page.

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Description:
         Description:


 - :Property:
         News fields shown in page module

   :Description:
         The page module can list news records at the bottom of the screen.

         |img-10|

         It is possible to create different list showing different fields of
         the news record. The syntax is very simple::

            <LIST-TITLE-1>=<FIELD1>,<FIELD2>;<LIST-TITLE-2>=<FIELD2>,<FIELD3>,<FIELD4>;

         You can use a normal string as title or a translatable string from a
         locallang file by using the common syntax
         LLL:path/to/file/locallang.xml:key.

         If no field list is set, no records are shown in the page module.


 - :Property:
         Category fields shown in page module

   :Description:
         Category records can be shown in the page module as news records. The
         syntax is the same as described above.


 - :Property:
         Define pid of tag records

   :Description:
         Set the Ids of pages which are used for getting the tags. The pid can also be set in TsConfig using tx_news.tagPid = 123


 - :Property:
         Hide Media Records

   :Description:
         If set, media records are not shown in the list module. They should be
         edited through the news record.


 - :Property:
         Hide File Records

   :Description:
         If set, file records are not shown in the list module. They should be
         edited through the news record.


 - :Property:
         Show tt\_news importer

   :Description:
         If set, the backend module to import tt\_news records is shown in the
         module menu. Furthermore tt\_news needs to be installed!


 - :Property:
         Prepend at copy

   :Description:
         If set and a news record is copied in the list module, the title gets
         appended with the string “(copy x)”.


 - :Property:
         Category restriction

   :Description:
         **[To be done] Not yet working**


 - :Property:
         Use content element relation

   :Description:
         If set, content elements can be added to news records.By using content
         elements an editor got more options to include content.


 - :Property:
         Enable manual sorting of news records

   :Description:
         If set, news records can be sorted manually in the list module.


 - :Property:
         Archive Date

   :Description:
         Switch the archive date from “Date” to “Date & Time”. This is needed
         if you want to make usage of minutes and hours for news items.


 - :Property:
         Show import module

   :Description:
         If set, the backend module to import records is shown.

