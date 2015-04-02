<?php

namespace GeorgRinger\News\MediaRenderer\Video;

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
use GeorgRinger\News\MediaRenderer\MediaInterface;
use GeorgRinger\News\Service\FileService;

/**
 * Implementation of quicktime support
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Quicktime implements MediaInterface {

	/**
	 * Render quicktime files
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	public function render(\GeorgRinger\News\Domain\Model\Media $element, $width, $height) {
		$url = FileService::getCorrectUrl($element->getContent());

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
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @return boolean
	 */
	public function enabled(\GeorgRinger\News\Domain\Model\Media $element) {
		$url = FileService::getFalFilename($element->getContent());
		$fileEnding = strtolower(substr($url, -3));

		return ($fileEnding === 'mov');
	}

}
