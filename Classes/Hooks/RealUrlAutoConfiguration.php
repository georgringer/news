<?php

namespace GeorgRinger\News\Hooks;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
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
                                    'alias_field' => 'IF(path_segment!="",path_segment,title)',
                                    'addWhereClause' => ' AND NOT deleted',
                                    'useUniqueCache' => 1,
                                    'expireDays' => 180,
                                    'enable404forInvalidAlias' => true,
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        );
    }
}
