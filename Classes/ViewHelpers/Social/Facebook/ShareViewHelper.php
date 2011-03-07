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
 * @subpackage tx_news2
 */
class Tx_News2_ViewHelpers_Social_Facebook_ShareViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	/**
	 * @var	string
	 */
	protected $tagName = 'a';

	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('share_url', 'string', 'Shared url, default: http://www.facebook.com/sharer.php');
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
			$this->tag->addAttribute('href', 'http://www.facebook.com/sharer.php');
		}
		if (empty($this->arguments['name'])) {
			$this->tag->addAttribute('name', 'fb_share');
		}
		if (empty($this->arguments['type'])) {
			$this->tag->addAttribute('type', 'button_count');
		}
		if (empty($this->arguments['share_url'])) {
			$this->tag->addAttribute('share_url', t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'));
		}

		$this->tag->setContent($this->renderChildren());

		$code = $this->tag->render();

		if ($loadJs) {
			$code .= '<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>';
		}

		return $code;
	}

}

?>