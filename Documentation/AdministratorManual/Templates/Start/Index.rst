.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Changing & editing templates
============================
EXT:news is using fluid as template engine. If you are used to fluid
already, you might skip this section.

This documentation won't bring you all information about fluid but only the
most important things you need for using it. You can get
more information in books like the one of `Jochen Rau und Sebastian
Kurfürst <http://www.amazon.de/Zukunftssichere-TYPO3-Extensions-mit-
Extbase-Fluid/dp/3897219654/>`_ or online, e.g. at
`http://wiki.typo3.org/Fluid <http://wiki.typo3.org/Fluid>`_ or many
other sites.


Changing paths of the template
------------------------------
You should never edit the original templates of an extension as those changes will vanish if you upgrade the extension.
As any extbase based extension, you can find the templates in the directory ``Resources/Private/``.

If you want to change a template, copy the desired files to the directory where you store the templates.
This can be a directory in ``fileadmin`` or a custom extension. Multiple fallbacks can be defined which makes it far easier to customize the templates.

.. code-block:: typoscript

		plugin.tx_news {
			view {
				templateRootPaths >
				templateRootPaths {
					0 = EXT:news/Resources/Private/Templates/
					1 = fileadmin/templates/ext/news/Templates/
				}
				partialRootPaths >
				partialRootPaths {
					0 = EXT:news/Resources/Private/Partials/
					1 = fileadmin/templates/ext/news/Partials/
				}
				layoutRootPaths >
				layoutRootPaths {
					0 = EXT:news/Resources/Private/Layouts/
					1 = fileadmin/templates/ext/news/Layouts/
				}
			}
		}


Change the templates using TypoScript constants
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
You can use the following TypoScript in the  **constants** to change
the paths

.. code-block:: typoscript

   plugin.tx_news {
           view {
                   templateRootPath = fileadmin/templates/ext/news/Templates/
                   partialRootPath = fileadmin/templates/ext/news/Partials/
                   layoutRootPath = fileadmin/templates/ext/news/Layouts/
           }
   }


Change path of the pagination widget
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
The path of the pagination widget can be changed by using a configuration like below.

.. code-block:: typoscript

	plugin.tx_news {
		view {
			widget.GeorgRinger\News\ViewHelpers\Widget\PaginateViewHelper.templateRootPath = {$plugin.tx_news.view.templateRootPath}
		}
	}


Layouts, Templates & Partials
-----------------------------

If using fluid, the templates are structured by using Layouts, Templates and Partials.

Layouts
^^^^^^^

Layouts are used to structure the output of a plugin. A simple example is to wrap every output with the same
<div> element. Therefore it is not needed to repeat this code and write it only once.

A layout can look this:

.. code-block:: html

	<div class="news">
		<f:render section="content" />
	</div>

This means that the output of the section ``content`` will be rendered inside a div with the class ``news``.

Templates
^^^^^^^^^
Every action (like the list view, the detail view, date menu or a category listing) needs its own template which can be
found at ``Templates/<Model>/<ActionName>.html``.

If **Layouts** are used, it is required to define the name of the Layout (which is identical to the file name of the Layout file).

.. code-block:: html

	<f:layout name="General" />

	<f:section name="content">
		This will be rendered
	</f:section>


.. note:: It is optional to use Layouts. If those are not used, the complete content of the Template is shown.

Partials
^^^^^^^^
Partials are used within templates to be able to reuse code snippets. If you open the template ``News/List.html`` you will see the partial:

.. code-block:: html

	<f:render partial="List/Item" arguments="{newsItem: newsItem, settings:settings, className:className, view:'list'}"/>

This will embed the output of the partial which is located at ``Partials/List/Item.html`` (as stated in the attribute  *partial* ). All
arguments which are used in the attribute *arguments* are available in the partial itself.

You can create your own partials and name them as you like

Sections
^^^^^^^^
Sections are very similar to partials. The difference is that sections are defined in the same file as the template.



ViewHelpers
^^^^^^^^^^^

Ever fluid viewhelper starts with  **<f:** . and you can always check
out the code at typo3/sysext/fluid/Classes/ViewHelpers/. As an example
the viewhelper <f:link.page can be found at
typo3/sysext/fluid/Classes/ViewHelpers/Link/PageViewHelper.php.

Any other viewhelpers from other extensions can be used by using a
namespace declaration like

.. code-block:: html

	{namespace n=GeorgRinger\News\ViewHelpers}

Then viewHelpers of EXT:news (which can be found in ``news/Classes/ViewHelpers``) can be used with the prefix  **n:** .

