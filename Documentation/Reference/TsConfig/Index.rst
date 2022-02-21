.. include:: /Includes.rst.txt

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


.. container:: row m-0 p-0

   .. container:: col-12 col-md-6 pl-0 pr-3 py-3 m-0

      .. container:: card px-0 h-100

         .. rst-class:: card-header h3

            .. rubric:: :ref:`General TSconfig <tsconfigGeneral>`

         .. container:: card-body

            The general configuration covers options available during
            the creation and editing of news records.

   .. container:: col-12 col-md-6 pl-0 pr-3 py-3 m-0

      .. container:: card px-0 h-100

         .. rst-class:: card-header h3

            .. rubric:: :ref:`Administration module <tsconfigAdministration>`

         .. container:: card-body

            Configuration of the :guilabel:`Web > News Administration` module

   .. container:: col-12 col-md-6 pl-0 pr-3 py-3 m-0

      .. container:: card px-0 h-100

         .. rst-class:: card-header h3

            .. rubric:: :ref:`Plugin configuration <tsconfigPlugin>`

         .. container:: card-body

            This section covers settings which influence the news plugin

.. toctree::
   :maxdepth: 3
   :glob:

   General
   Administration
   Plugin
