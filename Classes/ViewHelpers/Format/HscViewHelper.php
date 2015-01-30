<?php

namespace GeorgRinger\News\ViewHelpers\Format;

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
 * ViewHelper for htmlspecialchars
 *
 * @package TYPO3
 * @subpackage tx_news
 * @deprecated Use Tx_Fluid_ViewHelpers_Format_HtmlspecialcharsViewHelper
 */
class HscViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render content with htmlspecialchars
	 *
	 * @return string Formatted date
	 * @deprecated Use Tx_Fluid_ViewHelpers_Format_HtmlspecialcharsViewHelper instead
	 */
	public function render() {
		if (class_exists('Tx_Fluid_ViewHelpers_Format_HtmlspecialcharsViewHelper')) {
			$message = 'EXT:news: Since TYPO3 4.6.0, a native ViewHelper for htmlspecialchars() ' .
			'is available, use f:format.htmlspecialchars instead of n:format.hsc';

			\TYPO3\CMS\Core\Utility\GeneralUtility::deprecationLog($message);
		}
		return htmlspecialchars($this->renderChildren());
	}
}
