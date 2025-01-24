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
    protected SlugHelper $slugService;

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
}
