.. include:: /Includes.rst.txt

.. _ext-based-on-news:

Extensions based on EXT:news
============================

If you are using news records but need custom configuration and custom settings, you should think of creating a separate extension. This is really simple, just take a look at the following example.

.. only:: html

   .. contents::
      :local:
      :depth: 1

Setup of the extension
----------------------

As a demonstration, a new extension with the extension key ``news_filter`` will be created. The following files and its content is required.

ext_emconf.php
^^^^^^^^^^^^^^

This file containts the basic information about its extension like name, version, author...

.. code-block:: php

   <?php

   $EM_CONF[$_EXTKEY] = [
      'title' => 'News Filter',
      'description' => 'News filtering',
      'category' => 'fe',
      'author' => 'John Doe',
      'author_email' => 'john@doe.net',
      'shy' => '',
      'dependencies' => '',
      'conflicts' => '',
      'priority' => '',
      'module' => '',
      'state' => 'stable',
      'internal' => '',
      'uploadfolder' => 0,
      'modify_tables' => '',
      'clearCacheOnLoad' => 1,
      'lockType' => '',
      'author_company' => '',
      'version' => '1.0.0',
      'constraints' => [
         'depends' => [
            'typo3' => '7.6.0-8.9.99',
         ],
         'conflicts' => [],
         'suggests' => [],
      ],
      'suggests' => [],
   ];

ext_localconf.php
^^^^^^^^^^^^^^^^^

Create a basic plugin with one action called ``list``.

.. code-block:: php

   <?php
   defined('TYPO3_MODE') or die();

   $boot = function () {
      \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
         'GeorgRinger.news_filter',
         'Filter',
         [
            'Filter' => 'list',
         ]
      );
   };

   $boot();
   unset($boot);

Configuration/TCA/Overrides/tt_content.php
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Register the plugin:

.. code-block:: php

   <?php
   defined('TYPO3_MODE') or die();

   /***************
    * Plugin
    */
   \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
      'news_filter',
      'Filter',
      'Some demo'
   );

.. _extension_custom_controller:

Classes/Controller/FilterController.php
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Create a basic controller with the mentioned action.

.. code-block:: php

   <?php

   namespace GeorgRinger\NewsFilter\Controller;

   use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
   use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

   class FilterController extends ActionController
   {

      public function listAction()
      {
         $demand = $this->createDemandObject();
         $this->view->assignMultiple([
            'news' => $this->newsRepository->findDemanded($demand)
         ]);
      }

      /**
       * @return NewsDemand
       */
      protected function createDemandObject()
      {
         $demand = $this->objectManager->get(NewsDemand::class);
         $demand->setLimit(10);

         return $demand;
      }

      /**
       * @var \GeorgRinger\News\Domain\Repository\NewsRepository
       * @inject
       */
      protected $newsRepository;
   }

Resources/Private/Templates/Filter/List.html
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Create the template:

.. code-block:: html

   <f:if condition="{news}">
      <f:then>
         <div class="row">
            <f:for each="{news}" as="newsItem">
               <div class="col-md-3">
                  <h5>{newsItem.title}</h5>
               </div>
            </f:for>
         </div>
      </f:then>
      <f:else>
         <div class="alert alert-danger">No news found</div>
      </f:else>
   </f:if>

Setup
-----

After enabling the extension in the Extension Manager and creating a plugin "Filter" on a page, you will see up to 10 news records of your system.

.. hint::

   If your installation is based on composer, you need to add the classes to the PSR-4 section.

   .. code-block:: js

      "autoload": {
         "psr-4": {
            "GeorgRinger\\NewsFilter\\": "typo3conf/ext/news_filter/Classes/"
         }
      }

Configuration
-------------

There are multiple ways how to configure which news records should be shown. The fastest way is to hardcode the configuration.

Hardcode it
^^^^^^^^^^^

By modifying the controller with the following code, you will change the output to show only those news records which fulfill the following requirements:

- The pid is ``123``
- The author is ``John``
- The id of the records is neither ``12`` nor ``45``.

.. code-block:: php

    /**
     * @return NewsDemand
     */
    protected function createDemandObject()
    {
        $demand = $this->objectManager->get(NewsDemand::class);
        $demand->setStoragePage('123');
        $demand->setAuthor('John');
        $demand->setHideIdList('12,45');

        return $demand;
    }

Use FlexForms
^^^^^^^^^^^^^

Flexforms are a powerful tool to let editors configure plugins.

Configuration/TCA/Overrides/tt_content.php
""""""""""""""""""""""""""""""""""""""""""

Exchange the existing file with the following content.

.. code-block:: php

   <?php
   defined('TYPO3_MODE') or die();

   /***************
    * Plugin
    */
   \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
      'news_filter',
      'Filter',
      'Some demo'
   );

   $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['newsfilter_filter'] = 'recursive,select_key,pages';
   $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['newsfilter_filter'] = 'pi_flexform';
   \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('newsfilter_filter',
      'FILE:EXT:news_filter/Configuration/FlexForms/flexform_news_filter.xml');

Configuration/FlexForms/flexform_news_filter.xml
""""""""""""""""""""""""""""""""""""""""""""""""

The syntax of ``FlexForms`` is identical to the one of ``TCA`` with the only difference that it is written in XML instead of PHP.

.. code-block:: xml

   <T3DataStructure>
      <sheets>
         <sDEF>
            <ROOT>
               <TCEforms>
                  <sheetTitle>LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:flexforms_tab.settings
                  </sheetTitle>
               </TCEforms>
               <type>array</type>
               <el>
                  <settings.startingpoint>
                     <TCEforms>
                        <label>LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.startingpoint</label>
                        <config>
                           <type>group</type>
                           <internal_type>db</internal_type>
                           <allowed>pages</allowed>
                           <size>3</size>
                           <maxitems>50</maxitems>
                           <minitems>0</minitems>
                           <wizards>
                              <suggest>
                                 <type>suggest</type>
                                 <default>
                                    <searchWholePhrase>1</searchWholePhrase>
                                 </default>
                              </suggest>
                           </wizards>
                        </config>
                     </TCEforms>
                  </settings.startingpoint>
               </el>
            </ROOT>
         </sDEF>
      </sheets>
   </T3DataStructure>

Important is that each element's name is prepended with ``settings.``.

.. hint::
   Take a look at the FlexForms of the news extension for inspiration. You can even just copy & paste settings from there.
   The file can be found at ``EXT:news/Configuration/FlexForms/flexform_news.xml``.


Classes/Controller/FilterController.php
"""""""""""""""""""""""""""""""""""""""

Adopt the controller to use the settings instead of the hardcoded ones.

.. code-block:: php

    /**
     * @return NewsDemand
     */
    protected function createDemandObject()
    {
        $demand = $this->objectManager->get(NewsDemand::class);
        // Because of the naming "<settings.startingpoint>", you can use $this->settings['startingpoint']
        $demand->setStoragePage($this->settings['startingpoint']);
        $demand->setLimit(10);

        return $demand;
    }
