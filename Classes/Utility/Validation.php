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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Validation
 *
 */
class Validation
{

    /**
     * Validate ordering as extbase can't handle that currently
     *
     * @param string $fieldToCheck
     * @param string $allowedSettings
     * @return bool
     */
    public static function isValidOrdering($fieldToCheck, $allowedSettings)
    {
        $isValid = true;

        if (empty($fieldToCheck)) {
            return $isValid;
        } elseif (empty($allowedSettings)) {
            return false;
        }

        $fields = GeneralUtility::trimExplode(',', $fieldToCheck, true);
        foreach ($fields as $field) {
            if ($isValid === true) {
                $split = GeneralUtility::trimExplode(' ', $field, true);
                $count = count($split);
                switch ($count) {
                    case 1:
                        if (!GeneralUtility::inList($allowedSettings, $split[0])) {
                            $isValid = false;
                        }
                        break;
                    case 2:
                        if ((strtolower($split[1]) !== 'desc' && strtolower($split[1]) !== 'asc') ||
                            !GeneralUtility::inList($allowedSettings, $split[0])
                        ) {
                            $isValid = false;
                        }
                        break;
                    default:
                        $isValid = false;
                }
            }
        }

        return $isValid;
    }
}
