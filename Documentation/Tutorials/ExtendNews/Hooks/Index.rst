.. _events:

======
Events
======

Several events can be used to modify the behaviour of EXT:news.

.. contents::
      :local:
      :depth: 1

Events
-----

.. event_example_findDemanded:

\GeorgRinger\News\Event\ModifyDemandRepositoryEvent
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This event is very powerful, as it allows to modify the query used to fetch the news records.

Example
"""""""
This examples modifies the query and adds a constraint that only news records are shown which contain the word *yeah*.


First, register your implementation in the file `Configuration/Services.yaml`:

.. code-block:: yaml

  YourVendor\YourExtkey\EventListener\ModifyDemandRepositoryEventListener:
    tags:
      - name: event.listener
        identifier: 'eventnews-modifydemandrepository'
        event: GeorgRinger\News\Event\ModifyDemandRepositoryEvent

Now create the file ``Classes/EventListener/ModifyDemandRepositoryEventListener.php``:

.. code-block:: php

    <?php

    namespace YourVendor\YourExtkey\EventListener;

    use TYPO3\CMS\Core\Utility\GeneralUtility;
    use GeorgRinger\News\Event\ModifyDemandRepositoryEvent

    class ModifyDemandRepositoryEventListener
    {
        public function __invoke(ModifyDemandRepositoryEvent $event) {
            $constraints = $event->getConstraints();
            $constraints[] = $query->like('title', '%' . $subject . '%');
            $event->setConstraints($constraints);
        }
    }


Controller/NewsController overrideSettings
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Use this evebt to change the final settings which are for building queries, for the template, ...

Example
"""""""
This examples modifies the settings by changing the category selection.

First, register your implementation in the file ``ext_localconf.php``:

.. code-block:: php

   <?php
   defined('TYPO3') or die();

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Controller/NewsController.php']['overrideSettings'][$_EXTKEY]
      = \YourVendor\Extkey\Hooks\NewsControllerSettings::class . '->modify';

Now create the file ``Classes/Hooks/NewsControllerSettings.php``:

.. code-block:: php

   <?php

   namespace YourVendor\Extkey\Hooks;

   class NewsControllerSettings
   {
       public function modify(array $params)
       {
           $settings = $params['originalSettings'];
           $settings['categories'] = '2,3';

           return $settings;
       }
   }

.. hint:: Please change the vendor and extension key to your real life code.


