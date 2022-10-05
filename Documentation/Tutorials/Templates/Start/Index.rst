.. include:: /Includes.rst.txt

.. _templatingStart:

============================
Changing & editing templates
============================

EXT:news is using Fluid as templating engine. If you are used to Fluid
already, you might skip this section.

This documentation won't bring you all information about Fluid but only the
most important things you need for using it. You can get
more information in the TYPO3 Documentation
:ref:`TYPO3 Explained: Fluid <t3coreapi:fluid>`,
:doc:`Developing TYPO3 Extensions with Extbase and Fluid
<t3extbasebook:Index>` or many third party sites, videos and books.

.. _templatingSitepackage:

Use a site package extension
============================

It is recommended to always store overwritten templates in a custom TYPO3
extension. Usually this is done in a special extension called the
**site package**.

If you do not have a site package yet you can create one manually following
this :doc:`Official Tutorial: Site Package <t3sitepackage:Index>`.

There is also a `site package generator <https://sitepackagebuilder.com/>`__
available (Provided by Benjamin Kott).

Create a directory called
:file:`EXT:mysitepackage/Resources/Private/Extensions/News` for example and
create 3 directories therein called :file:`Templates`, :file:`Partials` and
:file:`Layouts`. In these directories you can store your version of the Fluid
templates that you need to override.

As any Extbase based extension, you can find the original templates of the
extension news in the directory :file:`EXT:news/Resources/Private/`.

If you want to change a template, copy the desired files to the
directory in your site package. If the template is in a sub folder you have to
preserve the folder structure.

For example the template:

.. code-block:: none

   EXT:news/Resources/Private/Templates/News/Detail.html

would have to be copied to

.. code-block:: none

   EXT:mysitepackage/Resources/Private/Extensions/News/Templates/News/Detail.html

Since your site package extends the extension news you should require news in
your :file:`composer.json`:

.. code-block:: json
   :caption: :file:`EXT:mysitepackage/composer.json`

   {
      "require": {
         "georgringer/news": "^9.0"
      }
   }

And / or :file:`ext_emconf.php`:

.. code-block:: php
   :caption: :file:`ext_emconf.php`

   $EM_CONF[$_EXTKEY] = [
       // ...
       'constraints' => [
           'depends' => [
               'news' => '9.0.0-9.99.99',
           ],
           // ...
       ],
   ];

.. note::
   It is not recommended anymore to store Fluid templates in the
   :file:`fileadmin` directory.

Changing paths of the template
==============================

.. warning::
   You should never edit the original templates of an extension as those changes
   will vanish if you upgrade the extension.

In the most common case, where you want to override the standard news template
with your own templates you can use the TypoScript **constants** to set the
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

If needed, multiple fallbacks can be defined with TypoScript setup:

.. code-block:: typoscript
   :caption: TypoScript setup

   plugin.tx_news {
      view {
         templateRootPaths >
         templateRootPaths {
            0 = EXT:news/Resources/Private/Templates/
            10 = EXT:mynewsextender/Resources/Private/Templates/
            15 = EXT:myothernewsextender/Resources/Private/Templates/
            20 = {$plugin.tx_news.view.templateRootPath}
         }
         partialRootPaths >
         partialRootPaths {
            0 = EXT:news/Resources/Private/Partials/
            10 = EXT:mynewsextender/Resources/Private/Partials/
            15 = EXT:myothernewsextender/Resources/Private/Partials/
            20 = {$plugin.tx_news.view.partialRootPath}
         }
         layoutRootPaths >
         layoutRootPaths {
            0 = EXT:news/Resources/Private/Layouts/
            10 = EXT:mynewsextender/Resources/Private/Layouts/
            15 = EXT:myothernewsextender/Resources/Private/Layouts/
            20 = {$plugin.tx_news.view.layoutRootPath}
         }
      }
   }

It is recommended to always include the path from the TypoScript constants
last (with the highest numeral) so that the site package can still override
the templates.


Change path of the pagination widget
------------------------------------
The path of the pagination widget can be changed by using a configuration like below.

.. code-block:: typoscript
   :caption: TypoScript setup

   plugin.tx_news {
      view {
         widget.GeorgRinger\News\ViewHelpers\Widget\PaginateViewHelper {
            templateRootPath = EXT:mysitepackage/Resources/Private/Extensions/News/Templates/
         }
      }
   }


Layouts, templates & partials
=============================

If using Fluid, the templates are structured by using Layouts, templates and partials.

Layouts
-------

Layouts are used to structure the output of a plugin. A simple example is to
wrap every output with the same :html:`<div>` element. Therefore it is not
needed to repeat this code and write it only once.

A layout can look this:

.. code-block:: html
   :caption: :file:`EXT:mysitepackage/Resources/Private/Extensions/News/Layouts/General.html`

   <div class="myFancyNews">
      <f:render section="content" />
   </div>

This means that the output of the section :html:`content` will be rendered
inside a div with the class :html:`myFancyNews`.

Templates
---------

Every action (like the :guilabel:`List View`, the :guilabel:`Detail View`,
:guilabel:`Date Menu` or a :guilabel:`Category Listing`) needs its own
template which can be found at
:file:`EXT:news/Resources/Private/Templates/<Model>/<ActionName>.html`.

If :file:`Layouts` are used, it is required to define the name of the Layout
(which is identical to the file name of the layout file without file extension).

.. code-block:: html
   :caption: :file:`mysitepackage/Resources/Private/Extensions/News/Templates/<Model>/<ActionName>.html`

   <f:layout name="General" />

   <f:section name="content">
      This will be rendered
   </f:section>


.. note::
   It is optional to use layouts. If those are not used, the complete
   content of the template is shown.

Partials
--------

Partials are used within templates to be able to reuse code snippets.
If you open the template :file:`Templates/News/List.html` you will see the
render ViewHelper:

.. code-block:: html
   :caption: :file:`EXT:news/Resources/Private/Templates/News/List.html`
   :linenos:

   <f:render
      partial="List/Item"
      arguments="{
         newsItem: newsItem,
         settings: settings,
         className: className,
         view: 'list'
      }"
   />

This will embed the output of the partial which is located at
:file:`Partials/List/Item.html` (attribute *partial*, line 2). All
arguments which are used in the attribute *arguments* (line 3ff)
are available in the partial itself.

You can override existing partials or create your own and name them as you like.

Sections
--------

Sections are very similar to partials. The difference is that sections
are defined in the same file as the template.

ViewHelpers
-----------

Every Fluid ViewHelper starts with  :html:`<f:`. The view helpers supplied by
TYPO3 are documented in the :doc:`ViewHelper Reference <t3viewhelper:Index>`.

Any other ViewHelpers from other extensions can be used by using a
namespace declaration like


.. code-block:: html

   <html xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
        xmlns:n="http://typo3.org/ns/GeorgRinger/News/ViewHelpers"
        xmlns:x="http://typo3.org/ns/Vendor/SomeExtension/ViewHelper"
        data-namespace-typo3-fluid="true">
   ...
   </html>


Then ViewHelpers of EXT:news can be used with prefix :html:`n:`. You can find
the available ViewHelpers in the chapter
:ref:`ViewHelper reference <viewHelpersReference>`.
