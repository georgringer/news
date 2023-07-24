<?php

declare(strict_types=1);

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Routing\Aspect;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Routing\Aspect\StaticRangeMapper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Use this range mapper with care by using
 * $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['LargeStaticRangeMapper']
 *   = \GeorgRinger\News\Routing\Aspect\LargeStaticRangeMapper::class;
 *
 * page:
 *   type: LargeStaticRangeMapper
 *   start: '1'
 *   end: '100'
 *   table: tx_news_domain_model_news
 *   perPage: 10
 */
class LargeStaticRangeMapper extends StaticRangeMapper
{
    public function __construct(array $settings)
    {
        parent::__construct($settings);
        if (isset($settings['table'])) {
            $this->end = (string)$this->getCountOfRecords($settings['table'], (int)($settings['perPage'] ?? 0));
            $this->range = $this->applyNumericPrefix($this->buildRange());
        }
    }

    protected function getCountOfRecords(string $table, int $perPage)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $count = $queryBuilder
            ->count('*')
            ->from($table)
            ->execute()
            ->fetchFirstColumn()[0];

        return (int)ceil($count / $perPage ?: 10);
    }

    protected function buildRange(): array
    {
        $range = array_map('strval', range($this->start, $this->end));
        if (count($range) > $this->getCountOfRecords($this->settings['table'], (int)($this->settings['perPage'] ?? 10))) {
            throw new \LengthException(
                'Range is too large',
                1688735502
            );
        }
        return $range;
    }
}
