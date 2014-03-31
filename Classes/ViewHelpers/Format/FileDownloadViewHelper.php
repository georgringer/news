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
class Tx_News_ViewHelpers_Format_FileDownloadViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

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
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 */
	public function render($file, $configuration = array(), $hideError = FALSE, $class = '', $target = '', $alt = '', $title = '') {
		if (!is_file($file)) {
			$errorMessage = sprintf('Given file "%s" for %s is not valid', htmlspecialchars($file), get_class());
			\TYPO3\CMS\Core\Utility\GeneralUtility::devLog($errorMessage, 'news', \TYPO3\CMS\Core\Utility\GeneralUtility::SYSLOG_SEVERITY_WARNING);

			if (!$hideError) {
				throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException(
					'Given file is not a valid file: ' . htmlspecialchars($file));
			}
		}

		$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tslib_cObj');

		$fileInformation = pathinfo($file);
		$fileInformation['file'] = $file;
		$fileInformation['size'] = filesize($file);
		$cObj->data = $fileInformation;

		// set a basic configuration for cObj->filelink
		$tsConfiguration = array(
			'path' => $fileInformation['dirname'] . '/',
			'ATagParams' => 'class="download-link basic-class ' . strtolower($fileInformation['extension']) . (!empty($class) ? ' ' . $class : '') . '"',
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
			/** @var $typoscriptService \TYPO3\CMS\Extbase\Service\TypoScriptService */
			$typoscriptService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\TypoScriptService');
			$configuration = $typoscriptService->convertPlainArrayToTypoScriptArray($configuration);
		}

		// merge default configuration with optional configuration
		\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($tsConfiguration, $configuration);

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
