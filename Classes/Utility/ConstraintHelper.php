<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Utility;

use DateTime;
use Exception;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
    public static function getTimeRestrictionLow($timeInput): int
    {
        $timeLimit = 0;
        // integer = timestamp
        if (MathUtility::canBeInterpretedAsInteger($timeInput)) {
            $currentTimestamp = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
            $timeLimit = (int)$currentTimestamp - $timeInput;
        } else {
            $timeByFormat = DateTime::createFromFormat('HH:mm DD-MM-YYYY', $timeInput);
            if ($timeByFormat) {
                $timeLimit = $timeByFormat->getTimestamp();
            } else {
                // try to check strtotime
                $timeFromString = strtotime($timeInput);

                if ($timeFromString) {
                    $timeLimit = $timeFromString;
                } else {
                    throw new Exception('Time limit Low could not be resolved to an integer. Given was: ' . htmlspecialchars($timeLimit));
                }
            }
        }
        return $timeLimit;
    }

    /**
     * @param string|int $timeInput
     * @return int
     * @throws Exception
     */
    public static function getTimeRestrictionHigh($timeInput): int
    {
        $timeLimit = 0;
        // integer = timestamp
        if (MathUtility::canBeInterpretedAsInteger($timeInput)) {
            $currentTimestamp = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
            $timeLimit = (int)$currentTimestamp + $timeInput;
            return $timeLimit;
        }
        // try to check strtotime
        $timeFromStringHigh = strtotime($timeInput);

        if ($timeFromStringHigh) {
            $timeLimit = $timeFromStringHigh;
            return $timeLimit;
        }
        throw new Exception('Time limit High could not be resolved to an integer. Given was: ' . htmlspecialchars($timeLimit));
    }
}
