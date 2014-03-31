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

The most of awesome thing is that you can use ViewHelpers of any extension in any other template by just adding another namespace declaration like ::
    {namespace something=Tx_AnotherExtension_ViewHelpers}
and call the ViewHelper like
    <something:NameOfTheViewHelper />


ViewHelpers of EXT:news
^^^^^^^^^^^^^^^^^^^^^^^^

.. toctree::
   :maxdepth: 5
   :titlesonly:
   :glob:

   CategoryChildrenViewHelper
   ExcludeDisplayedNewsViewHelper
   HeaderDataViewHelper
   IncludeFileViewHelper
   LinkViewHelper
   MediaFactoryViewHelper
   MetaTagViewHelper
   ObjectViewHelper
   PaginateBodytextViewHelper
   TargetLinkViewHelper
   TitleTagViewHelper

   Be/ClickmenuViewHelper
   Be/MultiEditLinkViewHelper

   Be/Buttons/IconForRecordViewHelper
   Be/Buttons/IconViewHelper

   Format/DateViewHelper
   Format/FileDownloadViewHelper
   Format/FileSizeViewHelper
   Format/HscViewHelper
   Format/HtmlentitiesDecodeViewHelper
   Format/NothingViewHelper
   Format/StriptagsViewHelper

   Social/DisqusViewHelper
   Social/GooglePlusViewHelper
   Social/GravatarViewHelper
   Social/TwitterViewHelper

   Social/Facebook/CommentViewHelper
   Social/Facebook/LikeViewHelper
   Social/Facebook/ShareViewHelper

   Widget/PaginateViewHelper
