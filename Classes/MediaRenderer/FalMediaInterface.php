<?php
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
interface Tx_News_MediaRenderer_FalMediaInterface {

	/**
	 * Render a media element
	 *
	 * @param Tx_News_Domain_Model_FileReference $element
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	public function render(Tx_News_Domain_Model_FileReference $element, $width, $height);

	/**
	 * If enabled
	 *
	 * @param Tx_News_Domain_Model_FileReference $element
	 * @return string
	 */
	public function enabled(Tx_News_Domain_Model_FileReference $element);

}
