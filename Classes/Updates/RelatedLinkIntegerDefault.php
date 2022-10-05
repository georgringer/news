<?php

declare(strict_types=1);

namespace GeorgRinger\News\Updates;

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
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Migrate default value of related links
 */
final class RelatedLinkIntegerDefault implements UpgradeWizardInterface
{
    private const TABLE_NEWS = 'tx_news_domain_model_news';
    private const FIELD_UID = 'uid';
    private const FIELD_RELATED_LINKS = 'related_links';
    private const RELATED_LINKS_DEFAULT_VALUE = '0';

    public function getIdentifier(): string
    {
        return 'txNewsRelatedLinkIntegerDefault';
    }

    public function getTitle(): string
    {
        return sprintf(
            'Set integer default value where field `%s`.`%s` is NULL.',
            self::TABLE_NEWS,
            self::FIELD_RELATED_LINKS
        );
    }

    public function getDescription(): string
    {
        return <<<'EOD'
            Set value to 0 (zero) where it's currently NULL to prevent errors
            during database schema update.
            See https://github.com/georgringer/news/pull/1436.
            EOD;
    }

    public function executeUpdate(): bool
    {
        $query = self::getQueryBuilderForNews();
        $query
            ->update(self::TABLE_NEWS)
            ->set(
                self::FIELD_RELATED_LINKS,
                self::RELATED_LINKS_DEFAULT_VALUE,
                true,
                Connection::PARAM_INT
            )
            ->where($query->expr()->isNull(self::FIELD_RELATED_LINKS))
            ->execute();
        return true;
    }

    public function updateNecessary(): bool
    {
        $query = self::getQueryBuilderForNews();
        return (bool) $query
            ->count(self::FIELD_UID)
            ->from(self::TABLE_NEWS)
            ->where($query->expr()->isNull(self::FIELD_RELATED_LINKS))
            ->execute()
            ->fetchColumn(0);
    }

    public function getPrerequisites(): array
    {
        return [];
    }

    private static function getQueryBuilderForNews(): QueryBuilder
    {
        $query = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::TABLE_NEWS);
        $query->getRestrictions()->removeAll();
        return $query;
    }
}
