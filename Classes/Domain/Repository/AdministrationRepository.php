<?php

namespace GeorgRinger\News\Domain\Repository;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Get data used in the administration view
 */
class AdministrationRepository
{
    public function getTotalCounts(): array
    {
        $count = [];

        $queryBuilder = $this->getQueryBuilder('tx_news_domain_model_news');
        $queryBuilder->getRestrictions()->removeAll();

        $count['tx_news_domain_model_news'] = $queryBuilder
            ->count('*')
            ->from('tx_news_domain_model_news')
            ->execute()->fetchColumn(0);

        $queryBuilder = $this->getQueryBuilder('sys_category_record_mm');
        $count['category_relations'] = $queryBuilder
            ->count('*')
            ->from('sys_category_record_mm')
            ->where($queryBuilder->expr()->like(
                'tablenames',
                $queryBuilder->createNamedParameter('tx_news_domain_model_news', \PDO::PARAM_STR)
            ))
            ->execute()->fetchColumn(0);

        if ($count['tx_news_domain_model_news'] > 0 && $count['category_relations']) {
            $count['_both'] = true;
        }

        return $count;
    }

    private function getConnection(string $table): Connection
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
    }

    private function getQueryBuilder(string $table): QueryBuilder
    {
        return $this->getConnection($table)->createQueryBuilder();
    }
}
