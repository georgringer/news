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

    /**
     * Count valid entries from EXT:realurl table tx_realurl_uniqalias which can be migrated
     * Checks also for existance of third party extension table 'tx_realurl_uniqalias'
     * EXT:realurl requires not to be installed
     *
     * @return int
     */
    public function countOfRealurlAliasMigrations(): int
    {
        $elementCount = 0;
        // Check if table 'tx_realurl_uniqalias' exists
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_realurl_uniqalias');
        $schemaManager = $queryBuilder->getConnection()->getSchemaManager();
        if ($schemaManager->tablesExist(['tx_realurl_uniqalias']) === true) {
            // Count valid aliases for news
            $queryBuilder->getRestrictions()->removeAll();
            $elementCount = $queryBuilder->selectLiteral('COUNT(DISTINCT tx_news_domain_model_news.uid)')
                ->from('tx_realurl_uniqalias')
                ->join(
                    'tx_realurl_uniqalias',
                    'tx_news_domain_model_news',
                    'tx_news_domain_model_news',
                    $queryBuilder->expr()->eq(
                        'tx_realurl_uniqalias.value_id',
                        $queryBuilder->quoteIdentifier('tx_news_domain_model_news.uid')
                    )
                )
                ->where(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->eq(
                                'tx_news_domain_model_news.path_segment',
                                $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)
                            ),
                            $queryBuilder->expr()->isNull('tx_news_domain_model_news.path_segment')
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_news_domain_model_news.sys_language_uid',
                            'tx_realurl_uniqalias.lang'
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_realurl_uniqalias.tablename',
                            $queryBuilder->createNamedParameter('tx_news_domain_model_news', \PDO::PARAM_STR)
                        ),
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->eq(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                            ),
                            $queryBuilder->expr()->gte(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter($GLOBALS['ACCESS_TIME'], \PDO::PARAM_INT)
                            )
                        )
                    )
                )
                ->execute()->fetchColumn(0);
        }
        return $elementCount;
    }

    /**
     * Perform migration of EXT:realurl unique alias into empty news slugs
     *
     * @return array
     */
    public function performRealurlAliasMigration(): array
    {
        $databaseQueries = [];

        // Check if table 'tx_realurl_uniqalias' exists
        $queryBuilderForRealurl = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_realurl_uniqalias');
        $schemaManager = $queryBuilderForRealurl->getConnection()->getSchemaManager();
        if ($schemaManager->tablesExist(['tx_realurl_uniqalias']) === true) {
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_news_domain_model_news');
            $queryBuilder = $connection->createQueryBuilder();

            // Get entries to update
            $statement = $queryBuilder
                ->selectLiteral(
                    'DISTINCT tx_news_domain_model_news.uid, tx_realurl_uniqalias.value_alias, tx_news_domain_model_news.uid'
                )
                ->from('tx_news_domain_model_news')
                ->join(
                    'tx_news_domain_model_news',
                    'tx_realurl_uniqalias',
                    'tx_realurl_uniqalias',
                    $queryBuilder->expr()->eq(
                        'tx_news_domain_model_news.uid',
                        $queryBuilder->quoteIdentifier('tx_realurl_uniqalias.value_id')
                    )
                )
                ->where(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->eq(
                                'tx_news_domain_model_news.path_segment',
                                $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)
                            ),
                            $queryBuilder->expr()->isNull('tx_news_domain_model_news.path_segment')
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_news_domain_model_news.sys_language_uid',
                            'tx_realurl_uniqalias.lang'
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_realurl_uniqalias.tablename',
                            $queryBuilder->createNamedParameter('tx_news_domain_model_news', \PDO::PARAM_STR)
                        ),
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->eq(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                            ),
                            $queryBuilder->expr()->gte(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter($GLOBALS['ACCESS_TIME'], \PDO::PARAM_INT)
                            )
                        )
                    )
                )
                ->execute();

            // Update entries
            while ($record = $statement->fetch()) {
                $queryBuilder = $connection->createQueryBuilder();
                $queryBuilder->update('tx_news_domain_model_news')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                        )
                    )
                    ->set('path_segment', $this->generateSlug((string) $record['value_alias']));
                $databaseQueries[] = $queryBuilder->getSQL();
                $queryBuilder->execute();
            }
        }

        return $databaseQueries;
    }
}
