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
 * ViewHelper to download a file
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class Tx_News2_ViewHelpers_Format_FileDownloadViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {


	/**
	 * Download a file
	 *
	 * @param string $file
	 * @param string $path
	 * @param array $configuration
	 * @return string
	 */
	public function render($file, $path = '', array $configuration = array()) {
		$filePath = $path . $file;
		if (!is_file($filePath)) {
			// @todo: better exceptions
			throw new Exception('Given file is not a valid file: ' . htmlspecialchars($filePath));
		}

		$cObj = t3lib_div::makeInstance('tslib_cObj');

		$fileInformation = pathinfo($filePath);

			// set a basic configuration for cObj->filelink
		$tsConfiguration = array(
			'path' => $path,
			'ATagParams' => 'class="download-link basic-class ' . $fileInformation['extension'] . '"',
			'labelStdWrap.' => array(
				'cObject.' => array(
					'value' => $this->renderChildren()
				)
			),

		);

		$configuration = $this->convertExtbaseToClassicTS($configuration);

			// merge default configuration with optional configuration
		$tsConfiguration = t3lib_div::array_merge_recursive_overrule($tsConfiguration, $configuration);

			// generate file
		$file = $cObj->filelink($file, $tsConfiguration);

		return $file;
	}


	/**
	 * Modify TS to fit cObjs
	 *
	 * @todo really needed? check convertExtbaseToClassicTS in extbase_utility
	 * @param array $extBaseTS
	 * @return array convertes TS
	 */
	public function convertExtbaseToClassicTS(array $extBaseTS) {
		$classicTS = array();
		if (is_array($extBaseTS)) {
			foreach ($extBaseTS as $key => $value) {
				if (is_array($value)) {

					$classicTS[$key.'.'] = $this->convertExtbaseToClassicTS($value);
				} else{
					$classicTS[$key] = $value;
				}
			}
		}
		return $classicTS;
	}


}

?>