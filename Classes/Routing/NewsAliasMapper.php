<?php

declare(strict_types=1);

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Routing;

use TYPO3\CMS\Core\Information\Typo3Version;
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
 *    manage the optional parameter for a news detail page, when a news was not found.
 *    For v12 setups, the fallbackValue is automatically set to "null" to keep the same functionality
 *    as in v11 with this news mapper.
 *
 * @deprecated File is not used by news extension anymore and will be removed with version 12
 */
class NewsAliasMapper extends PersistedAliasMapper
{
    public function __construct(array $settings)
    {
        // Use the shorthand to reduce configuration overhead
        switch ($settings['type']) {
            case 'NewsCategory':
                $settings['tableName'] = $settings['tableName'] ?? 'sys_category';
                $settings['routeFieldName'] = $settings['routeFieldName'] ?? 'slug';
                $settings['fallbackValue'] = array_key_exists('fallbackValue', $settings) ? $settings['fallbackValue'] : null;
                break;
            case 'NewsTag':
                $settings['tableName'] = $settings['tableName'] ?? 'tx_news_domain_model_tag';
                $settings['routeFieldName'] = $settings['routeFieldName'] ?? 'slug';
                $settings['fallbackValue'] = array_key_exists('fallbackValue', $settings) ? $settings['fallbackValue'] : null;
                break;
            case 'NewsTitle':
            default:
                $settings['tableName'] = $settings['tableName'] ?? 'tx_news_domain_model_news';
                $settings['routeFieldName'] = $settings['routeFieldName'] ?? 'path_segment';
                $settings['fallbackValue'] = array_key_exists('fallbackValue', $settings) ? $settings['fallbackValue'] : null;
        }
        parent::__construct($settings);
    }

    public function resolve(string $value): ?string
    {
        $result = parent::resolve($value);
        // Fallback Layer for v11
        if ((new Typo3Version())->getMajorVersion() < 12) {
            if ($result === null) {
                return '0';
            }
        }
        return $result;
    }
}
