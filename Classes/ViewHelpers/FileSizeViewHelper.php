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
 * ViewHelper to render the filesize
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_ViewHelpers_FileSizeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {


	/**
	 * Renders the size of a file using t3lib_div::formatSize
	 * 
	 * @param string $file
	 * @param string $path
	 * @param string $format
	 * @return string
	 */
	public function render($file, $path, $format = '') {

		$cObj = t3lib_div::makeInstance('tslib_cObj');

		$filePath = $path . $file;
		if (!is_file($filePath)) {
			// @todo: better exceptions
			throw new Exception('Given file is not a valid file: ' . htmlspecialchars($filePath));
		}

		$fileSize = t3lib_div::formatSize(filesize($filePath), $format);


		return $fileSize;
	}
}