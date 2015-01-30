<?php

namespace GeorgRinger\News\Utility;

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
 * Validation
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Validation {

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
