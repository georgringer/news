.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Set overwriteDemand in Frontend
-------------------------------
Sometimes it can be nice to define the overwriteDemand properties in the frontend. Usecases are:

- Change the sorting in the frontend
- Define some category filter
- ...

The following example defines the ordering

.. code-block:: html

   <f:link.page additionalParams="{tx_news_pi1:{overwriteDemand:{order: 'datetime desc'}}}">
      Order datetime descending
   </f:link.page>

.. important:: The checkbox **Disable override demand** in the list plugin (Tab Additional) must **not** be set to allow overriding the properties.

