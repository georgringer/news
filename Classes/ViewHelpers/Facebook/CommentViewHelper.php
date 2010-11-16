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
 * ViewHelper to comment content
 * Details: http://developers.facebook.com/docs/reference/plugins/comments
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
class Tx_News2_ViewHelpers_Facebook_CommentViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('identifier', 'string', 'An id associated with the comments object (defaults to URL-encoded page URL)');
		$this->registerTagAttribute('count', 'integer', 'the number of comments to display, or 0 to hide all comments');
		$this->registerTagAttribute('width', 'integer', 'The width of the plugin in px, default = 425');
		$this->registerTagAttribute('publishFeed', 'boolean', 'Whether the publish feed story checkbox is checked., default = TRUE');
	}

	public function render() {
		$url = (!empty($this->arguments['identifier'])) ? ' xid="' . rawurlencode($this->arguments['identifier']) . '"' : '';
		$width = !empty($this->arguments['width']) ? ' width="' . $this->arguments['width'] . '"' : '';

			// @todo: check that
		$code = '<div id="fb-root"></div>
					<script src="http://connect.facebook.net/en_US/all.js#appId=APP_ID&amp;xfbml=1"></script>
					<fb:comments ' . $url . $width . '></fb:comments>';

		return $code;
	}

}