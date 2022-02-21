.. include:: /Includes.rst.txt

.. highlight:: html

.. _viewHelpersReference:

=====================
ViewHelpers reference
=====================

In order to use the ViewHelpers provided by this extension, you need to include
them in the :html:`<html>` tag of your template or partial:

.. code-block:: html

   <html xmlns:n="http://typo3.org/ns/GeorgRinger/News/ViewHelpers"
         xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
         data-namespace-typo3-fluid="true">

You can find all available ViewHelpers in the folder
:file:`EXT:news/Classes/ViewHelpers`.

.. todo: Automatically document the ViewHelpers


All ViewHelpers
===============

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
   PaginateBodytextViewHelper
   TargetLinkViewHelper
   TitleTagViewHelper

   Format/NothingViewHelper

   Widget/PaginateViewHelper

