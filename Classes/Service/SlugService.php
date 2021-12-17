<?php

namespace GeorgRinger\News\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class SlugService
{

    /** @var NewsSlugHelper */
    protected $slugService;

    public function __construct()
    {
        $fieldConfig = $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['path_segment']['config'];
        $this->slugService = GeneralUtility::makeInstance(SlugHelper::class, 'tx_news_domain_model_news', 'path_segment', $fieldConfig);
    }

    /**
     * @return int
     */
    public function countOfSlugUpdates(): int
    {
        /** @var QueryBuilder $queryBuilder */
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

    /**
     * @return array
     */
    public function performUpdates(): array
    {
        $databaseQueries = [];

        /** @var Connection $connection */
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_news_domain_model_news');
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder->select('*')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('path_segment', $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)),
                    $queryBuilder->expr()->isNull('path_segment')
                )
            )
            ->execute();
        while ($record = $statement->fetch()) {
            if ((string)$record['title'] !== '') {
                $slug = $this->slugService->generate($record, $record['pid']);
                /** @var QueryBuilder $queryBuilder */
                $queryBuilder = $connection->createQueryBuilder();
                $queryBuilder->update('tx_news_domain_model_news')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                        )
                    )
                    ->set('path_segment', $this->getUniqueValue($record['uid'], $record['sys_language_uid'], $slug));
                $databaseQueries[] = $queryBuilder->getSQL();
                $queryBuilder->execute();
            }
        }

        return $databaseQueries;
    }

    /**
     * @param int $uid
     * @param string $slug
     * @return string
     */
    protected function getUniqueValue(int $uid, int $languageId, string $slug): string
    {
        $statement = $this->getUniqueCountStatement($uid, $languageId, $slug);
        if ($statement->fetchColumn()) {
            for ($counter = 1; $counter <= 100; $counter++) {
                $newSlug = $slug . '-' . $counter;
                $statement->bindValue(1, $newSlug);
                $statement->execute();
                if (!$statement->fetchColumn()) {
                    break;
                }
            }
        }

        return $newSlug ?? $slug;
    }

    /**
     * @param int $uid
     * @param int $languageId
     * @param string $slug
     * @return \Doctrine\DBAL\Driver\Statement|int
     */
    protected function getUniqueCountStatement(int $uid, int $languageId, string $slug)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
        /** @var DeletedRestriction $deleteRestriction */
        $deleteRestriction = GeneralUtility::makeInstance(DeletedRestriction::class);
        $queryBuilder->getRestrictions()->removeAll()->add($deleteRestriction);

        return $queryBuilder
            ->count('uid')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->eq(
                    'path_segment',
                    $queryBuilder->createPositionalParameter($slug, \PDO::PARAM_STR)
                ),
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createPositionalParameter($languageId, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->neq('uid', $queryBuilder->createPositionalParameter($uid, \PDO::PARAM_INT))
            )->execute();
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
        /** @var QueryBuilder $queryBuilder */
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
        /** @var QueryBuilder $queryBuilderForRealurl */
        $queryBuilderForRealurl = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_realurl_uniqalias');
        $schemaManager = $queryBuilderForRealurl->getConnection()->getSchemaManager();
        if ($schemaManager->tablesExist(['tx_realurl_uniqalias']) === true) {
            /** @var Connection $connection */
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_news_domain_model_news');
            $queryBuilder = $connection->createQueryBuilder();

            // Get entries to update
            $statement = $queryBuilder
                ->selectLiteral(
                    'DISTINCT tx_news_domain_model_news.uid, tx_realurl_uniqalias.value_alias, tx_news_domain_model_news.uid, tx_news_domain_model_news.l10n_parent,tx_news_domain_model_news.sys_language_uid'
                )
                ->from('tx_news_domain_model_news')
                ->join(
                    'tx_news_domain_model_news',
                    'tx_realurl_uniqalias',
                    'tx_realurl_uniqalias',
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq(
                            'tx_news_domain_model_news.uid',
                            $queryBuilder->quoteIdentifier('tx_realurl_uniqalias.value_id')
                        ),
                        $queryBuilder->expr()->andX(
                            $queryBuilder->expr()->eq(
                                'tx_news_domain_model_news.l10n_parent',
                                $queryBuilder->quoteIdentifier('tx_realurl_uniqalias.value_id')
                            ),
                            $queryBuilder->expr()->eq(
                                'tx_news_domain_model_news.sys_language_uid',
                                $queryBuilder->quoteIdentifier('tx_realurl_uniqalias.lang')
                            )
                        )
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
                $slug = $this->slugService->sanitize((string)$record['value_alias']);
                $queryBuilder = $connection->createQueryBuilder();
                $queryBuilder->update('tx_news_domain_model_news')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                        )
                    )
                    ->set('path_segment', $this->getUniqueValue($record['uid'], $record['sys_language_uid'], $slug));
                $databaseQueries[] = $queryBuilder->getSQL();
                $queryBuilder->execute();
            }
        }

        return $databaseQueries;
    }
}
