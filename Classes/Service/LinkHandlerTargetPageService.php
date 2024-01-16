<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class LinkHandlerTargetPageService
{
    /** @var ContentObjectRenderer */
    protected $cObj;

    /**
     * called by ContentObjectRenderer->callUserFunction() to explicitly set an instance of the ContentObjectRenderer.
     */
    public function setContentObjectRenderer(ContentObjectRenderer $cObj)
    {
        $this->cObj = $cObj;
    }

    public function process(string $content = '', array $configuration = []): int
    {
        $fallbackPageId = (int)($configuration['fallback'] ?? 0);

        $newsId = (int)$this->cObj->stdWrapValue('news', $configuration, null);
        if ($newsId === 0) {
            return $fallbackPageId;
        }

        $singlePid = $this->getSinglePidFromCategory($newsId);
        return $singlePid ?: $fallbackPageId;
    }

    /**
     * Obtains a pid for the single view from the category.
     *
     * @param int $newsId
     * @return int
     */
    protected function getSinglePidFromCategory(int $newsId): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        $categoryRecord = $queryBuilder
            ->select('title', 'single_pid')
            ->from('sys_category')
            ->leftJoin(
                'sys_category',
                'sys_category_record_mm',
                'sys_category_record_mm',
                $queryBuilder->expr()->eq('sys_category_record_mm.uid_local', $queryBuilder->quoteIdentifier('sys_category.uid'))
            )
            ->where(
                $queryBuilder->expr()->eq('sys_category_record_mm.tablenames', $queryBuilder->createNamedParameter('tx_news_domain_model_news', Connection::PARAM_STR)),
                $queryBuilder->expr()->gt('sys_category.single_pid', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('sys_category_record_mm.uid_foreign', $queryBuilder->createNamedParameter($newsId, Connection::PARAM_INT))
            )
            ->orderBy('sys_category_record_mm.sorting')
            ->setMaxResults(1)
            ->executeQuery()->fetchAssociative();
        return (int)($categoryRecord['single_pid'] ?? 0);
    }
}
