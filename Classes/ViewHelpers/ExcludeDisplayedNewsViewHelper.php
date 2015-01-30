<?php

namespace GeorgRinger\News\ViewHelpers;

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
 * ViewHelper to exclude news items in other plugins
 *
 * # Example: Basic example
 *
 * <code>
 * <n:excludeDisplayedNews newsItem="{newsItem}" />
 * </code>
 * <output>
 * None
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class ExcludeDisplayedNewsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Add the news uid to a global variable to be able to exclude it later
	 *
	 * @param \GeorgRinger\News\Domain\Model\News $newsItem current news item
	 * @return void
	 */
	public function render(\GeorgRinger\News\Domain\Model\News $newsItem) {
		$uid = $newsItem->getUid();

		if (empty($GLOBALS['EXT']['news']['alreadyDisplayed'])) {
			$GLOBALS['EXT']['news']['alreadyDisplayed'] = array();
		}
		$GLOBALS['EXT']['news']['alreadyDisplayed'][$uid] = $uid;

		// Add localized uid as well
		$originalUid = (int)$newsItem->_getProperty('_localizedUid');
		if ($originalUid > 0) {
			$GLOBALS['EXT']['news']['alreadyDisplayed'][$originalUid] = $originalUid;
		}
	}
}
