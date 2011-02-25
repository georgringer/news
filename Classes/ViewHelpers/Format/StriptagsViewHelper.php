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
 * = Examples =
 *
 * <code title="Defaults">
 * <n:format.striptags><p>This is a test</p></n:format.striptags>
 * </code>
 *
 * Output:
 * This is a test
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 * @see http://de.php.net/manual/de/function.strip-tags.php
 */
class Tx_News2_ViewHelpers_Format_StriptagsViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {


	/**
	 * Strip tags
	 *
	 * @param string $allowTags Allowed tags
	 * @return string
	 */
	public function render($allowTags = '') {

		$content = strip_tags($this->renderChildren(), $allowTags);

		return $content;
	}
}

?>