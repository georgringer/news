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
 * ViewHelper to share content
 * Details: https://developers.facebook.com/docs/plugins/share-button/
 *
 * Examples
 * ==============
 * <n:social.facebook.share />
 * Result: Facebook widget to share current URL with the Facebook button
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Social_Facebook_ShareViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var Tx_News_Service_SettingsService
	 */
	protected $pluginSettingsService;

	/** @var string */
	protected $tagName = 'div';

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
		$this->registerTagAttribute('type', 'string', 'default: button_count');
                $this->registerTagAttribute('app_id','string','Your apps unique identifier. Required.');
	}

	/**
	 * Render a share button
	 *
	 * @param boolean $loadJs
	 * @return string
	 */
	public function render($loadJs = TRUE) {
                $tsSettings = $this->pluginSettingsService->getSettings();
                
		if (!empty($this->arguments['type'])) {
			$this->tag->addAttribute('data-type', $this->arguments['type']);
			$this->tag->removeAttribute('type');
		} else {
			$this->tag->addAttribute('data-type', 'button_count');
		}

		$shareUrl = empty($this->arguments['shareurl']) ? \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL') : $this->arguments['shareurl'];
		$this->tag->addAttribute('data-href', $shareUrl);
		$this->tag->removeAttribute('shareurl');
                
                $fbAppId = (!empty($tsSettings['facebookAppId'])) ? $tsSettings['facebookAppId'] : '';
                
                if (!empty($fbAppId)) {
                    $this->tag->addAttribute('app_id',$fbAppId);
                } else {    
                    $this->tag->removeAttribute('app_id');
                }
		$this->tag->addAttribute('class', 'fb-share-button');
		$this->tag->setContent(' ');

		$code = $this->tag->render();
		if ($loadJs) {
			$code .= '<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/de_DE/sdk.js";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, \'script\', \'facebook-jssdk\'));</script>';
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
