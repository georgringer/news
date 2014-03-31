<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * ViewHelper to add a google+ button
 * Details: http://www.google.com/webmasters/+1/button/
 *
 * Examples
 * ==============
 *
 * <n:social.googlePlus></n:social.googlePlus>
 * Result: Google Plus Button
 *
 * <n:social.googlePlus size="small"
 * 		href="http://www.mydomain.tld" count="false"></n:social.googlePlus>
 * Result: Small Google Plus Button to share www.mydomain.tld
 * 	without showing the counter
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Social_GooglePlusViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var Tx_News_Service_SettingsService
	 */
	protected $pluginSettingsService;

	/**
	 * @var	string
	 */
	protected $tagName = 'g:plusone';

	/**
	 * @var Tx_News_Service_SettingsService $pluginSettingsService
	 * @return void
	 */
	public function injectSettingsService(Tx_News_Service_SettingsService $pluginSettingsService) {
		$this->pluginSettingsService = $pluginSettingsService;
	}


	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('size', 'string', 'Size of the icon. Can be small,medium,tall.');
		$this->registerTagAttribute('callback', 'string', 'Callback function');
		$this->registerTagAttribute('href', 'string', 'URL to be +1, default is current URL');
		$this->registerTagAttribute('count', 'string', 'Set it to false to hide counter');
	}

	/**
	 * Render the Google+ button
	 *
	 * @param string $jsCode Alternative JavaScript code which is used
	 * @return string
	 */
	public function render($jsCode = '') {
		if (empty($jsCode)) {
			$jsCode = 'https://apis.google.com/js/plusone.js';
		} elseif ($jsCode != '-1') {
			$jsCode = htmlspecialchars($jsCode);
		}

		$tsSettings = $this->pluginSettingsService->getSettings();
		$locale = (!empty($tsSettings['googlePlusLocale'])) ? '{lang:\'' . $tsSettings['googlePlusLocale'] . '\'}' : '';

		$code = '<script type="text/javascript" src="' . $jsCode . '">' . $locale . '</script>';

		$this->tag->setContent(' ');

		$code .= $this->tag->render();
		return $code;
	}
}
