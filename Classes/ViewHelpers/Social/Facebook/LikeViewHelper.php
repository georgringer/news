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
 * ViewHelper to add a like button
 * Details: http://developers.facebook.com/docs/reference/plugins/like
 *
 * Examples
 * ==============
 *
 * <n:social.facebook.like />
 * Result: Facebook widget to share the current URL
 *
 * <n:social.facebook.like
 * 		href="http://www.typo3.org"
 * 		width="300"
 * 		font="arial" />
 * Result: Facebook widget to share www.typo3.org within a plugin styled with
 * width 300 and arial as font
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Social_Facebook_LikeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	/**
	 * @var Tx_News_Service_SettingsService
	 */
	protected $pluginSettingsService;

	/**
	 * @var	string
	 */
	protected $tagName = 'fb:like';

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
		$this->registerTagAttribute('href', 'string', 'Given url, if empty, current url is used');
		$this->registerTagAttribute('layout', 'string', 'Either: standard, button_count or box_count');
		$this->registerTagAttribute('width', 'integer', 'With of widget, default 450');
		$this->registerTagAttribute('font', 'string', 'Font, options are: arial,lucidia grande,segoe ui,tahoma,trebuchet ms,verdana');
		$this->registerTagAttribute('javaScript', 'string', 'JS URL. If not set, default is used, if set to -1 no Js is loaded');
	}

	/**
	 * Render the facebook like viewhelper
	 *
	 * @return string
	 */
	public function render() {
		$code = '';

		$url = (!empty($this->arguments['href'])) ?
				$this->arguments['href'] :
			t3lib_div::getIndpEnv('TYPO3_REQUEST_URL');

			// absolute urls are needed
		$this->tag->addAttribute('href', Tx_News_Utility_Url::prependDomain($url));
		$this->tag->forceClosingTag(TRUE);

			// -1 means no JS
		if ($this->arguments['javaScript'] != '-1') {
			if (empty($this->arguments['javaScript'])) {
				$tsSettings = $this->pluginSettingsService->getSettings();

				$locale = (!empty($tsSettings['facebookLocale'])) ? $tsSettings['facebookLocale'] : 'en_US';

				$code = '<script src="http://connect.facebook.net/' . $locale . '/all.js#xfbml=1"></script>';

					// Social interaction Google Analytics
				if ($this->pluginSettingsService->getByPath('analytics.social.facebookLike') == 1) {
					$code .= t3lib_div::wrapJS("
						FB.Event.subscribe('edge.create', function(targetUrl) {
						 	_gaq.push(['_trackSocial', 'facebook', 'like', targetUrl]);
						});
						FB.Event.subscribe('edge.remove', function(targetUrl) {
						  _gaq.push(['_trackSocial', 'facebook', 'unlike', targetUrl]);
						});
					");
				}
			} else {
				$code = '<script src="' . htmlspecialchars($this->arguments['javaScript']) . '"></script>';
			}
		}

			// seems as if a div with id fb-root is needed this is just a dirty
			// workaround to make things work again Perhaps we should
			// use the iframe variation.
		$code .= '<div id="fb-root"></div>' . $this->tag->render();
		return $code;
	}

}

?>