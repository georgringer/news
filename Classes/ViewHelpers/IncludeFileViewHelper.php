<?php

namespace GeorgRinger\News\ViewHelpers;

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
 * ViewHelper to include a css/js file
 *
 * # Example: Basic example
 * <code>
 * <n:includeFile path="{settings.cssFile}" />
 * </code>
 * <output>
 * This will include the file provided by {settings} in the header
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class IncludeFileViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Include a CSS/JS file
	 *
	 * @param string $path Path to the CSS/JS file which should be included
	 * @param boolean $compress Define if file should be compressed
	 * @return void
	 */
	public function render($path, $compress = FALSE) {
		if (TYPO3_MODE === 'FE') {
			$path = $GLOBALS['TSFE']->tmpl->getFileName($path);

				// JS
			if (strtolower(substr($path, -3)) === '.js') {
				$GLOBALS['TSFE']->getPageRenderer()->addJsFile($path, NULL, $compress);

				// CSS
			} elseif (strtolower(substr($path, -4)) === '.css') {
				$GLOBALS['TSFE']->getPageRenderer()->addCssFile($path, 'stylesheet', 'all', '', $compress);
			}
		} else {
			$doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate');
			$pageRenderer = $doc->getPageRenderer();

				// JS
			if (strtolower(substr($path, -3)) === '.js') {
				$pageRenderer->addJsFile($path, NULL, $compress);

				// CSS
			} elseif (strtolower(substr($path, -4)) === '.css') {
				$pageRenderer->addCssFile($path, 'stylesheet', 'all', '', $compress);
			}
		}
	}

}
