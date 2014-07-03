<?php

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
 * TemplateLayout utility class
 */
class Tx_News_Utility_TemplateLayout implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * Get available template layouts for a certain page
	 *
	 * @param int $pageUid
	 * @return array
	 */
	public function getAvailableTemplateLayouts($pageUid) {
		$templateLayouts = array();

		// Check if the layouts are extended by ext_tables
		if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'])
			&& is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'])) {
			$templateLayouts = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'];
		}

		// Add TsConfig values
		foreach($this->getTemplateLayoutsFromTsConfig($pageUid) as $templateKey => $title) {
			$templateLayouts[] = array($title, $templateKey);
		}

		return $templateLayouts;
	}

	/**
	 * Get template layouts defined in TsConfig
	 *
	 * @param $pageUid
	 * @return array
	 */
	protected function getTemplateLayoutsFromTsConfig($pageUid) {
		$templateLayouts = array();
		$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($pageUid);
		if (isset($pagesTsConfig['tx_news.']['templateLayouts.']) && is_array($pagesTsConfig['tx_news.']['templateLayouts.'])) {
			$templateLayouts = $pagesTsConfig['tx_news.']['templateLayouts.'];
		}
		return $templateLayouts;
	}
}