.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Reference TsConfig
^^^^^^^^^^^^^^^^^^

This section covers all configuration which can be set with TsConfig.
Every configuration starts with tx\_news.

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Data type:
         Data type:

   :Description:
         Description:


 - :Property:
         module.preselect

   :Data type:
         array

   :Description:
         Define preselects in the Administration module. An example would be ::

            tx_news.module {
                    preselect {
                            topNewsRestriction = 1
                    }
            }

         which will preselect “Top news” in the module instead of all


 - :Property:
         singlePid

   :Data type:
         integer

   :Description:
         It is possible to preview a news record if pressing the button “Save &
         Preview”. Therefore the ID of the page with the single view needs to
         be defined. An example would look like ::

            tx_news.singlePid = 123

         A plugin with the view “Detail view” needs to be created on that page.
         If a preview of hidden records needs to be allowed too, the checkbox
         “Allow hidden records” needs to be checked in the plugin.


 - :Property:
         templateLayouts

   :Data type:
         array

   :Description:
         The selectbox “Template Layout” inside a plugin can be easily be
         extended by using TsConfig. As an example ::

            tx_news.templateLayouts {
                    123 = Fobar
                    456 = Blub
            }

         will show 2 layout options with 123/456 as key and Fobar/Blub as
         value.

         Inside the template it is then possible to define conditions with
         fluid by checking {settings.templateLayout}


 - :Property:
         predefine.archive

   :Data type:
         string

   :Description:
         Use strtotime (see `http://www.php.net/strtotime
         <http://www.php.net/strtotime>`_ ) to predefine the archive date. As
         an example::

            tx_news.predefine.archive = next friday

         will set the archive date on the the next friday.


Reference TsConfig for the Administration Module (Backend)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. tip::

	All configuration can happen either in  **Page TsConfig** but can also
	be overridden by the  **User TsConfig** by prefixing the setting with
	**page.** .


.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Data type:
         Data type:

   :Description:
         Description:

 - :Property:
         preselect:

   :Data type:
         array

   :Description:
         Predefine the form in the administration module. The possible fields for the preselection are:

         * recursive
         * timeRestriction
         * topNewsRestriction
         * limit
         * offset
         * sortingField
         * sortingDirection
         * categoryConjunction

         Example: ::

           tx_news.module {
           	preselect {
           		topNewsRestriction = 1
           	}
           }

 - :Property:
         defaultPid

   :Data type:
         integer

   :Description:
         If no page is selected in the page tree, any record created in the administration module would be saved on the root page.
         If this is not desired, the pid can be defined by using defaultPid.<tablename>

         Example: ::

           tx_news.module.defaultPid.tx_news_domain_model_news = 123

         News records will be saved on page with ID 123.

 - :Property:
         redirectToPageOnStart

   :Data type:
         integer

   :Description:
         If no page is selected, the user will be redirected to the given page

         Example: ::

           tx_news.module.redirectToPageOnStart = 456

         The user will be redirected to the page with the uid 456.


Clear caches if a news record changes
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If a news record is created or edited, chances are high that the changes are not visible in the frontend as the output is still cached.
Therefore you need to clear the caches of the list and single view pages. This can be done automatically by using this command in the PageTsConfig: ::

	TCEMAIN.clearCacheCmd = 123,456,789


The code needs to be added to the sys folder where the news records are edited. Change the example page ids to the ones which should be cleared, e.g. the one of list views and detail view.
You can use: ::

	TCEMAIN.clearCacheCmd = pages

to clear the complete caches as well

.. tip::

	The mentioned TCEMAIN settings are part of the TYPO3 core and can be used therefore not only for the news extension.
