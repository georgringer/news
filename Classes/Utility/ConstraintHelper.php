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
use Exception;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Helper class for building constraints
 */
class ConstraintHelper
{

    /**
     * @param string|int $timeInput
     * @return int
     * @throws Exception
     */
    public static function getTimeRestrictionLow($timeInput)
    {
        $timeLimit = 0;
        // integer = timestamp
        if (MathUtility::canBeInterpretedAsInteger($timeInput)) {
            $timeLimit = $GLOBALS['EXEC_TIME'] - $timeInput;
            return $timeLimit;
        } else {
            // try to check strtotime
            $timeFromString = strtotime($timeInput);

            if ($timeFromString) {
                $timeLimit = $timeFromString;
                return $timeLimit;
            } else {
                throw new Exception('Time limit Low could not be resolved to an integer. Given was: ' . htmlspecialchars($timeLimit));
            }
        }
    }

    /**
     * @param string|int $timeInput
     * @return int
     * @throws Exception
     */
    public static function getTimeRestrictionHigh($timeInput)
    {
        $timeLimit = 0;
        // integer = timestamp
        if (MathUtility::canBeInterpretedAsInteger($timeInput)) {
            $timeLimit = $GLOBALS['EXEC_TIME'] + $timeInput;
            return $timeLimit;
        } else {
            // try to check strtotime
            $timeFromStringHigh = strtotime($timeInput);

            if ($timeFromStringHigh) {
                $timeLimit = $timeFromStringHigh;
                return $timeLimit;
            } else {
                throw new Exception('Time limit High could not be resolved to an integer. Given was: ' . htmlspecialchars($timeLimit));
            }
        }
    }
}
