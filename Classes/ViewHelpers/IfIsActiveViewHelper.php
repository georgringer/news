<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * ViewHelper to check if the current news item is rendered as single view on the same page
 *
 * # Example: Basic example
 * <code>
 * {n:ifIsActive(newsItem: newsItem, then: 'active', else: '')}
 * </code>
 * <output>
 * Renders the string "active" if the current news item is active
 * </output>
 *
 */
class Tx_News_ViewHelpers_IfIsActiveViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	public function render(Tx_News_Domain_Model_News $newsItem) {
		$vars = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tx_news_pi1');

		if (isset($vars['news']) && (int)$newsItem->getUid() === (int)$vars['news']) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}
