<?php

namespace GeorgRinger\News\MediaRenderer;

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
 * Interface to implement video views
 *
 * @package TYPO3
 * @subpackage tx_news
 */
interface MediaInterface {

	/**
	 * Render a media element
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	public function render(\GeorgRinger\News\Domain\Model\Media $element, $width, $height);

	/**
	 * If enabled
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @return string
	 */
	public function enabled(\GeorgRinger\News\Domain\Model\Media $element);

}
