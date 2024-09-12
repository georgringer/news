.. _extendFlexforms:

================
Extend FlexForms
================

Following fields of the plugin configuration can be extended without
overriding the complete FlexForm configuration.


.. contents::
        :local:
        :depth: 1

Selectbox "Sort by"
^^^^^^^^^^^^^^^^^^^
The sorting can be extended by adding the value to

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews']

Default values are: tstamp,datetime,crdate,title

Additional Template Selector
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If you need a kind of template selector inside a plugin, you can add
your own selections by adding those to

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts']['myext'] = ['My Title', 'my value'];

You can then access the variable in your template with
:code:`{settings.templateLayout}` and use it for a condition or whatever.

Extend FlexForms with custom fields
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If you need additional fields in the FlexForm configuration, this can be done by using a hook in the Core.

.. important::

  Using such event is *not* restricted to the news extension. It can be used for any extension.
  One working example can be found in `FlexFormHook` of EXT:eventnews.

**Register the Event**

Add this to ``Services.yaml`` of your extension:

.. code-block:: yaml

   services:
       Vendor\ExtKey\EventListener\ModifyFlexformEvent:
           tags:
               - name: event.listener
                 identifier: 'flexParsing'
                 event: TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureParsedEvent

**Add the hook**

Create the class ``ModifyFlexformEvent`` in your extension in ``Classes/EventListener/ModifyFlexformEvent.php`` and add the path to an additional
FlexForm file.

.. code-block:: php

   <?php

   namespace Vendor\ExtKey\EventListener;

   use TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureParsedEvent;
   use TYPO3\CMS\Core\Utility\ArrayUtility;
   use TYPO3\CMS\Core\Utility\GeneralUtility;

   class ModifyFlexformEvent
   {
       public function __invoke(AfterFlexFormDataStructureParsedEvent $event): void
       {
           $dataStructure = $event->getDataStructure();
           $identifier = $event->getIdentifier();

           // $identifier['dataStructureKey'] depends on the selected plugin!
           if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === '*,news_pi1') {
               $file = GeneralUtility::getFileAbsFileName('EXT:extKey/Configuration/Example.xml');
               $content = file_get_contents($file);
               if ($content) {
                   ArrayUtility::mergeRecursiveWithOverrule($dataStructure['sheets'], GeneralUtility::xml2array($content));
               }
           }

           $event->setDataStructure($dataStructure);
       }
   }

**Create the FlexForm file**

Create the FlexForm file you just referenced in the hook. This can look like that. (Syntax for TYPO3 12 LTS+)

.. code-block:: html

    <sheets>
        <extra>
            <ROOT>
                <sheetTitle>Fo</sheetTitle>
                <type>array</type>
                <el>
                    <settings.postsPerPage>
                        <label>Max. number of posts to display per page</label>
                        <config>
                            <type>input</type>
                            <size>2</size>
                            <eval>int</eval>
                            <default>3</default>
                        </config>
                    </settings.postsPerPage>
                </el>
            </ROOT>
        </extra>
    </sheets>
