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
 */
class Tx_News_ViewHelpers_Format_FileSizeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {


	/**
	 * Renders the size of a file using t3lib_div::formatSize
	 *
	 * @param string $file
	 * @param string $format
	 * @param boolean $hideError
	 * @return string
	 */
	public function render($file, $format = '', $hideError = FALSE) {
		if (!is_file($file)) {
			$errorMessage = sprintf('Given file "%s" for %s is not valid', htmlspecialchars($file), get_class());
			t3lib_div::devLog($errorMessage, 'news', t3lib_div::SYSLOG_SEVERITY_WARNING);

			if (!$hideError) {
				throw new Tx_Fluid_Core_ViewHelper_Exception_InvalidVariableException(
					'Given file is not a valid file: ' . htmlspecialchars($file));
			}
		}

		$fileSize = t3lib_div::formatSize(filesize($file), $format);

		return $fileSize;
	}
}

?>