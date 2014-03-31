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
 * ViewHelper to render the page title
 *
 * # Example: Basic Example
 * # Description: Render the content of the VH as page title
 * <code>
 *	<n:titleTag>{newsItem.title}</n:titleTag>
 * </code>
 * <output>
 *	<title>TYPO3 is awesome</title>
 * </output>
 *
 */
class Tx_News_ViewHelpers_TitleTagViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Override the title tag
	 *
	 * @return void
	 */
	public function render() {
		$content = trim($this->renderChildren());
		if (!empty($content)) {
			$GLOBALS['TSFE']->page['title'] = $content;
			$GLOBALS['TSFE']->indexedDocTitle = $content;
		}
	}
}
