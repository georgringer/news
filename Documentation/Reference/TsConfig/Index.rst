.. _tsconfig:

========
TsConfig
========

This section covers all configurations that can be made with the TSconfig
shipped by the extension news. If you are interested in what you can do
with the general TsConfig in news records have a look at
:ref:`General TSconfig examples <general_tsconfig_examples>`.

Every TSconfig configuration of extension news starts with :typoscript:`tx_news.`.

.. note::
   Just for clarification: TsConfig is in TYPO3 only used for configurations
   inside the backend!


.. card-grid::
   :columns: 1
   :columns-md: 2
   :gap: 4
   :class: pb-4
   :card-height: 100

   ..  card:: :ref:`General TSconfig <tsconfigGeneral>`

      The general configuration covers options available during
      the creation and editing of news records.

   ..  card:: :ref:`Administration module <tsconfigAdministration>`

      Configuration of the :guilabel:`Web > News Administration` module

   ..  card:: :ref:`Plugin configuration <tsconfigPlugin>`

      This section covers settings which influence the news plugin

.. toctree::
   :maxdepth: 3
   :glob:

   General
   Administration
   Plugin
