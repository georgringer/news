<?php

declare(strict_types=1);

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Routing;

use TYPO3\CMS\Core\Routing\Aspect\PersistedAliasMapper;

/**
 * Use this Mapper if you have an alias field (e.g. "path_segment" or "alias") in your TCA setup along
 * a DB table.
 *
 * This mapper solves two issues:
 *
 * 1. It allows to reduce configuration for typical mapper scenarios by just using the
 *    Mapper name as registered and use "autoconfiguration" for table names and field names.
 * 2. It allows to use "fallback handling" which is only available in TYPO3 v12.1
 *    see https://review.typo3.org/c/Packages/TYPO3.CMS/+/76545 to have our News plugin
 *    manage the optional parameter for a news detail page, when a news record was not found.
 */
abstract class AbstractNewsAliasMapper extends PersistedAliasMapper
{
    public function __construct(array $settings)
    {
        $settings['fallbackValue'] = array_key_exists('fallbackValue', $settings) ? $settings['fallbackValue'] : null;
        parent::__construct($settings);
    }
}
