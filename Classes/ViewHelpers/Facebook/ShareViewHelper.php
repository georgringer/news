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
 * <n:facebook.share text="Teilen" />
 * Result: Facebook widget to share current URL with the text "Teilen"
 *
 * <n:facebook.share text="Share it with your friends" url="http://www.typo3.org" />
 * Result: Facebook widget to share www.typo3.org with the text "Share with your friends"
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_ViewHelpers_Facebook_ShareViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('url', 'string', 'Given url, if empty, current url is used');
		$this->registerTagAttribute('text', 'string', 'Given text', TRUE);
	}

	public function render() {
		$url = (!empty($this->arguments['layout'])) ? $this->arguments['layout'] : t3lib_div::getIndpEnv('TYPO3_REQUEST_URL');

		$code = '<a name="fb_share" type="button_count" share_url="' . rawurlencode($url) . '" href="http://www.facebook.com/sharer.php">' . htmlspecialchars($this->arguments['text']) . '</a>
					<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>';

		return $code;
	}

}