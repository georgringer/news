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
 * Compatibility class which is used as wrapper for deprecated functions
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Utility_Compatibility {

	/**
	 * Returns TRUE if the current TYPO3 version is compatible to the input version
	 * Notice that this function compares branches, not versions
	 * (4.0.1 would be > 4.0.0 although they use the same compat_version)
	 *
	 * @param string $verNumberStr Minimum branch number: format "4.0" NOT "4.0.0"
	 * @return boolean Returns TRUE if compatible with the provided version number
	 */
	public static function convertVersionNumberToInteger($verNumberStr) {
		$result = '';
		if (class_exists('t3lib_utility_VersionNumber')) {
			$result = t3lib_utility_VersionNumber::convertVersionNumberToInteger($verNumberStr);
		} else {
			$result = t3lib_div::int_from_ver($verNumberStr);
		}

		return $result;
	}

	/**
	 * Forces the integer $theInt into the boundaries of $min and $max.
	 * If the $theInt is 'FALSE' then the $zeroValue is applied.
	 *
	 * @param integer $theInt Input value
	 * @param integer $min Lower limit
	 * @param integer $max Higher limit
	 * @param integer $zeroValue Default value if input is FALSE.
	 * @return integer The input value forced into the boundaries of $min and $max
	 */
	public static function forceIntegerInRange($theInt, $min, $max = 2000000000, $zeroValue = 0) {
		$result = '';
		if (class_exists('t3lib_utility_Math')) {
			$result = t3lib_utility_Math::forceIntegerInRange($theInt, $min, $max, $zeroValue);
		} else {
			$result = t3lib_div::intInRange($theInt, $min, $max, $zeroValue);
		}

		return $result;
	}

	/**
	 * Tests if the input can be interpreted as integer.
	 *
	 * @param mixed $var Any input variable to test
	 * @return boolean Returns TRUE if string is an integer
	 */
	public static function canBeInterpretedAsInteger($var) {
		$result = '';
		if (class_exists('t3lib_utility_Math')) {
			$result = t3lib_utility_Math::canBeInterpretedAsInteger($var);
		} else {
			$result = t3lib_div::testInt($var);
		}

		return $result;
	}

	/**
	 * Returns the $integer if greater than zero, otherwise returns zero.
	 *
	 * @param integer $theInt Integer string to process
	 * @return integer
	 */
	public static function convertToPositiveInteger($theInt) {
		$result = '';
		if (class_exists('t3lib_utility_Math')) {
			$result = t3lib_utility_Math::convertToPositiveInteger($theInt);
		} else {
			$result = t3lib_div::intval_positive($theInt);
		}

		return $result;
	}

	/**
	 * Gets list of loaded extensions.
	 *
	 * @return string List of loaded extensions
	 */
	public static function getLoadedExtensionList() {
		$result = '';
		if (self::convertVersionNumberToInteger(TYPO3_version) <  '6000000') {
			$result = t3lib_extMgm::getEnabledExtensionList();
		} else {
			$result = implode(',', t3lib_extMgm::getLoadedExtensionListArray());
		}

		return $result;
	}
}
?>