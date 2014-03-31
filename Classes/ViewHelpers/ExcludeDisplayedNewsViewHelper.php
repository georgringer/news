<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

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
class Tx_News_ViewHelpers_ExcludeDisplayedNewsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Add the news uid to a global variable to be able to exclude it later
	 *
	 * @param Tx_News_Domain_Model_News $newsItem current news item
	 * @return void
	 */
	public function render(Tx_News_Domain_Model_News $newsItem) {
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
