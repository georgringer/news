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
 * Url Utility class
 *
 */
class Url
{

    /**
     * Prepend current url if url is relative
     *
     * @param string $url given url
     * @return string
     */
    public static function prependDomain($url)
    {
        if (!GeneralUtility::isFirstPartOfStr($url, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'))) {
            $url = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $url;
        }

        return $url;
    }
}
