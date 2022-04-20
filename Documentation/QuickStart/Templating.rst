.. include:: /Includes.rst.txt

.. _quickTemplating:

=========================
Quick templating in Fluid
=========================

EXT:news is using Fluid as templating engine. If you are not experienced
with Fluid yet you can read more about it in the chapter
:ref:`Changing & editing templates <templatingStart>`.

Copy the Fluid templates that you want to adjust to your
:ref:`sitepackage extension <templatingSitepackage>`.

You find the original templates in :file:`EXT:news/Resources/Private/Templates/`
and the partials in :file:`EXT:news/Resources/Private/Partials/`. Never change
these templates directly!

To override the standard news templates
with your own you can use the TypoScript **constants** to set the
paths:

.. code-block:: typoscript
   :caption: TypoScript constants

   plugin.tx_news {
      view {
         templateRootPath = EXT:mysitepackage/Resources/Private/Extensions/News/Templates/
         partialRootPath = EXT:mysitepackage/Resources/Private/Extensions/News/Partials/
         layoutRootPath = EXT:mysitepackage/Resources/Private/Extensions/News/Layouts/
      }
   }

Add these lines to the file
:file:`EXT:mysitepackage/Configuration/TypoScript/constants.typoscript` in your
sitepackage.

EXT:news provides a couple of specific Fluid :ref:`ViewHelpers <viewHelpersReference>`.
Of course all :doc:`ViewHelpers provided by TYPO3 <t3viewhelper:Index>` can
be used as well.

In the tutorial section there are many
:ref:`templating examples <templatingExamples>`.
