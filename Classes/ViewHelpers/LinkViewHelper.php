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
 * Example
 * <n:link newsItem="{newsItem}" settings="{settings}">
 * {newsItem.title}
 * </n:link>
 *
 * Inline notation:
 * {n:link(newsItem:newsItem,settings:settings,linkOnly:1)}
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class Tx_News2_ViewHelpers_LinkViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @var array
	 */
	protected $detailPidDeterminationCallbacks = array (
		'flexform' => 'getDetailPidFromFlexform',
		'categories' => 'getDetailPidFromCategories',
		'default' => 'getDetailPidFromDefaultDetailPid',
	);

	/**
	 * Render link to news item or internal/external pages
	 *
	 * @param Tx_News2_Domain_Model_News $newsItem
	 * @param array $settings
	 * @param boolean $renderTypeClass
	 * @param string $class
	 * @param boolean $linkOnly
	 * @param boolean $hsc
	 * @return string url
	 */
	public function render(Tx_News2_Domain_Model_News $newsItem, array $settings = array(), $renderTypeClass = TRUE, $class = '', $linkOnly = FALSE, $hsc = FALSE) {
		$cObj = t3lib_div::makeInstance('tslib_cObj');
		$linkConfiguration = array();

		if ($renderTypeClass && !empty($class)) {
			$linkConfiguration['ATagParams'] = 'class="list-item ' . $class . '"';
		} elseif (!empty($class)) {
			$linkConfiguration['ATagParams'] = 'class="' . $class . '"';
		}

		$newsType = $newsItem->getType();

			// normal news record
		if ($newsType == 0) {
			$detailPid = 0;
			$detailPidDeterminationMethods = t3lib_div::trimExplode(',', $settings['detailPidDetermination']);

			foreach ($detailPidDeterminationMethods as $determinationMethod) {
				if ($callback = $this->detailPidDeterminationCallbacks[$determinationMethod]) {
					if ($detailPid = call_user_func(array($this, $callback), $settings, $newsItem)) {
					  break;
					}
				}
			}

			if (!$detailPid) {
				$detailPid = $GLOBALS['TSFE']->id;
			}

			$linkConfiguration['useCacheHash'] = 1;
			$linkConfiguration['parameter'] = $detailPid;
			$linkConfiguration['additionalParams'] = '&tx_news2_pi1[controller]=News' .
				'&tx_news2_pi1[action]=detail' .
				'&tx_news2_pi1[news]=' . $newsItem->getUid();
			// internal news
		} elseif ($newsType == 1) {
			$linkConfiguration['parameter'] = $newsItem->getInternalurl();
			// external news
		} elseif ($newsType == 2) {
			$linkConfiguration['parameter'] = $newsItem->getExternalurl();
		}

		if ($linkOnly) {
			$linkConfiguration['returnLast'] = 'url';
		}

		$finalLink = $cObj->typolink($this->renderChildren(), $linkConfiguration);

		if ($hsc) {
			$finalLink = htmlspecialchars($finalLink);
		}

		return $finalLink;
	}

	/**
	 * Gets detailPid from categories of the given news item. First will be return.
	 *
	 * @param  array $settings
	 * @param  Tx_News2_Domain_Model_News $newsItem
	 * @return int
	 */
	protected function getDetailPidFromCategories($settings, $newsItem) {
		$detailPid = 0;
		foreach ($newsItem->getCategories() as $category) {
			if ($detailPid = (int)$category->getSinglePid()) {
				break;
			}
		}
		return $detailPid;
	}

	/**
	 * Gets detailPid from defaultDetailPid setting
	 *
	 * @param  array $settings
	 * @param  Tx_News2_Domain_Model_News $newsItem
	 * @return int
	 */
	protected function getDetailPidFromDefaultDetailPid($settings, $newsItem) {
		return (int)$settings['defaultDetailPid'];
	}

	/**
	 * Gets detailPid from flexfrom of current plugin.
	 *
	 * @param  array $settings
	 * @param  Tx_News2_Domain_Model_News $newsItem
	 * @return int
	 */
	protected function getDetailPidFromFlexform($settings, $newsItem) {
		return (int)$settings['detailPid'];

	}
}
?>