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
 * ViewHelper to show videos
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_ViewHelpers_VideoInterfaceViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {
	
	/**
	 * If the escaping interceptor should be disabled inside this ViewHelper, then set this value to FALSE.
	 * This is internal and NO part of the API. It is very likely to change.
	 *
	 * @var boolean
	 * @internal
	 */
	protected $escapingInterceptorEnabled = FALSE;


	/**
	 *
	 * @param string $classes
	 * @param Tx_News2_Domain_Model_Media $element
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	public function render($classes, Tx_News2_Domain_Model_Media $element, $width, $height) {
		$content = '';
		$classList = t3lib_div::trimExplode(',', $classes, TRUE);


		foreach($classList as $classData) {
			$hookObject = t3lib_div::makeInstance($classData);

			if(!($hookObject instanceof Tx_News2_Interfaces_VideoMediaInterface)) {
				throw new UnexpectedValueException('$hookObject must implement interface Tx_News2_Interfaces_VideoMediaInterface', 1227834741);
			}

			$content = $hookObject->render($element, $width, $height);
		}
		
		return $content;
	}

	

}