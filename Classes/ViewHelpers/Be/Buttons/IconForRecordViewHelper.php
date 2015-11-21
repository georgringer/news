<?php

namespace GeorgRinger\News\ViewHelpers\Be\Buttons;

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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to show sprite icon for a record
 *
 * # Example: Basic example
 * <code>
 * <n:be.buttons.iconForRecord table="tx_news_domain_model_news" uid="{newsItem.uid}" title="" />
 * </code>
 * <output>
 * Icon of the news record with the given uid
 * </output>
 *
 */
class IconForRecordViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper
{

    /**
     * Render the sprite icon
     *
     * @param string $table table name
     * @param int $uid uid of record
     * @param string $title title
     * @return string sprite icon
     */
    public function render($table, $uid, $title)
    {
        $icon = '';
        $row = BackendUtility::getRecord($table, $uid);
        if (is_array($row)) {
            /** @var IconFactory $iconFactory */
            $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
            $icon = '<span title="' . htmlspecialchars($title) . '">'
                . $iconFactory->getIconForRecord($table, $row, Icon::SIZE_SMALL)->render()
                . '</span>';
        }

        return $icon;
    }
}
