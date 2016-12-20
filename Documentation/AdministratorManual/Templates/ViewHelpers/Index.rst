.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


ViewHelpers of EXT:news
=======================

ViewHelpers are used to add logic inside the view.
There basic things like if/else conditions, loops and so on. The system extension fluid has the most important ViewHelpers already included.

To be able to use a ViewHelper in your template, you need to follow always the same structure which is:

.. code-block:: html

	<f:fo>bar</f:fo>

This would call the ViewHelper fo of the namespace **f** which stands for fluid.
If you want to use ViewHelpers from other extensions you need to add the namespace
declaration at the beginning of the template. The namespace declaration for the news extension is:

.. code-block:: html

	{namespace n=GeorgRinger\News\ViewHelpers}


Now you can use a ViewHelper of news with a code like:

.. code-block:: html

	<n:headerData><!-- some comment --></n:headerData>

If you want to know what a ViewHelper does, it is very easy to find the related PHP class by looking at the namespace and the name of the ViewHelper.
Having e.g. ``GeorgRinger\News\ViewHelpers`` and ``headerData`` you will find the class at ``news\\Classes\ViewHelpers\\HeaderDataViewHelper.php``.

The most of awesome thing is that you can use ViewHelpers of any extension in any other template by just adding another namespace declaration like:

.. code-block:: html

    {namespace something=Tx_AnotherExtension_ViewHelpers}

and call the ViewHelper like

.. code-block:: html

    <something:NameOfTheViewHelper />

All ViewHelpers
---------------

.. toctree::
   :maxdepth: 5
   :titlesonly:
   :glob:

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

   Format/NothingViewHelper

   Social/DisqusViewHelper
   Social/GooglePlusViewHelper
   Social/GravatarViewHelper
   Social/TwitterViewHelper

   Social/Facebook/CommentViewHelper
   Social/Facebook/LikeViewHelper
   Social/Facebook/ShareViewHelper

   Widget/PaginateViewHelper
