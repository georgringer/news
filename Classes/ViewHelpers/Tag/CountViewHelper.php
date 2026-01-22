<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\ViewHelpers\Tag;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Get usage count
 *
 * Example usage
 * {n:tag.count(tagUid:tag.uid) -> f:variable(name: 'tagUsageCount')}
 * {tagUsageCount}
 */
class CountViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('tagUid', 'int', 'Uid of the tag', true);
    }

    public function render(): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');

        $languageUid = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getId();

        return $queryBuilder
            ->count('tx_news_domain_model_news.title')
            ->from('tx_news_domain_model_news')
            ->rightJoin(
                'tx_news_domain_model_news',
                'tx_news_domain_model_news_tag_mm',
                'tx_news_domain_model_news_tag_mm',
                $queryBuilder->expr()->eq('tx_news_domain_model_news.uid', $queryBuilder->quoteIdentifier('tx_news_domain_model_news_tag_mm.uid_local'))
            )
            ->rightJoin(
                'tx_news_domain_model_news_tag_mm',
                'tx_news_domain_model_tag',
                'tx_news_domain_model_tag',
                $queryBuilder->expr()->eq('tx_news_domain_model_tag.uid', $queryBuilder->quoteIdentifier('tx_news_domain_model_news_tag_mm.uid_foreign'))
            )
            ->where(
                $queryBuilder->expr()->eq('tx_news_domain_model_tag.uid', $queryBuilder->createNamedParameter($this->arguments['tagUid'], Connection::PARAM_INT)),
                $queryBuilder->expr()->in(
                    'tx_news_domain_model_news.sys_language_uid',
                    $queryBuilder->createNamedParameter([-1, $languageUid], Connection::PARAM_INT_ARRAY)
                )
            )
            ->executeQuery()->fetchOne();
    }
}
