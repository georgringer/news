<?php

namespace GeorgRinger\News\Service;

use GeorgRinger\News\Service\Transliterator\Transliterator;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class SlugService
{

    /**
     * @param string $string
     * @return string
     */
    public function generateSlug(string $string): string
    {
        return Transliterator::urlize($string);
    }


    public function countOfSlugUpdates(): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
        $queryBuilder->getRestrictions()->removeAll();
        $elementCount = $queryBuilder->count('uid')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('path_segment', $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)),
                    $queryBuilder->expr()->isNull('path_segment')
                )
            )
            ->execute()->fetchColumn(0);

        return $elementCount;
    }

    public function performUpdates(): array
    {
        $databaseQueries = [];

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_news_domain_model_news');
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder->select('uid', 'title')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('path_segment', $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)),
                    $queryBuilder->expr()->isNull('path_segment')
                )
            )
            ->execute();
        while ($record = $statement->fetch()) {
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update('tx_news_domain_model_news')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('path_segment', $this->generateSlug((string)$record['title']));
            $databaseQueries[] = $queryBuilder->getSQL();
            $queryBuilder->execute();
        }

        return $databaseQueries;
    }
}