.. include:: /Includes.rst.txt

.. _demands:

=======
Demands
=======

A demand is a configuration object used by the repository to decide which
news records should be fetched.

The default demand class implementation for the frontend output of news
is :php:`GeorgRinger\News\Domain\Model\Dto\NewsDemand`. It has a couple of
useful properties that can be used to filter news records, for example
:php:`$categories`, :php:`$searchFields`, :php:`$author` and many more.

There is also the property :php:`$customSettings` an array of custom settings
by extensions. You should use your extension name as key in this array.

Demand objects can be changed in a couple of ways, see below:

URL Parameter
=============

The URL parameter :code:`overwriteDemand` can be used to override properties
of the demand.

You can set this parameter in a Fluid link (see
:ref:`Set overwriteDemand in Frontend <overwriteDemand-in-frontend>`) or via
TypoScript in a typolink (See
:ref:`TypoScript reference: typolink <t3tsref:typolink>`).

It would even be possible to configure a :ref:`LinkHandler <linkhandler>`
for this parameter.

.. important::

   The checkbox :guilabel:`Disable override demand` in the list plugin
   (Tab Additional) must **not** be set to allow overriding the properties.

   Additionally the TypoScript setting
   :ref:`settings.disableOverrideDemand <tsDisableOverrideDemand>` must be set to
   :code:`0`.

Via TypoScript
==============

TypoScript can be used to define a class containing a
:ref:`custom implementation <demand_custom>`
of the demand object. This can be achieved by the TypoScript setting
:ref:`settings.demandClass <tsDemandClass>`.

Custom controllers
==================

The demand object can be used in a custom controller used in an extension
extending EXT:news. Read more about using a demand object in a custom
controller:
:ref:`Extension based on EXT:news: FilterController.php <extension_custom_controller>`.

Hooks
=====

Several hooks can be used to influence the demand object and its usage.

Hook findDemanded
-----------------

The hook :code:`findDemanded` is very
powerful. It allows to modify the query used to fetch the news records depending
on values set in the demand object. You can find an example of how to use it in
the chapter :ref:`Hooks: Example findDemanded <hooks_example_findDemanded>`.

Hook createDemandObjectFromSettings
-----------------------------------

The hook :code:`createDemandObjectFromSettings`
(:php:`$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Controller/NewsController.php']['createDemandObjectFromSettings']`)
can be used to influence the settings of the demand object as it is used in
most standard actions in the :php:`NewsController`, such as the
:php:`listAction()`, :php:`selectedListAction()` and :php:`detailAction()`.

This hook can be used to insert settings from custom TypoScript or custom
FlexForm configuration into the demand object. (See also
:ref:`Extend FlexForms <extendFlexforms>`)


Events
======

Multiple events can change or use demand objects. For example the events of
the main actions in the :php:`NewsController`, for example
:php:`NewsListActionEvent` and :php:`NewsDetailActionEvent`. For more
information refer to the chapter :ref:`Events <events>`.

.. _demand_custom:

Custom demand class
===================

All custom frontend news demand classes must extend
:php:`GeorgRinger\News\Domain\Model\Dto\NewsDemand`. The demand object is
a simple configuration object. It should contain no business logic. For each
property there must be a setter and a getter.

Example:

.. code-block:: php

   <?php

   namespace Vendor\MyNews\Domain\Model\Dto;

   use \GeorgRinger\News\Domain\Model\Dto\NewsDemand;

   class MyNewsDemand extends NewsDemand {

      /**
      * @var string
      */
      protected $myCustomField = '';

      /**
      * Set myCustomField
      *
      * @param string $myCustomField
      * @return NewsDemand
      */
      public function setMyCustomField(string $myCustomField): NewsDemand
      {
         $this->myCustomField = $myCustomField;
         return $this;
      }

      /**
      * Get myCustomField
      *
      * @return string
      */
      public function getMyCustomField(): string
      {
         return $this->myCustomField;
      }
   }
