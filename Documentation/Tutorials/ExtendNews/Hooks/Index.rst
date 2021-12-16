.. include:: /Includes.rst.txt

.. _hooks:

=====
Hooks
=====

Several hooks can be used to modify the behaviour of EXT:news.

.. only:: html

   .. contents::
      :local:
      :depth: 1

Hooks
-----

.. _hooks_example_findDemanded:

Domain/Repository/AbstractDemandedRepository.php findDemanded
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This hook is very powerful, as it allows to modify the query used to fetch the news records.

Example
"""""""
This examples modifies the query and adds a constraint that only news records are shown which contain the word *yeah*.


First, register your implementation in the file ``ext_localconf.php``:

.. code-block:: php

   <?php
   defined('TYPO3_MODE') or die();

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'][$_EXTKEY]
      = 'YourVendor\\Extkey\\Hooks\\Repository->modify';

Now create the file ``Classes/Hooks/Repository.php``:

.. code-block:: php

   <?php

   namespace YourVendor\Extkey\Hooks;

   use TYPO3\CMS\Core\Utility\GeneralUtility;
   use \GeorgRinger\News\Domain\Repository\NewsRepository;

   class Repository {

      public function modify(array $params, NewsRepository $newsRepository) {
         $this->updateConstraints($params['demand'], $params['respectEnableFields'], $params['query'], $params['constraints']);
      }

      /**
       * @param \GeorgRinger\News\Domain\Model\Dto\NewsDemand $demand
       * @param bool $respectEnableFields
       * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
       * @param array $constraints
       */
      protected function updateConstraints($demand, $respectEnableFields, \TYPO3\CMS\Extbase\Persistence\QueryInterface $query, array &$constraints) {
         $subject = 'yeah';
         $constraints[] = $query->like('title', '%' . $subject . '%');
      }
   }

.. hint:: Please change the vendor and extension key to your real life code.

Controller/NewsController overrideSettings
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Use this hook to change the final settings which are for building queries, for the template, ...

Example
"""""""
This examples modifies the settings by changing the category selection.

First, register your implementation in the file ``ext_localconf.php``:

.. code-block:: php

   <?php
   defined('TYPO3_MODE') or die();

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Controller/NewsController.php']['overrideSettings'][$_EXTKEY]
      = 'YourVendor\\Extkey\\Hooks\\NewsControllerSettings->modify';

Now create the file ``Classes/Hooks/NewsControllerSettings.php``:

.. code-block:: php

   <?php

   namespace YourVendor\Extkey\Hooks;

   class NewsControllerSettings {

      public function modify(array $params) {
         $settings = $params['originalSettings'];
         $settings['categories'] = '2,3';

         return $settings;
      }
   }

.. hint:: Please change the vendor and extension key to your real life code.


