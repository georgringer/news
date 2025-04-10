.. _eventsTutorial:

======
Events
======

Several events can be used to modify the behaviour of EXT:news.

.. contents::
      :local:
      :depth: 2


Available events
----------------

Check out the :ref:`Events reference <referenceEvents>`.



Examples
--------

Provide more variables to the view
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To connect to an event, you need to register an event listener in your custom
extension. All what it needs is an entry in your
:file:`Configuration/Services.yaml` file:

.. code-block:: yaml

   services:
     Vendor\Extension\EventListener\YourListener:
       tags:
         - name: event.listener
           identifier: 'your-self-choosen-identifier'
           method: 'methodToConnectToEvent'
           event: GeorgRinger\News\Event\NewsListActionEvent

An example event listener can look like this:

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace Vendor\Extension\EventListener;

   use GeorgRinger\News\Event\NewsListActionEvent;

   class YourListener
   {
       /**
        * Do what you want...
        */
       public function methodToConnectToEvent(NewsListActionEvent $event): void
       {
           $values = $event->getAssignedValues();

           // Do some stuff

           $event->setAssignedValues($values);
       }
   }


.. event_example_findDemanded:

\GeorgRinger\News\Event\ModifyDemandRepositoryEvent
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This event is very powerful, as it allows to modify the query used to fetch the news records.

This example modifies the query and adds a constraint that only news records are shown which contain the word *yeah*.


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
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Use this event to change the final settings which are for building queries, for the template, ...


This example modifies the settings by changing the category selection.

First, register your implementation in the file `Services/yaml`:

.. code-block:: yaml

  YourVendor\YourExtkey\EventListener\NewsControllerOverrideSettingsEventListener:
    tags:
      - name: event.listener
        identifier: 'eventnews-modifysettings'
        event: GeorgRinger\News\Event\NewsControllerOverrideSettingsEvent

Now create the file ``Classes/EventListener/NewsControllerOverrideSettingsEvent.php``:

.. code-block:: php

   <?php

   namespace YourVendor\Extkey\EventListener;

   class NewsControllerOverrideSettingsEvent
   {
       public function __invoke(\GeorgRinger\News\Event\NewsControllerOverrideSettingsEvent $event): array
       {
           $settings = $event->getSettings();
           $settings['categories'] = '2,3';

           $event->setSettings($settings);
       }
   }
