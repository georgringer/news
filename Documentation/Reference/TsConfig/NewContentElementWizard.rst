.. _tsconfigNewCEWizard:

====================
New content element wizard
====================

This section covers settings which influence the New content element wizard.

Example: Setting default values for News plugins (TYPO3 < 13)
----------------------------------------

The following examples sets some default values of the plugin "News article list (incl. detail view)" and "News Article Detail View".

.. code-block:: typoscript

   mod.wizards.newContentElement.wizardItems.ext-news {
       elements {
           news_list {
               tt_content_defValues {
                   header_layout = 2
                   header = Latest news from #replace this#
               }
           }

           news_detail {
              tt_content_defValues {
                  header_layout = 3
                  layout = 1
              }
          }
       }
   }

.. note::
   The plugins are added to the :guilabel:`New Content Element` wizard by loading the TSConfig file `ContentElementWizard.tsconfig <https://github.com/georgringer/news/blob/main/Configuration/TSconfig/ContentElementWizard.tsconfig>`_

   In TYPO3 versions < 13 this is done automatically by the news extension.

Example: Setting default values for News plugins (TYPO3 >= 13)
----------------------------------------

..  versionchanged:: 13.0
    Custom content element types are auto-registered for the
    :guilabel:`New Content Element` wizard. The listing can be configured using
    TCA.

    See `Feature: #102834 - Auto-registration of New Content Element Wizard via TCA  <https://docs.typo3.org/permalink/changelog:feature-102834-1705256634>`_

    The TSConfig file that previously registered the plugins in the :guilabel:`New Content Element` wizard is not evaluated anymore.

The following examples sets some default values of the plugin "News article list (incl. detail view)"  and "News Article Detail View".

.. note::
   Notice the changed keys for the wizard group and elements:

   `news` represents the extension key

   `news_pi1` and `news_newsdetail` represent the plugin CType.

.. code-block:: typoscript
    :caption: EXT:my_extension/Configuration/TSConfig/ContentElementWizard.tsconfig

   mod.wizards.newContentElement.wizardItems.news {
       elements {
           news_pi1  {
               tt_content_defValues {
                   header_layout = 2
                   header = Latest news from X (replace this)
               }
           }

           news_newsdetail {
              tt_content_defValues {
                  header_layout = 3
                  layout = 1
              }
          }
       }
   }




Example: Setting default values for News plugins with TCA Overrides
----------------------------------------

.. versionadded:: 13.0
   Default values for the plugins can also be defined with TCA Overrides by using `creationOptions  <https://docs.typo3.org/permalink/t3tca:confval-types-creationoptions>`_ of the tt_content `type` section.


.. code-block:: php
   :caption: EXT:my_extension/Configuration/TCA/Overrides/tt_content.php

   $GLOBALS['TCA']['tt_content']['types']['news_pi1]['creationOptions']['defaultValues'] = [
       'header_layout' => 2,
       'header' => 'Latest news from X (replace this)',
   ];

   $GLOBALS['TCA']['tt_content']['types']['news_newsdetail]['creationOptions']['defaultValues'] = [
      'header_layout' => 3,
      'layout' => 1,
   ];


