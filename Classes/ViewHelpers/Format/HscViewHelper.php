<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * ViewHelper for htmlspecialchars
 *
 * @package TYPO3
 * @subpackage tx_news
 * @deprecated Use Tx_Fluid_ViewHelpers_Format_HtmlspecialcharsViewHelper
 */
class Tx_News_ViewHelpers_Format_HscViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

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
