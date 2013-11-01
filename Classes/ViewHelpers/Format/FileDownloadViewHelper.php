<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2010 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * ViewHelper to render a download link of a file using $cObj->filelink()
 * # Example: Basic example
 * <code>
 * <n:format.fileDownload file="uploads/tx_news/{relatedFile.file}" configuration="{settings.relatedFiles.download}">
 *    {relatedFile.title}
 * </n:format.fileDownload>
 * </code>
 * <output>
 *  Link to download the file "uploads/tx_news/{relatedFile.file}"
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Format_FileDownloadViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Download a file
	 *
	 * @param string $file Path to the file
	 * @param array $configuration configuration used to render the filelink cObject
	 * @param boolean $hideError define if an error should be displayed if file not found
* 	 * @param string $class optional class
* 	 * @param string $target target
* 	 * @param string $alt alt text
* 	 * @param string $title title text
	 * @return string
	 * @throws Tx_Fluid_Core_ViewHelper_Exception_InvalidVariableException
	 */
	public function render($file, $configuration = array(), $hideError = FALSE, $class = '', $target = '', $alt = '', $title = '') {
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
		$fileInformation['file'] = $file;
		$fileInformation['size'] = filesize($file);
		$cObj->data = $fileInformation;

		// set a basic configuration for cObj->filelink
		$tsConfiguration = array(
			'path' => $fileInformation['dirname'] . '/',
			'ATagParams' => 'class="download-link basic-class ' . strtolower($fileInformation['extension']) . '"',
			'labelStdWrap.' => array(
				'cObject.' => array(
					'value' => $this->renderChildren()
				)
			),

		);

		// Fallback if no configuration given
		if (!is_array($configuration)) {
			$configuration = array('labelStdWrap.' => array('cObject' => 'TEXT'));
		} else {
			if (class_exists('Tx_Extbase_Utility_TypoScript')) {
				$configuration = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray($configuration);
			} else {
				/** @var $typoscriptService Tx_Extbase_Service_TypoScriptService */
				$typoscriptService = t3lib_div::makeInstance('Tx_Extbase_Service_TypoScriptService');
				$configuration = $typoscriptService->convertPlainArrayToTypoScriptArray($configuration);
			}
		}

		// merge default configuration with optional configuration
		$tsConfiguration = t3lib_div::array_merge_recursive_overrule($tsConfiguration, $configuration);

		if (!empty($class)) {
			$tsConfiguration['ATagParams'] .= ' class="' . $class . '"';
		}
		if (!empty($target)) {
			$tsConfiguration['target'] = $target;
		}
		if (!empty($alt)) {
			$tsConfiguration['altText'] = $alt;
		}
		if (!empty($title)) {
			$tsConfiguration['titleText'] = $title;
		}

		// generate link
		$link = $cObj->filelink($fileInformation['basename'], $tsConfiguration);

		return $link;
	}
}
