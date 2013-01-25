.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt
.. include:: Images.txt


About News & category records
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The records can be created on any page, however a sysfolder is often
preferred.


Category
""""""""

Categories are not required but can be used to structure news records
in the frontend (e.g. show only news records which belong to category
A and B).

|img-6|

The following table describes the main fields of a category record.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:


 - :Field:
         Title

   :Description:
         Title of the category and required.


 - :Field:
         Parent category

   :Description:
         The parent category can be used to build a category tree. Therefore
         select the parent of the current category. If nothing selected, the
         category is used as a root category.


 - :Field:
         Image

   :Description:
         Optional image of the category which can be shown next to a category
         title.


 - :Field:
         Description

   :Description:
         Description of the category


 - :Field:
         Single-view page for news from this category

   :Description:
         If a page is defined, it is used as page for displaying the news
         record. If a news record belongs to more than one category, only the 1
         :sup:`st` category is checked for this field.


 - :Field:
         News category shortcut

   :Description:
         Optional link of a news category


News
""""

The following table describes the main fields of a news record.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:


 - :Field:
         Header

   :Description:
         Header of a news record. It is a required field.


 - :Field:
         Top news

   :Description:
         News records can be marked as top news if it is an important one.


 - :Field:
         Type

   :Description:
         A news record can belong to one of the following types:

         - “News”: Default news record

         - “Internal Page”: The news record is linked to a regular page

         - “External Page”: The news record is linked to an external page

         Some fields are depending on the selection.


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
         Author Email

   :Description:
         Email address of the author


 - :Field:
         Date & Time

   :Description:
         Date of the news record


 - :Field:
         Archive date

   :Description:
         Depending on the configuration this date is used to define if the
         record is still shown. It is e.g. possible to show only records with
         an archive date in the past or in the future.


 - :Field:
         Text

   :Description:
         Main content of the news record. This field is only shown with the
         type “News”.


 - :Field:
         Content Elements

   :Description:
         Add normal content elements to a news records. This field can be
         hidden by disabling the setting in extension manager's settings.


 - :Field:
         Link to this Page

   :Description:
         Link to a regular page. This field is only shown with the type
         “Internal Page”.


 - :Field:
         Link to External URL

   :Description:
         Link to an external url. This field is only shown with the type
         “External Page”.


 - :Field:
         Categories

   :Description:
         Selection of categories the news record belongs to.


 - :Field:
         Related News

   :Description:
         Define news records which are related to the current one.


 - :Field:
         Keywords

   :Description:
         Set keywords of this news record, separated with a comma (',')


 - :Field:
         Media Element

   :Description:
         Media elements belonging to this news record. Those are described
         below.


 - :Field:
         Related Files

   :Description:
         Related files, described below too.


 - :Field:
         Related Links

   :Description:
         Related links, described below too.


Media element
~~~~~~~~~~~~~

A media element can be one of the following types:

**Image**

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:


 - :Field:
         Show in preview views

   :Description:
         It is possible to decide which media can be shown in the list view. If
         not checked, it will only be displayed in the single view.


 - :Field:
         Image

   :Description:
         The image file


 - :Field:
         Width

   :Description:
         Width of image


 - :Field:
         Height

   :Description:
         Height of image


 - :Field:
         Caption

   :Description:
         Caption


 - :Field:
         Title Text

   :Description:
         Text to describe the image which is used for the title attribute


 - :Field:
         Alternative Text

   :Description:
         Further description which is used for the alt attribute


**Video & Audio**

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:


 - :Field:
         Show in preview views

   :Description:
         It is possible to decide which media can be shown in the list view. If
         not checked, it will only be displayed in the single view.


 - :Field:
         Video & Audio URL

   :Description:
         Set the url to the media element. This can either be a relative path
         like fileadmin/song.mp3 or an url like
         `http://www.example.com/song.mp3 <http://www.example.com/song.mp3>`_


 - :Field:
         Caption

   :Description:
         Caption


**HTML**

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:


 - :Field:
         Show in preview views

   :Description:
         It is possible to decide which media can be shown in the list view. If
         not checked, it will only be displayed in the single view.


 - :Field:
         Caption

   :Description:
         Caption


Related Files
~~~~~~~~~~~~~

The following table describes the main fields of related files which
can be inserted in a news record.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:


 - :Field:
         File

   :Description:
         Related file


 - :Field:
         Title

   :Description:
         Title of the file


 - :Field:
         Description

   :Description:
         Further optional description


Related Links
~~~~~~~~~~~~~

The following table describes the main fields of related links which
can be inserted in a news record.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:


 - :Field:
         URL

   :Description:
         Url of the link


 - :Field:
         Title

   :Description:
         Title of the link


 - :Field:
         Description

   :Description:
         Further optional description

