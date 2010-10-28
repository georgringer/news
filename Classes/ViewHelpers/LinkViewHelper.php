<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
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
 * ViewHelper to render links from news records to detail view or page
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_ViewHelpers_LinkViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {


	/**
	 *
	 * @param Tx_News2_Domain_Model_News $newsItem
	 * @param array $settings
	 * @param boolean $renderTypeClass
	 * @param string $class
	 * @return string url
	 */
	public function render(Tx_News2_Domain_Model_News $newsItem, array $settings=array(), $renderTypeClass = TRUE, $class = '') {
		$url = '';
		$cObj = t3lib_div::makeInstance('tslib_cObj');
		$linkConfiguration = array();

		if ($renderTypeClass && !empty($class)) {
			$linkConfiguration['ATagParams'] = 'class="list-item ' . $class . '"';
		} elseif(!empty($class)) {
			$linkConfiguration['ATagParams'] = 'class="' . $class . '"';
		}

		
		$newsType = $newsItem->getType();

		if ($newsType == 0) {
			$pageId = ((int)($settings['pidDetail']) > 0) ? (int)($settings['pidDetail']) : $GLOBALS['TSFE']->id;
			$linkConfiguration['parameter'] = $pageId;
			$linkConfiguration['additionalParams'] = '&tx_news2_pi1[controller]=News&tx_news2_pi1[action]=detail&tx_news2_pi1[news]=' . $newsItem->getUid();
			$linkConfiguration['useCacheHash'] = 1;
		} elseif($newsType == 1) {
			$linkConfiguration['parameter'] = $newsItem->getInternalurl();
		} elseif($newsType == 2) {
			$linkConfiguration['parameter'] = $newsItem->getExternalurl();
		} else {
			// @todo error handling
		}
			

		return $cObj->typolink($this->renderChildren(), $linkConfiguration);
	}
}