<?php

namespace GeorgRinger\News\Hooks;

/**
     * This file is part of the TYPO3 CMS project.
     *
     * It is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License, either version 2
     * of the License, or any later version.
     *
     * For the full copyright and license information, please read the
     * LICENSE.txt file that was distributed with this source code.
     *
     * The TYPO3 project - inspiring people to share!
     */

/**
 * Inline Element Hook
 *
 */
class InlineElementHook implements \TYPO3\CMS\Backend\Form\Element\InlineElementHookInterface
{

    /**
     * Initializes this hook object.
     *
     * @param \TYPO3\CMS\Backend\Form\Element\InlineElement $parentObject
     * @return void
     */
    public function init(&$parentObject)
    {
    }

    /**
     * Pre-processing to define which control items are enabled or disabled.
     *
     * @param string $parentUid The uid of the parent (embedding) record (uid or NEW...)
     * @param string $foreignTable The table (foreign_table) we create control-icons for
     * @param array $childRecord The current record of that foreign_table
     * @param array $childConfig TCA configuration of the current field of the child record
     * @param bool $isVirtual Defines whether the current records is only virtually shown and not physically part of the parent record
     * @param array &$enabledControls (reference) Associative array with the enabled control items
     * @return void
     */
    public function renderForeignRecordHeaderControl_preProcess(
        $parentUid,
        $foreignTable,
        array $childRecord,
        array $childConfig,
        $isVirtual,
        array &$enabledControls
    ) {
    }

    /**
     * Post-processing to define which control items to show. Possibly own icons can be added here.
     *
     * @param string $parentUid The uid of the parent (embedding) record (uid or NEW...)
     * @param string $foreignTable The table (foreign_table) we create control-icons for
     * @param array $childRecord The current record of that foreign_table
     * @param array $childConfig TCA configuration of the current field of the child record
     * @param bool $isVirtual Defines whether the current records is only virtually shown and not physically part of the parent record
     * @param array &$controlItems (reference) Associative array with the currently available control items
     * @return void
     */
    public function renderForeignRecordHeaderControl_postProcess(
        $parentUid,
        $foreignTable,
        array $childRecord,
        array $childConfig,
        $isVirtual,
        array &$controlItems
    ) {
        if ($foreignTable === 'sys_file_reference' && !empty($childRecord['showinpreview'])) {
            $label = $GLOBALS['LANG']->sL('LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_media.showinpreview');
            $extraItem = ['showinpreview' => ' <span class="btn btn-default" title="' . htmlspecialchars($label) . '"><i class="fa fa-check"></i></span>'];
            $controlItems = $extraItem + $controlItems;
        }
    }
}
