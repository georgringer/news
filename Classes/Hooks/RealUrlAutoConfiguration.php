<?php

namespace GeorgRinger\News\Hooks;

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
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * AutoConfiguration-Hook for RealURL
 *
 */
class RealUrlAutoConfiguration
{

    /**
     * Generates additional RealURL configuration and merges it with provided configuration
     *
     * @param       array $params Default configuration
     * @return      array Updated configuration
     */
    public function addNewsConfig($params)
    {

        // Check for proper unique key
        $postVar = (ExtensionManagementUtility::isLoaded('tt_news') ? 'tx_news' : 'news');

        return array_merge_recursive($params['config'], [
                'postVarSets' => [
                    '_DEFAULT' => [
                        $postVar => [
                            [
                                'GETvar' => 'tx_news_pi1[news]',
                                'lookUpTable' => [
                                    'table' => 'tx_news_domain_model_news',
                                    'id_field' => 'uid',
                                    'alias_field' => 'title',
                                    'useUniqueCache' => 1,
                                    'useUniqueCache_conf' => [
                                        'strtolower' => 1,
                                        'spaceCharacter' => '-',
                                    ],
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        );
    }
}
