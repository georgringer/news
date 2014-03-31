<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Georg Ringer <typo3@ringerge.org>
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
 * Validation
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Utility_Validation {

	/**
	 * Validate ordering as extbase can't handle that currently
	 *
	 * @param string $fieldToCheck
	 * @param string $allowedSettings
	 * @return boolean
	 */
	public static function isValidOrdering($fieldToCheck, $allowedSettings) {
		$isValid = TRUE;

		if (empty($fieldToCheck)) {
			return $isValid;
		} elseif (empty($allowedSettings)) {
			return FALSE;
		}

		$fields = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $fieldToCheck, TRUE);
		foreach ($fields as $field) {
			if ($isValid === TRUE) {
				$split = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(' ', $field, TRUE);
				$count = count($split);
				switch ($count) {
					case 1:
						if (!\TYPO3\CMS\Core\Utility\GeneralUtility::inList($allowedSettings, $split[0])) {
							$isValid = FALSE;
						}
						break;
					case 2:
						if ((strtolower($split[1]) !== 'desc' && strtolower($split[1]) !== 'asc') ||
							!\TYPO3\CMS\Core\Utility\GeneralUtility::inList($allowedSettings, $split[0])) {
							$isValid = FALSE;
						}
						break;
					default:
						$isValid = FALSE;
				}
			}
		}

		return $isValid;
	}
}
