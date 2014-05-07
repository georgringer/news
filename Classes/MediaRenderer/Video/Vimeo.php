<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Maarten Mandemakers
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 */

/**
 * Implementation of Vimeo support
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_MediaRenderer_Video_Vimeo implements Tx_News_MediaRenderer_MediaInterface {

	/**
	 * Render videos from vimeo
	 *
	 * @param Tx_News_Domain_Model_Media $element
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	public function render(Tx_News_Domain_Model_Media $element, $width, $height) {
		$content = '';

		$url = $this->getVimeoUrl($element);

		if ($url !== NULL) {
			// override width & height if both are set
			if ($element->getWidth() > 0 && $element->getHeight() > 0) {
				$width = $element->getWidth();
				$height = $element->getHeight();
			}
			$content = '<iframe src="' . htmlspecialchars($url) . '" width="' . (int)$width . '" height="' . (int)$height . '" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		}

		return $content;
	}

	/**
	 * Check if given element includes an url to a vimeo video
	 *
	 * @param Tx_News_Domain_Model_Media $element
	 * @return boolean
	 */
	public function enabled(Tx_News_Domain_Model_Media $element) {
		$result = FALSE;
		$url = $this->getVimeoUrl($element);
		if ($url !== NULL) {
			$result = TRUE;
		}
		return $result;
	}


	/**
	 * Get Vimeo url
	 *
	 * @param Tx_News_Domain_Model_Media $element
	 * @return null|string
	 */
	public function getVimeoUrl(Tx_News_Domain_Model_Media $element) {
		$videoId = NULL;
		$vimeoUrl = NULL;

		if (preg_match('/vimeo.com\/([0-9]+)/', $element->getContent(), $matches)) {
			$videoId = $matches[1];
		}

		if ($videoId) {
			$vimeoUrl = '//player.vimeo.com/video/' . $videoId . '';
		}

		return $vimeoUrl;
	}

}

