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
 * Implementation of quicktime support
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_MediaRenderer_Video_Quicktime implements Tx_News_MediaRenderer_MediaInterface {

	/**
	 * Render quicktime files
	 *
	 * @param Tx_News_Domain_Model_Media $element
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	public function render(Tx_News_Domain_Model_Media $element, $width, $height) {
		$url = Tx_News_Service_FileService::getCorrectUrl($element->getContent());

			// override width & height if both are set
		if ($element->getWidth() > 0 && $element->getHeight() > 0) {
			$width = $element->getWidth();
			$height = $element->getHeight();
		}

		$content =
				'<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="' . (int)$width . '" height="' . (int)$height . '" >
					<param name="src" value="' . htmlspecialchars($url) . '">
					<param name="autoplay" value="true">
					<param name="type" value="video/quicktime" width="' . (int)$width . '" height="' . (int)$height . '">
					<embed src="' . htmlspecialchars($url) . '" width="' . (int)$width . '" height="' . (int)$height . '" autoplay="false" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/">
				</object>';

		return $content;
	}

	/**
	 * Implementation is used if file extension is mov
	 *
	 * @param Tx_News_Domain_Model_Media $element
	 * @return boolean
	 */
	public function enabled(Tx_News_Domain_Model_Media $element) {
		$url = $element->getContent();
		$fileEnding = strtolower(substr($url, -3));

		return ($fileEnding === 'mov');
	}

}
