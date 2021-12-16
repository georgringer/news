.. include:: /Includes.rst.txt

.. _viewHelpersTutorial:

===========
ViewHelpers
===========

ViewHelpers are used to add logic inside the view.
There basic things like if/else conditions, loops and so on. The system
extension Fluid has the most important ViewHelpers already included.

To be able to use a ViewHelper in your template, you need to follow always the
same structure which is:

.. code-block:: html

   <f:fo>bar</f:fo>

This would call the ViewHelper :code:`fo` of the namespace :code:`f` which stands for Fluid.
If you want to use ViewHelpers from other extensions you need to add the namespace
declaration at the beginning of the template. The namespace declaration for the news extension is:

.. code-block:: html

   <html xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
        xmlns:n="http://typo3.org/ns/GeorgRinger/News/ViewHelpers"
        xmlns:x="http://typo3.org/ns/Vendor/Someextension/ViewHelper"
        data-namespace-typo3-fluid="true">
   ...
   </html>


Now you can use a ViewHelper of news with a code like:

.. code-block:: html

   <n:headerData><!-- some comment --></n:headerData>

If you want to know what a ViewHelper does, it is very easy to find the related
PHP class by looking at the namespace and the name of the ViewHelper.
Having for example :php:`GeorgRinger\News\ViewHelpers` and :php:`headerData`
you will find the class at :file:`news\Classes\ViewHelpers\HeaderDataViewHelper.php`.

The most of awesome thing is that you can use ViewHelpers of any extension in
any other template by just adding another namespace declaration like:

.. code-block:: html

    {namespace something=Tx_AnotherExtension_ViewHelpers}

and call the ViewHelper like

.. code-block:: html

    <something:NameOfTheViewHelper />

See also
========

*  :ref:`EXT:news ViewHelper reference <viewHelpersReference>`
*  :ref:`Core ViewHelper reference <viewHelpersReference>`
