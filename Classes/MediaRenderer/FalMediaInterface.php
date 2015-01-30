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

use GeorgRinger\News\Domain\Model\FileReference;

/**
 * Interface to implement video views
 *
 * @package TYPO3
 * @subpackage tx_news
 */
interface FalMediaInterface {

	/**
	 * Render a media element
	 *
	 * @param FileReference $element
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	public function render(FileReference $element, $width, $height);

	/**
	 * If enabled
	 *
	 * @param FileReference $element
	 * @return string
	 */
	public function enabled(FileReference $element);

}
