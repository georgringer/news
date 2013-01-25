.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Short Introduction
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

ViewHelpers are used to add logic inside the view.
There basic things like if/else conditions, loops and so on. The system extension fluid has the most important ViewHelpers already included.

To be able to use a ViewHelper in your template, you need to follow always the same structure which is: ::

	<f:fo>bar</f:fo>

This would call the ViewHelper fo of the namespace **f** which stands for fluid.
If you want to use ViewHelpers from other extensions you need to add the namespace
declaration at the beginning of the template. The namespace declaration for the news extension is: ::

	{namespace n=Tx_News_ViewHelpers}


Now you can use a ViewHelper of news with a code like ::

	<n:headerData><!-- some comment --></n:headerData

If you want to know what a ViewHelper does, it is very easy to find the related PHP class by looking at the namespace and the name of the ViewHelper.
Having e.g. **Tx_News_ViewHelpers** and **headerData** you will find the class at **news\\Classes\ViewHelpers\\HeaderDataViewHelper.php**.

The most of awesome thing is that you can use ViewHelpers of any extension in any other template by just adding another namespace delcaration like ::
    {namespace something=Tx_AnotherExtension_ViewHelpers}
and call the ViewHelper like
    <something:NameOfTheViewHelper />


Root directory
""""""""""""""""""""""""


CategoryChildrenViewHelper
******************************************
ViewHelper to get children of a category

ExcludeDisplayedNewsViewHelper
********************************
Exclude already rendered news items from other views.

**Requirements:**

- The ViewHelper needs to be added to the templated, e.g. list or detail view
- The setting "exclude already displayed news" needs to checked in the plugin which should not show these news items

.. t3-field-list-table::
 :header-rows: 1

 - :Argument:
         newsItem

   :Description:
         news record which should not be displayed in other views on the same page

**Example** ::

    <n:excludeDisplayedNews newsItem="{newsItem}" />

HeaderDataViewHelper
******************************************

ViewHelper to render data in the <head> section of the page

**Example** which renders a link to a possible RSS fedd  ::

    <n:headerData>
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<f:uri.page additionalParams="{type:9818}"/>" />
    </n:headerData>

IncludeFileViewHelper
******************************************

ViewHelper to include a css/js file

.. t3-field-list-table::
 :header-rows: 1

 - :Argument:
         path

   :Description:
         Path to the the file

 - :Argument:
         compress

   :Description:
         If file should get compressed or not

**Example** ::

	<n:includeFile path="fileadmin/fo.css" />



LinkViewHelper
******************************************

ViewHelper to render links from news records to detail view

MediaFactoryViewHelper
******************************************

ViewHelper to render media elements

MetaTagViewHelper
******************************************

ViewHelper to render meta tags

ObjectViewHelper
******************************************

ViewHelper to render extended objects

PaginateBodytextViewHelper
******************************************

Paginate the bodytext which is very useful for longer texts or to increase traffic.

TitleTagViewHelper
******************************************

ViewHelper to render a title tag

Be
""""""""""""""""""""""""

Used in the backend module "Administration"

ClickmenuViewHelper
******************************************

Generate a click menu für the backend module.

MultiEditLinkViewHelper
******************************************

Generate the link to the view of editing multiple news records at one time

Be\\Buttons
""""""""""""""""""""""""

IconForRecordViewHelper
******************************************

Displays the icon of a record using the core internal sprite

IconViewHelper
******************************************

Fixed copy of the ViewHelper from sysext fluid which renders a sprite icon

Format
""""""""""""""""""""""""

DamViewHelper
******************************************

ViewHelper to get full dam record

DateViewHelper
******************************************

ViewHelper to format a date, using strftime, also date is possible

FileDownloadViewHelper
******************************************

ViewHelper to download a file using TS to make it fully configurable, e.g. using secure downloads.

FileSizeViewHelper
******************************************

ViewHelper to render the filesize of a given file

HscViewHelper
******************************************

ViewHelper for htmlspecialchars, especially needed for the RSS feed template

HtmlentitiesDecodeViewHelper
******************************************

ViewHelper for html_entity_decode

NothingViewHelper
******************************************

ViewHelper to render other VieHelpers which don't print out any actual content

StriptagsViewHelper
******************************************

ViewHelper for strip_tags

Social
""""""""""""""""""""""""

DisqusViewHelper
******************************************

ViewHelper to add disqus thread

GooglePlusViewHelper
******************************************

ViewHelper to add a google+ button

TwitterViewHelper
******************************************

ViewHelper to add a twitter button

Facebook_CommentViewHelper
******************************************

ViewHelper to comment content

Facebook_LikeViewHelper
******************************************

ViewHelper to add a like button

Facebook_ShareViewHelper
******************************************

ViewHelper to share content

Widget
""""""""""""""""""""""""

PaginateViewHelper
******************************************

Render a pagination