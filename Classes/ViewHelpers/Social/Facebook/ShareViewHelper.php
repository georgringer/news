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
 * ViewHelper to share content
 * Details: http://developers.facebook.com/docs/reference/plugins/like
 *
 * Examples
 * ==============
 * <n:social.facebook.share>Share</n:social.facebook.share>
 * Result: Facebook widget to share current URL with the text "Share"
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Social_Facebook_ShareViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var Tx_News_Service_SettingsService
	 */
	protected $pluginSettingsService;

	/**
	 * @var	string
	 */
	protected $tagName = 'a';

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
		$this->registerTagAttribute('shareurl', 'string', 'Shared url, default: https://www.facebook.com/sharer/sharer.php');
		$this->registerTagAttribute('name', 'string', 'default: fb_share');
		$this->registerTagAttribute('type', 'string', 'default: button_count');
	}

	/**
	 * Render a share button
	 *
	 * @param boolean $loadJs
	 * @return string
	 */
	public function render($loadJs = TRUE) {
			// check defaults
		if (empty($this->arguments['href'])) {
			$this->tag->addAttribute('href', 'https://www.facebook.com/sharer/sharer.php');
		}
		if (empty($this->arguments['name'])) {
			$this->tag->addAttribute('name', 'fb_share');
		}
		if (empty($this->arguments['type'])) {
			$this->tag->addAttribute('type', 'button_count');
		}

		$shareUrl = empty($this->arguments['shareurl']) ? \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL') : $this->arguments['shareurl'];
		$this->tag->addAttribute('share_url', $shareUrl);
		$this->tag->removeAttribute('shareurl');

		$this->tag->setContent($this->renderChildren());

		$code = $this->tag->render();

		if ($loadJs) {
			$code .= '<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>';
		}

			// Social interaction Google Analytics
		if ($this->pluginSettingsService->getByPath('analytics.social.facebookShare') == 1) {
			$code .= \TYPO3\CMS\Core\Utility\GeneralUtility::wrapJS("
				FB.Event.subscribe('message.send', function(targetUrl) {
				  _gaq.push(['_trackSocial', 'facebook', 'send', targetUrl]);
				});
			");
		}

		return $code;
	}

}
