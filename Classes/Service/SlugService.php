<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SlugService
{
    /** @var SlugHelper */
    protected $slugService;

    public function __construct()
    {
        $fieldConfig = $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['path_segment']['config'];
        $this->slugService = GeneralUtility::makeInstance(SlugHelper::class, 'tx_news_domain_model_news', 'path_segment', $fieldConfig);
    }

    public function countOfSlugUpdates(): int
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
        $queryBuilder->getRestrictions()->removeAll();
        return (int)$queryBuilder->count('uid')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq('path_segment', $queryBuilder->createNamedParameter('', Connection::PARAM_STR)),
                    $queryBuilder->expr()->isNull('path_segment')
                )
            )
            ->executeQuery()->fetchOne();
    }

    public function performUpdates(): array
    {
        $databaseQueries = [];

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_news_domain_model_news');
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder->select('*')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq('path_segment', $queryBuilder->createNamedParameter('', Connection::PARAM_STR)),
                    $queryBuilder->expr()->isNull('path_segment')
                )
            )
            ->executeQuery();
        while ($record = $statement->fetchAssociative()) {
            if ((string)$record['title'] !== '') {
                $slug = $this->slugService->generate($record, $record['pid']);
                /** @var QueryBuilder $queryBuilder */
                $queryBuilder = $connection->createQueryBuilder();
                $queryBuilder->update('tx_news_domain_model_news')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($record['uid'], Connection::PARAM_INT)
                        )
                    )
                    ->set('path_segment', $this->getUniqueValue($record['uid'], $record['sys_language_uid'], $slug));
                $databaseQueries[] = $queryBuilder->getSQL();
                $queryBuilder->executeStatement();
            }
        }

        return $databaseQueries;
    }

    protected function getUniqueValue(int $uid, int $languageId, string $slug): string
    {
        $queryBuilder = $this->getUniqueCountStatement($uid, $languageId, $slug);
        // For as long as records with the test-value existing, try again (with incremented numbers appended)
        $statement = $queryBuilder->prepare();
        $result = $statement->executeQuery();
        if ($result->fetchOne()) {
            for ($counter = 0; $counter <= 100; $counter++) {
                $result->free();
                $newSlug = $slug . '-' . $counter;
                $statement->bindValue(1, $newSlug);
                $result = $statement->executeQuery();
                if (!$result->fetchOne()) {
                    break;
                }
            }
            $result->free();
        }

        return $newSlug ?? $slug;
    }

    /**
     * @param int $uid
     * @param int $languageId
     * @param string $slug
     */
    protected function getUniqueCountStatement(int $uid, int $languageId, string $slug)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
        $deleteRestriction = GeneralUtility::makeInstance(DeletedRestriction::class);
        $queryBuilder->getRestrictions()->removeAll()->add($deleteRestriction);

        return $queryBuilder
            ->count('uid')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->eq(
                    'path_segment',
                    $queryBuilder->createPositionalParameter($slug, Connection::PARAM_STR)
                ),
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createPositionalParameter($languageId, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->neq('uid', $queryBuilder->createPositionalParameter($uid, Connection::PARAM_INT))
            );
    }

    /**
     * Count valid entries from EXT:realurl table tx_realurl_uniqalias which can be migrated
     * Checks also for existance of third party extension table 'tx_realurl_uniqalias'
     * EXT:realurl requires not to be installed
     */
    public function countOfRealurlAliasMigrations(): int
    {
        $elementCount = 0;
        // Check if table 'tx_realurl_uniqalias' exists
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_realurl_uniqalias');
        $schemaManager = $queryBuilder->getConnection()->getSchemaManager();
        if ($schemaManager && $schemaManager->tablesExist(['tx_realurl_uniqalias']) === true) {
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
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->or(
                            $queryBuilder->expr()->eq(
                                'tx_news_domain_model_news.path_segment',
                                $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
                            ),
                            $queryBuilder->expr()->isNull('tx_news_domain_model_news.path_segment')
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_news_domain_model_news.sys_language_uid',
                            'tx_realurl_uniqalias.lang'
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_realurl_uniqalias.tablename',
                            $queryBuilder->createNamedParameter('tx_news_domain_model_news', Connection::PARAM_STR)
                        ),
                        $queryBuilder->expr()->or(
                            $queryBuilder->expr()->eq(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)
                            ),
                            $queryBuilder->expr()->gte(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter($GLOBALS['ACCESS_TIME'], Connection::PARAM_INT)
                            )
                        )
                    )
                )
                ->executeQuery()->fetchOne();
        }
        return $elementCount;
    }

    /**
     * Perform migration of EXT:realurl unique alias into empty news slugs
     */
    public function performRealurlAliasMigration(): array
    {
        $databaseQueries = [];

        // Check if table 'tx_realurl_uniqalias' exists
        $queryBuilderForRealurl = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_realurl_uniqalias');
        $schemaManager = $queryBuilderForRealurl->getConnection()->getSchemaManager();
        if ($schemaManager && $schemaManager->tablesExist(['tx_realurl_uniqalias']) === true) {
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
                    $queryBuilder->expr()->or(
                        $queryBuilder->expr()->eq(
                            'tx_news_domain_model_news.uid',
                            $queryBuilder->quoteIdentifier('tx_realurl_uniqalias.value_id')
                        ),
                        $queryBuilder->expr()->and(
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
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->or(
                            $queryBuilder->expr()->eq(
                                'tx_news_domain_model_news.path_segment',
                                $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
                            ),
                            $queryBuilder->expr()->isNull('tx_news_domain_model_news.path_segment')
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_news_domain_model_news.sys_language_uid',
                            'tx_realurl_uniqalias.lang'
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_realurl_uniqalias.tablename',
                            $queryBuilder->createNamedParameter('tx_news_domain_model_news', Connection::PARAM_STR)
                        ),
                        $queryBuilder->expr()->or(
                            $queryBuilder->expr()->eq(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)
                            ),
                            $queryBuilder->expr()->gte(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter($GLOBALS['ACCESS_TIME'], Connection::PARAM_INT)
                            )
                        )
                    )
                )
                ->executeQuery();

            // Update entries
            while ($record = $statement->fetchAssociative()) {
                $slug = $this->slugService->sanitize((string)$record['value_alias']);
                $queryBuilder = $connection->createQueryBuilder();
                $queryBuilder->update('tx_news_domain_model_news')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($record['uid'], Connection::PARAM_INT)
                        )
                    )
                    ->set('path_segment', $this->getUniqueValue($record['uid'], $record['sys_language_uid'], $slug));
                $databaseQueries[] = $queryBuilder->getSQL();
                $queryBuilder->executeStatement();
            }
        }

        return $databaseQueries;
    }
}
