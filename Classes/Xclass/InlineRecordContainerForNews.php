<?php

namespace GeorgRinger\News\Xclass;

use TYPO3\CMS\Backend\Form\Container\InlineRecordContainer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Xclass InlineRecordContainer to show better preview of tt_content elements
 */
class InlineRecordContainerForNews extends InlineRecordContainer
{

    /**
     * @param array $data
     * @return string
     */
    protected function _renderForeignRecordHeader(array $data): string
    {
        $languageService = $this->getLanguageService();
        $inlineConfig = $data['inlineParentConfig'];
        $foreignTable = $inlineConfig['foreign_table'];
        if (!isset($inlineConfig['appearance']['useXclassedVersion']) || $inlineConfig['appearance']['useXclassedVersion'] !== true || $foreignTable !== 'tt_content') {
            return parent::renderForeignRecordHeader($data);
        }

        $rec = $data['databaseRow'];
        // Init:
        $domObjectId = $this->inlineStackProcessor->getCurrentStructureDomObjectIdPrefix($data['inlineFirstPid']);
        $objectId = $domObjectId . '-' . $foreignTable . '-' . $rec['uid'];

        $altText = BackendUtility::getRecordIconAltText($rec, $foreignTable);
        $iconImg = '<span title="' . $altText . '" id="' . htmlspecialchars($objectId) . '_icon' . '">' . $this->iconFactory->getIconForRecord($foreignTable, $rec, Icon::SIZE_SMALL)->render() . '</span>';

        $raw = BackendUtility::getRecord('tt_content', $rec['uid']);
        $renderFallback = true;
        if (is_array($raw) && !empty($raw) && $raw['CType'] !== 'gridelements_pi1') {
            $renderFallback = false;
            $pageLayoutView = GeneralUtility::makeInstance(PageLayoutView::class);
            $pageLayoutView->doEdit = false;
            foreach ($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] as $val) {
                $pageLayoutView->CType_labels[$val[1]] = $this->getLanguageService()->sL($val[0]);
            }

            $label = trim($pageLayoutView->tt_content_drawItem($raw));
            if (strpos($label, $this->getWarningLabel($raw['CType'])) !== false) {
                $renderFallback = true;
            }
        }

        if ($renderFallback) {
            $label = $data['recordTitle'];
            if (!empty($label)) {
                // The user function may return HTML, therefore we can't escape it
                if (empty($data['processedTca']['ctrl']['formattedLabel_userFunc'])) {
                    $label = BackendUtility::getRecordTitlePrep($label);
                }
            } else {
                $label = '<em>[' . htmlspecialchars($languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.no_title')) . ']</em>';
            }
        }

        $label = '<span id="' . $objectId . '_label">' . $label . '</span>';

        $typo3Information = GeneralUtility::makeInstance(Typo3Version::class);
        if ($typo3Information->getMajorVersion() === 9) {
            $header = '
                <div class="form-irre-header-cell form-irre-header-icon" id="' . $objectId . '_iconcontainer" style="vertical-align:top;padding-top:8px;">' . $iconImg . '</div>
				<div class="form-irre-header-cell form-irre-header-body">' . $label . '</div>
				<div class="form-irre-header-cell form-irre-header-control t3js-formengine-irre-control">' . $this->renderForeignRecordHeaderControl($data) . '</div>';
        } else {
            $header = '
                <button class="form-irre-header-cell form-irre-header-button" ' . $data['ariaAttributesString'] . '>' . '
                    <div class="form-irre-header-cell form-irre-header-icon" id="' . $objectId . '_iconcontainer" style="vertical-align:top;padding-top:8px;">' . $iconImg . '</div>
                    <div class="form-irre-header-cell form-irre-header-body">' . $label . '</div>
                </button>
                <div class="form-irre-header-cell form-irre-header-control t3js-formengine-irre-control">' . $this->renderForeignRecordHeaderControl($data) . '</div>';
        }

        return $header;
    }

    /**
     * @param string $cType
     * @return string
     */
    protected function getWarningLabel($cType): string
    {
        $message = sprintf(
            $this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.noMatchingValue'),
            $cType
        );
        return htmlspecialchars($message);
    }
}
