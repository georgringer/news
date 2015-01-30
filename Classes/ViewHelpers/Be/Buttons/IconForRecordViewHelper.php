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
use TYPO3\CMS\Backend\Utility\IconUtility;

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
 * @package TYPO3
 * @subpackage tx_news
 */
class IconForRecordViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper {

	/**
	 * Render the sprite icon
	 *
	 * @param string $table table name
	 * @param integer $uid uid of record
	 * @param string $title title
	 * @return string sprite icon
	 */
	public function render($table, $uid, $title) {
		$icon = '';
		$row = BackendUtility::getRecord($table, $uid);
		if (is_array($row)) {
			$icon = IconUtility::getSpriteIconForRecord($table, $row, array('title' => htmlspecialchars($title)));
		}

		return $icon;
	}
}
