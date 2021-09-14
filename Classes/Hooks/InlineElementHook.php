<?php

namespace GeorgRinger\News\Hooks;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Form\Element\InlineElement;
use TYPO3\CMS\Backend\Form\Element\InlineElementHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Inline Element Hook
 */
class InlineElementHook implements InlineElementHookInterface
{

    /**
     * Initializes this hook object.
     *
     * @param InlineElement $parentObject
     *
     * @return void
     */
    public function init(&$parentObject): void
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
     *
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
     *
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
        $previewSetting = (int)($childRecord['showinpreview'] ?? 0);
        if ($foreignTable === 'sys_file_reference' && $previewSetting > 0) {
            $ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

            $extensionConfiguration = GeneralUtility::makeInstance(EmConfiguration::class);

            if ($extensionConfiguration->isAdvancedMediaPreview()) {
                if ($previewSetting === 1) {
                    $label = $GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.showinviews.1');
                    $extraItem = ['showinpreview' => ' <span class="btn btn-default" title="' . htmlspecialchars($label) . '"><i class="fa fa-check"></i></span>'];
                    $controlItems = $extraItem + $controlItems;
                } elseif ($previewSetting === 2) {
                    $label = $GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.showinviews.2');
                    $extraItem = ['showinpreview' => ' <span class="btn btn-default" title="' . htmlspecialchars($label) . '"><i class="fa fa-check-square"></i></span>'];
                    $controlItems = $extraItem + $controlItems;
                }
            } elseif ($previewSetting === 1) {
                $label = $GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.showinpreview');
                $extraItem = ['showinpreview' => ' <span class="btn btn-default" title="' . htmlspecialchars($label) . '"><i class="fa fa-check"></i></span>'];
                $controlItems = $extraItem + $controlItems;
            }
        }
    }
}
