.. include:: /Includes.rst.txt

.. _overwriteDemand-in-frontend:

============================
Set overwriteDemand in links
============================

Sometimes it can be nice to define links with overwriteDemand properties in the
frontend. Use cases are:

-  Change the sorting in the frontend
-  Define some category filter
-  ...

The following example defines the ordering

.. code-block:: html

   <f:link.page additionalParams="{tx_news_pi1:{overwriteDemand:{order: 'datetime desc'}}}">
      Order datetime descending
   </f:link.page>

.. important::
   The checkbox **Disable override demand** in the list plugin (Tab Additional)
   must **not** be set to allow overriding the properties.
