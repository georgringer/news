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
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Format_FileDownloadViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {
	
	/**
	 * @var Tx_Extbase_Service_TypoScriptService
	 */
	protected $typoScriptService;

	/**
	 * @param Tx_Extbase_Service_TypoScriptService $typoScriptService
	 */
	public function injectTypoScriptService(Tx_Extbase_Service_TypoScriptService $typoScriptService) {
		$this->typoScriptService = $typoScriptService;
	}

	/**
	 * Download a file
	 *
	 * @param string $file file
	 * @param array $configuration
	 * @param boolean $hideError
	 * @return string
	 */
	public function render($file, array $configuration = array(), $hideError = FALSE) {
		if (!is_file($file)) {
			$errorMessage = sprintf('Given file "%s" for %s is not valid', htmlspecialchars($file), get_class());
			t3lib_div::devLog($errorMessage, 'news', t3lib_div::SYSLOG_SEVERITY_WARNING);

			if (!$hideError) {
				throw new Tx_Fluid_Core_ViewHelper_Exception_InvalidVariableException(
					'Given file is not a valid file: ' . htmlspecialchars($file));
			}
		}

		$cObj = t3lib_div::makeInstance('tslib_cObj');

		$fileInformation = pathinfo($file);

			// set a basic configuration for cObj->filelink
		$tsConfiguration = array(
			'path' => $fileInformation['dirname'] . '/',
			'ATagParams' => 'class="download-link basic-class ' . $fileInformation['extension'] . '"',
			'labelStdWrap.' => array(
				'cObject.' => array(
					'value' => $this->renderChildren()
				)
			),

		);

		if (class_exists('Tx_Extbase_Utility_TypoScript')) {
			$configuration = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray($configuration);
		} else {
			$configuration = $this->typoScriptService->convertPlainArrayToTypoScriptArray($configuration);
		}

			// merge default configuration with optional configuration
		$tsConfiguration = t3lib_div::array_merge_recursive_overrule($tsConfiguration, $configuration);

			// generate file
		$file = $cObj->filelink($fileInformation['basename'], $tsConfiguration);

		return $file;
	}
}

?>
