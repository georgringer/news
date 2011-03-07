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
 * <n:facebook.comment appId="165193833530000" xid="news-{newsItem.uid}"></n:facebook.comment>
 * Result: Facebook widget to comment an article
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class Tx_News2_ViewHelpers_Facebook_CommentViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	/**
	 * @var	string
	 */
	protected $tagName = 'fb:comments';

	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('xid', 'string', 'An id associated with the comments object (defaults to URL-encoded page URL)', TRUE);
		$this->registerTagAttribute('numposts', 'integer', 'the number of comments to display, or 0 to hide all comments');
		$this->registerTagAttribute('width', 'integer', 'The width of the plugin in px, default = 425');
		$this->registerTagAttribute('publishFeed', 'boolean', 'Whether the publish feed story checkbox is checked., default = TRUE');
	}

	/**
	 * Render facebook comment viewhelper
	 *
	 * @param string $appId
	 * @return string
	 */
	public function render($appId) {
		$code = '<div id="fb-root"></div>
					<script src="http://connect.facebook.net/en_US/all.js#appId=' . htmlspecialchars($appId) . '&amp;xfbml=1"></script>';
		$code .= $this->tag->render();

		return $code;
	}

}

?>