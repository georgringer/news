<?php

declare(strict_types=1);

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Routing;

class NewsTitleMapper extends AbstractNewsAliasMapper
{
    protected $tableName;

    public function __construct(array $settings)
    {
        $settings['tableName'] = $settings['tableName'] ?? 'tx_news_domain_model_news';
        $settings['routeFieldName'] = $settings['routeFieldName'] ?? 'path_segment';
        parent::__construct($settings);
    }
}
