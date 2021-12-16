.. include:: /Includes.rst.txt

.. _eventsTutorial:

======
Events
======

Several events can be used to modify the behaviour of EXT:news.

.. only:: html

   .. contents::
      :local:
      :depth: 1

Connect to Event
----------------

To connect to an event, you need to register an event listener in your custom
extension. All what it needs is an entry in your
:file:`Configuration\Services.yaml` file:

.. code-block:: yaml

   services:
     Vendor\Extension\EventListener\YourListener:
       tags:
         - name: event.listener
      identifier: 'your-self-choosen-identifier'
      method: 'methodToConnectToEvent'
      event: GeorgRinger\News\Event\NewsListActionEvent

Write your EventListener
------------------------

An example event listener can look like this:

.. code-block:: php

    <?php

   declare(strict_types=1);

   namespace Vendor\Extension\EventListener;

   use GeorgRinger\News\Event\NewsListActionEvent;

   /**
    * Use NewsListActionEvent from ext:news
    */
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

Available events
----------------

Check out the :ref:`Events reference <referenceEvents>`.
