.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


Extend flexforms
^^^^^^^^^^^^^^^^

Following fields of the plugin configuration can be extended without
overrwriting the complete flexform configuration.


Selectbox “Sort by”
"""""""""""""""""""

The sorting can be extended by adding the value to ::

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews']

Default values are: tstamp,datetime,crdate,title


Additional Actions
""""""""""""""""""

If you need an additional action to select, you can extend it by using ::

    // add an additional action: Key is "Controller->action", value is label
   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems']['News->byFobar'] = 'A fobar action';


Additional Template Selector
""""""""""""""""""""""""""""

If you need a kind of template selector inside a plugin, you can add
your own selections by adding those to ::

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts']['myext'] = array('My Title', 'my value');

You can then access the variable in your template with
{settings.templateLayout} and use it for a condition or whatever.

