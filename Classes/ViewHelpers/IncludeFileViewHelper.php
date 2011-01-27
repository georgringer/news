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
 * ViewHelper to include a css/js file
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_ViewHelpers_IncludeFileViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Include a CSS/JS file
	 * @param string $path path to the file
	 * @param boolean $compress if file should be compressed
	 */
	public function render($path, $compress = FALSE) {
		$path = $GLOBALS['TSFE']->tmpl->getFileName($path);
		
		if ($path) {
				// JS
			if (strtolower(substr($path, -3)) === '.js') {
				$GLOBALS['TSFE']->getPageRenderer()->addJsFile($path, NULL, $compress);
			
				// CSS
			} elseif (strtolower(substr($path, -4)) === '.css') {
				$GLOBALS['TSFE']->getPageRenderer()->addCssFile($path, $rel = 'stylesheet', $media = 'all', $title = '', $compress);
			}
		}
	}

}

?>