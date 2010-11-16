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
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_ViewHelpers_Facebook_LikeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('url', 'string', 'Given url, if empty, current url is used');
		$this->registerTagAttribute('layout', 'string', 'Either: standard, button_count or box_count');
		$this->registerTagAttribute('width', 'integer', 'With of widget, default 450');
		$this->registerTagAttribute('font', 'string', 'Font, options are: arial, lucidia grande, segoe ui, tahoma, trebuchet ms, verdana');
		$this->registerTagAttribute('javaScript', 'string', 'JS URL. If not set, default is used, if set to -1 no Js is loaded');
	}

	public function render() {
		$code = '';
		$url = (!empty($this->arguments['layout'])) ? $this->arguments['layout'] : t3lib_div::getIndpEnv('TYPO3_REQUEST_URL');
		$width = ((int)$this->arguments['width']) ? $this->arguments['width'] : 450;
		$layout = (!empty($this->arguments['layout'])) ? $this->arguments['layout'] : 'standard';
		$font = (!empty($this->arguments['font'])) ? $this->arguments['font'] : 'verdana';

		if ($this->arguments['javaScript'] != '-1') {
			if (empty($this->arguments['javaScript'])) {
				$code = '<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>';
			} else {
				$code = '<script src="' . htmlspecialchars($this->arguments['javaScript']) . '"></script>';
			}
		}
		$code .= '<fb:like layout="' . $layout . '" href="' . rawurlencode($url) . '" width="' . (int)$width . '" font="' . $font . '"></fb:like>';

		return $code;
	}

}