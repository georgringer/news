<?php

declare(strict_types=1);

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Routing;

class NewsCategoryMapper extends AbstractNewsAliasMapper
{
    public function __construct(array $settings)
    {
        $settings['tableName'] = $settings['tableName'] ?? 'sys_category';
        $settings['routeFieldName'] = $settings['routeFieldName'] ?? 'slug';
        parent::__construct($settings);
    }
}
