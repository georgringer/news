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


All configuration can happen either in  **Page TsConfig** but can also
be overridden by the  **User TsConfig** by prefixing the setting with
**page.** . As an example: Placing the following snippet in Page
TsConfig will define the preselections for every user on the page
where it is defined ::

   tx_news.module {
           preselect {
                   topNewsRestriction = 1
           }
   }

Having something like ::

   page.tx_news.module {
           preselect {
                   topNewsRestriction = 2
           }
   }

inside the User TsConfig of a backend user will override any Page
TsConfig.

