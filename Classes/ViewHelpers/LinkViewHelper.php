<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2010 Georg Ringer <typo3@ringerge.org>
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
 * ViewHelper to render links from news records to detail view or page
 * Example
 * <n:link newsItem="{newsItem}" settings="{settings}">
 * {newsItem.title}
 * </n:link>
 *
 * As the AbstractTagBasedViewHelper is extended, it is simple to add an
 * additional class to the link, e.g. by using
 * <n:link newsItem="{newsItem}" settings="{settings}" class="a-link-class">fo</n:link>
 *
 */
class Tx_News_ViewHelpers_LinkViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'a';

	/**
	 * @var Tx_News_Service_SettingsService
	 */
	protected $pluginSettingsService;

	/**
	 * @var array
	 */
	protected $detailPidDeterminationCallbacks = array(
		'flexform' => 'getDetailPidFromFlexform',
		'categories' => 'getDetailPidFromCategories',
		'default' => 'getDetailPidFromDefaultDetailPid',
	);

	/**
	 * @var Tx_News_Service_SettingsService $pluginSettingsService
	 * @return void
	 */
	public function injectSettingsService(Tx_News_Service_SettingsService $pluginSettingsService) {
		$this->pluginSettingsService = $pluginSettingsService;
	}

	/**
	 * Initialize arguments of this view helper
	 *
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerUniversalTagAttributes();
	}

	/**
	 * Render link to news item or internal/external pages
	 *
	 * @param Tx_News_Domain_Model_News $newsItem
	 * @param array $settings
	 * @param boolean $uriOnly return only the url without the a-tag
	 * @param array $configuration optional typolink configuration
	 * @return string link
	 */
	public function render(Tx_News_Domain_Model_News $newsItem, array $settings = array(), $uriOnly = FALSE, $configuration = array()) {
		$tsSettings = $this->pluginSettingsService->getSettings();

		/** @var $cObj tslib_cObj */
		$cObj = t3lib_div::makeInstance('tslib_cObj');

		$newsType = (int)$newsItem->getType();
		switch ($newsType) {
				// internal news
			case 1:
				$configuration['parameter'] = $newsItem->getInternalurl();
				break;
				// external news
			case 2:
				$configuration['parameter'] = $newsItem->getExternalurl();
				break;
				// normal news record
			default:
				$detailPid = 0;
				$detailPidDeterminationMethods = t3lib_div::trimExplode(',', $settings['detailPidDetermination'], TRUE);

					// if TS is not set, prefer flexform setting
				if (!isset($settings['detailPidDetermination'])) {
					$detailPidDeterminationMethods[] = 'flexform';
				}

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

				$configuration['useCacheHash'] = 1;
				$configuration['parameter'] = $detailPid;
				$configuration['additionalParams'] .= '&tx_news_pi1[news]=' . $newsItem->getUid();

				if ((int)$tsSettings['link']['skipControllerAndAction'] !== 1) {
					$configuration['additionalParams'] .= '&tx_news_pi1[controller]=News' .
						'&tx_news_pi1[action]=detail';
				}

					// Add date as human readable (30/04/2011)
				if ($tsSettings['link']['hrDate'] == 1 || $tsSettings['link']['hrDate']['_typoScriptNodeValue'] == 1) {
					$dateTime = $newsItem->getDatetime();

					if (!empty($tsSettings['link']['hrDate']['day'])) {
						$configuration['additionalParams'] .= '&tx_news_pi1[day]=' . $dateTime->format($tsSettings['link']['hrDate']['day']);
					}
					if (!empty($tsSettings['link']['hrDate']['month'])) {
						$configuration['additionalParams'] .= '&tx_news_pi1[month]=' . $dateTime->format($tsSettings['link']['hrDate']['month']);
					}
					if (!empty($tsSettings['link']['hrDate']['year'])) {
						$configuration['additionalParams'] .= '&tx_news_pi1[year]=' . $dateTime->format($tsSettings['link']['hrDate']['year']);
					}
				}
		}
		$configuration['returnLast'] = 'url';
		if (isset($tsSettings['link']['typesOpeningInNewWindow'])) {
			if (t3lib_div::inList($tsSettings['link']['typesOpeningInNewWindow'], $newsType)) {
				$this->tag->addAttribute('target', '_blank');
			}
		}

		$link = $cObj->typolink($this->renderChildren(), $configuration);
		if ($uriOnly) {
			return $link;
		}

		$this->tag->addAttribute('href', $link);
		$this->tag->setContent($this->renderChildren());
		return $this->tag->render();
	}

	/**
	 * Gets detailPid from categories of the given news item. First will be return.
	 *
	 * @param  array $settings
	 * @param  Tx_News_Domain_Model_News $newsItem
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
	 * @param  Tx_News_Domain_Model_News $newsItem
	 * @return int
	 */
	protected function getDetailPidFromDefaultDetailPid($settings, $newsItem) {
		return (int)$settings['defaultDetailPid'];
	}

	/**
	 * Gets detailPid from flexform of current plugin.
	 *
	 * @param  array $settings
	 * @param  Tx_News_Domain_Model_News $newsItem
	 * @return int
	 */
	protected function getDetailPidFromFlexform($settings, $newsItem) {
		return (int)$settings['detailPid'];

	}
}

?>