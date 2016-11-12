.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _extendFlexforms:

Extend flexforms
----------------
Following fields of the plugin configuration can be extended without overriding the complete flexform configuration.


.. only:: html

   .. contents::
        :local:
        :depth: 1

Selectbox "Sort by"
^^^^^^^^^^^^^^^^^^^
The sorting can be extended by adding the value to

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews']

Default values are: tstamp,datetime,crdate,title

Additional Actions
^^^^^^^^^^^^^^^^^^
If you need an additional action to select, you can extend it by using:

.. code-block:: php

	// Add an additional action: Key is "Controller->action", value is label
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems']['News->byFobar'] = 'A fobar action';

Remove fields in additional actions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you define an additional action, you won't need all available fields which are available inside the Flexforms. If you want to hide some fields,
take a look at the hook inside the class Hooks/BackendUtility.php:

.. code-block:: php

	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Hooks/T3libBefunc.php']['updateFlexforms']

Additional Template Selector
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If you need a kind of template selector inside a plugin, you can add
your own selections by adding those to

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts']['myext'] = array('My Title', 'my value');

You can then access the variable in your template with
:code:`{settings.templateLayout}` and use it for a condition or whatever.

Extend flexforms with custom fields
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If you need additional fields in the flexform configuration, this can be done by using a hook in the core.

**Register the hook**

Add this to the ``ext_localconf.php`` of your extension:

.. code-block:: php

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][]
        = \Vendor\ExtKey\Hooks\FlexFormHook::class;

**Add the hook**

Create the class ``FlexFormHook`` in your extension in ``Classes/Hooks/FlexFormHook.php`` and add the path to an additional
flexform file.

.. code-block:: php

    <?php

    namespace Vendor\ExtKey\Hooks;

    class FlexFormHook
    {
        /**
         * @param array $dataStructArray
         * @param array $conf
         * @param array $row
         * @param string $table
         */
        public function getFlexFormDS_postProcessDS(&$dataStructArray, $conf, $row, $table)
        {
            if ($table === 'tt_content' && $row['CType'] === 'list' && $row['list_type'] === 'news_pi1') {
                $dataStructArray['sheets']['extraEntry'] = 'typo3conf/ext/extKey/Configuration/Example.xml';
            }
        }
    }

**Create the flexform file**

Create the flexform file you just referenced in the hook. This can look like that.

.. code-block:: html

    <extra>
        <ROOT>
            <TCEforms>
                <sheetTitle>Fo</sheetTitle>
            </TCEforms>
            <type>array</type>
            <el>
                <settings.postsPerPage>
                    <TCEforms>
                        <label>Max. number of posts to display per page</label>
                        <config>
                            <type>input</type>
                            <size>2</size>
                            <eval>int</eval>
                            <default>3</default>
                        </config>
                    </TCEforms>
                </settings.postsPerPage>
            </el>
        </ROOT>
    </extra>

