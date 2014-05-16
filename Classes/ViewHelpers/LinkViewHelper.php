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
 *
 * # Example: Basic link
 * <code>
 * <n:link newsItem="{newsItem}" settings="{settings}">
 * 	{newsItem.title}
 * </n:link>
 * </code>
 * <output>
 * A link to the given news record using the news title as link text
 * </output>
 *
 * # Example: Set an additional attribute
 * # Description: Available: class, dir, id, lang, style, title, accesskey, tabindex, onclick
 * <code>
 * <n:link newsItem="{newsItem}" settings="{settings}" class="a-link-class">fo</n:link>
 * </code>
 * <output>
 * <a href="link" class="a-link-class">fo</n:link>
 * </output>
 *
 * # Example: Return the link only
 * <code>
 * <n:link newsItem="{newsItem}" settings="{settings}" uriOnly="1" />
 * </code>
 * <output>
 * The uri is returned
 * </output>
 *
 */
class Tx_News_ViewHelpers_LinkViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Link\PageViewHelper {

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

	/** @var $cObj \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
	protected $cObj;

	/**
	 * @var Tx_News_Service_SettingsService $pluginSettingsService
	 * @return void
	 */
	public function injectSettingsService(Tx_News_Service_SettingsService $pluginSettingsService) {
		$this->pluginSettingsService = $pluginSettingsService;
	}

	/**
	 * Render link to news item or internal/external pages
	 *
	 * @param Tx_News_Domain_Model_News $newsItem current news object
	 * @param array $settings
	 * @param boolean $uriOnly return only the url without the a-tag
	 * @param array $configuration optional typolink configuration
	 * @return string link
	 */
	public function render(Tx_News_Domain_Model_News $newsItem, array $settings = array(), $uriOnly = FALSE, $configuration = array()) {
		$tsSettings = $this->pluginSettingsService->getSettings();

		$this->init();

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
				$configuration = $this->getLinkToNewsItem($newsItem, $tsSettings, $configuration);
		}
		if (isset($tsSettings['link']['typesOpeningInNewWindow'])) {
			if (\TYPO3\CMS\Core\Utility\GeneralUtility::inList($tsSettings['link']['typesOpeningInNewWindow'], $newsType)) {
				$this->tag->addAttribute('target', '_blank');
			}
		}

		$url = $this->cObj->typoLink_URL($configuration);
		if ($uriOnly) {
			return $url;
		}

		$this->tag->addAttribute('href', $url);
		$this->tag->setContent($this->renderChildren());
		return $this->tag->render();
	}

	/**
	 * Generate the link configuration for the link to the news item
	 *
	 * @param Tx_News_Domain_Model_News $newsItem
	 * @param array $tsSettings
	 * @param array $configuration
	 * @return array
	 */
	protected function getLinkToNewsItem(Tx_News_Domain_Model_News $newsItem, $tsSettings, $configuration) {
		$detailPid = 0;
		$detailPidDeterminationMethods = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $tsSettings['detailPidDetermination'], TRUE);

		// if TS is not set, prefer flexform setting
		if (!isset($tsSettings['detailPidDetermination'])) {
			$detailPidDeterminationMethods[] = 'flexform';
		}

		foreach ($detailPidDeterminationMethods as $determinationMethod) {
			if ($callback = $this->detailPidDeterminationCallbacks[$determinationMethod]) {
				if ($detailPid = call_user_func(array($this, $callback), $tsSettings, $newsItem)) {
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
		return $configuration;
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

	/**
	 * Initialize properties
	 *
	 * @return void
	 */
	protected function init() {
		$this->cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
	}
}
