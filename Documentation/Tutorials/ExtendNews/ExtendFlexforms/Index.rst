.. include:: /Includes.rst.txt

.. _extendFlexforms:

================
Extend FlexForms
================

Following fields of the plugin configuration can be extended without
overriding the complete FlexForm configuration.


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

If you define an additional action, you won't need all available fields which are available inside the FlexForms. If you want to hide some fields,
take a look at the hook inside the class Hooks/BackendUtility.php:

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Hooks/BackendUtility.php']['updateFlexforms']

Additional Template Selector
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If you need a kind of template selector inside a plugin, you can add
your own selections by adding those to

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts']['myext'] = array('My Title', 'my value');

You can then access the variable in your template with
:code:`{settings.templateLayout}` and use it for a condition or whatever.

Extend FlexForms with custom fields
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If you need additional fields in the FlexForm configuration, this can be done by using a hook in the Core.

**Register the hook**

Add this to the ``ext_localconf.php`` of your extension:

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools::class]['flexParsing'][]
      = \Vendor\ExtKey\Hooks\FlexFormHook::class;

**Add the hook**

Create the class ``FlexFormHook`` in your extension in ``Classes/Hooks/FlexFormHook.php`` and add the path to an additional
FlexForm file.

.. code-block:: php

   <?php

   namespace Vendor\ExtKey\Hooks;

   use TYPO3\CMS\Core\Core\Environment;
   use TYPO3\CMS\Core\Utility\GeneralUtility;

   class FlexFormHook
   {
      /**
      * @param array $dataStructure
      * @param array $identifier
      * @return array
      */
      public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier): array
      {
        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === 'news_pi1,list') {
            $file = Environment::getPublicPath() . '/typo3conf/ext/extKey/Configuration/Example.xml';
            $content = file_get_contents($file);
            if ($content) {
                $dataStructure['sheets']['extraEntry'] = GeneralUtility::xml2array($content);
            }
        }
        return $dataStructure;
      }
   }

**Create the FlexForm file**

Create the FlexForm file you just referenced in the hook. This can look like that.

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

