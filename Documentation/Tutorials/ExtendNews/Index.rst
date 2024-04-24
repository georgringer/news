.. _tutorialExtendNews:

===========
Extend News
===========

.. card-grid::
   :columns: 1
   :columns-md: 2
   :gap: 4
   :class: pb-4
   :card-height: 100

   .. card:: :ref:`Custom extension <ext-based-on-news>`

      All changes listed on this page should be done in a custom
      extension that extends EXT:news. Learn how to set it up.

   .. card:: :ref:`Extend FlexForms <extendFlexforms>`

      Influence the fields available in the plugins backend FlexForm.

   .. card:: :ref:`Events <eventsTutorial>`

      Extend the code of EXT:news by using PSR-14 Events.

   .. card:: :ref:`Hooks <hooks>`

      If there is no PSR-14 Event, try using a hook instead.

   .. card:: :ref:`Demands <demands>`

      Learn how to filter and sort the displayed news using demands.

   .. card:: :ref:`Data processing <dataProcessing>`

      Display news in menus using data processing

   .. card:: :ref:`Add custom fields <proxyClassGenerator>`

      Learn how to add custom fields to news records and extend the
      :php:`News` model using the ProxyClassGenerator

   .. card:: :ref:`Custom news types <addCustomType>`

      Add additional custom news types

.. toctree::
   :maxdepth: 5
   :titlesonly:
   :hidden:

   ExtensionBasedOnNews/Index
   ExtendFlexforms/Index
   Events/Index
   Hooks/Index
   Demands/Index
   DataProcessing/Index
   ProxyClassGenerator/Index
   AddCustomType/Index
