.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Changing & editing templates
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

EXT:news is using fluid as template engine. If you are used to fluid
already, you can skip this section as you will not get any new
information ;)

This section won't bring you all information about fluid but only the
most important things if you have just started using it. You can get
more information in books like the one of `Jochen Rau und Sebastian
Kurfürst <http://www.amazon.de/Zukunftssichere-TYPO3-Extensions-mit-
Extbase-Fluid/dp/3897219654/>`_ or online, e.g. at
`http://wiki.tpyo3.org/Fluid <http://wiki.tpyo3.org/Fluid>`_ or many
other sites.


Changing paths of the template
""""""""""""""""""""""""""""""

You should never edit the original templates of an extension as those
changes will vanish when you upgrade the extension. As any extbase
based extension, you will the templates in the directory
Resources/Private/Templates/.

If you want to change a template, copy the directories Layouts/,
Partials/, and Templates to a different folder. In this section, this
is fileadmin/template/ext/news/. The difference between those items
will be explained in the next section.


**Nice to know:** Since TYPO3 6.2 it is possible to just override a single template file.
Multiple fallbacks can be defined which makes it far easier to customize the templates.

The TypoScript Setup syntax looks like this: ::

		plugin.tx_news {
			view {
				templateRootPaths {
					0 = EXT:news/Resources/Private/Templates/
					1 = EXT:fileadmin/templates/ext/news/Templates/
				}
				partialRootPaths {
					0 = EXT:news/Resources/Private/Partials/
					1 = fileadmin/templates/ext/news/Partials/
				}
				layoutRootPaths {
					0 = EXT:news/Resources/Private/Layouts/
					1 = fileadmin/templates/ext/news/Layouts/
				}
			}
		}


Change the templates using TypoScript constants
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can use the following TypoScript in the  **constants** to change
the paths ::

   plugin.tx_news {
           view {
                   templateRootPath = fileadmin/templates/ext/news/Templates/
                   partialRootPath = fileadmin/templates/ext/news/Partials/
                   layoutRootPath = fileadmin/templates/ext/news/Layouts/
           }
   }



Layouts, Templates & Partials
"""""""""""""""""""""""""""""

If using fluid, the templates are better structured and you
will get used to those structures very fast, I promise.


Templates
~~~~~~~~~

Every action (like the list view, the detail view, date menu or a
category listing) got its own template which can be found within the
Templates/<Model>/<ActionName>.html


Partials
~~~~~~~~

Partials are used within templates to be able to reuse code snippets.
If you open the template News/List.html you will see the partial ::

   <f:render partial="List/Item" arguments="{newsItem: newsItem, settings:settings, className:className, view:'list'}"/>

This will embed the output of the partial which is saved in
Partials/List/Item.html (as stated in the attribute  *partial* ). All
arguments which are used in the attribute *arguments* are available in
the partial.

You can invent your own partials.


Layouts
~~~~~~~

If you open the template News/List.html (which is used for the list
view of news records), you will see in the beginning of the file ::

   <f:layout name="General" />

which is meaning that this template is using the layout called
“General”. The belonging file can be found in Layouts/General.html.
Please also notice the section “<f:section name="content">” which
holds the information of the list template.

The content of the file is ::

   {namespace n=Tx_News_ViewHelpers}

   <n:includeFile path="EXT:news/Resources/Public/Css/news-basic.css" />
   <n:includeFile path="EXT:news/Resources/Public/Css/news-advanced.css" />

   <div class="news">
           <f:renderFlashMessages class="tx-news-flash-message" />
           <f:render section="content" />
   </div>

Whenever a template is using this layout, the css files news-basic.css
and news-advanced are included. The section “content” will hold the
information of the template.


Viewhelpers
~~~~~~~~~~~

Ever fluid viewhelper starts with  **<f:** . and you can always check
out the code at typo3/sysext/fluid/Classes/ViewHelpers/. As an example
the viewhelper <f:link.page can be found at
typo3/sysext/fluid/Classes/ViewHelpers/Link/PageViewHelper.php.

Any other viewhelpers from other extensions can be used by using a
namespace declaration like

{namespace n=Tx\_News\_ViewHelpers}

Then viewHelpers of EXT:news (which can be found in
news/Classes/ViewHelpers) can be used with the prefix  **n:** .

