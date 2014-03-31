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
 * # Example: Basic example
 * # Description: If format is empty, the default from t3lib_div:::formatSize() is taken.
 * <code>
 * <n:format.fileSize file="uploads/tx_news/{relatedFile.file}" format="' | K| M| G'" />
 * </code>
 * <output>
 *  3 M
 * </output>
 *
 * # Example: FAL example
 * # Description: If format is empty, the default from t3lib_div:::formatSize() is taken.
 * <code>
 * <n:format.fileSize fileSize="{falRelatedFile.originalResource.size}" format="' | K| M| G'" />
 * </code>
 * <output>
 *  3 M
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Format_FileSizeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Renders the size of a file using t3lib_div::formatSize
	 *
	 * @param string $file Path to the file
	 * @param string $format Labels for bytes, kilo, mega and giga separated by vertical bar (|) and possibly encapsulated in "". Eg: " | K| M| G" (which is the default value)
	 * @param boolean $hideError Define if an error should be displayed if file not found
	 * @param integer $fileSize File size
	 * @return string
	 * @throws Tx_Fluid_Core_ViewHelper_Exception_InvalidVariableException
	 */
	public function render($file = NULL, $format = '', $hideError = FALSE, $fileSize = NULL) {

		if (!is_file($file)) {
			$errorMessage = sprintf('Given file "%s" for %s is not valid', htmlspecialchars($file), get_class());
			t3lib_div::devLog($errorMessage, 'news', t3lib_div::SYSLOG_SEVERITY_WARNING);

			if (!$hideError) {
				throw new Tx_Fluid_Core_ViewHelper_Exception_InvalidVariableException(
					'Given file is not a valid file: ' . htmlspecialchars($file));
			}
		}

		if ($fileSize === NULL) {
			$result = t3lib_div::formatSize(filesize($file), $format);
		} else {
			$result = t3lib_div::formatSize($fileSize, $format);
		}

		return $result;
	}
}
