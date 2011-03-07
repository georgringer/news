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
 * ViewHelper to render news elements only once on a page
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class Tx_News2_ViewHelpers_UniqueViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {


	/**
	 * Test for showing news only once, should not be used
	 *
	 * @param  integer $newsUid news uid
	 * @param string $view view
	 * @param boolean $debug if TRUE, additonal message is returned
	 * @return string
	 */
	public function render($newsUid, $view, $debug = FALSE) {
		$show = TRUE;
		if (!is_array($GLOBALS['TSFE']->displayedNews2)) {
				$GLOBALS['TSFE']->displayedNews2 = array();
		} else {
			if (isset($GLOBALS['TSFE']->displayedNews2[$newsUid])) {
				$show = FALSE;
			}
		}

		if ($view == 'list' || $view == 'latest') {
			$GLOBALS['TSFE']->displayedNews2[$newsUid] = 1;
		}

		if (!$show && ($view == 'list' || $view == 'latest')) {
			if ($debug) {
				return '<tr><td colspan="6">not rendered, uid = ' . $newsUid . '</td><tr>';
			} else {
				return '';
			}

		} else {
			return $this->renderChildren();
		}

		return '';
	}
}

?>