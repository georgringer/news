<?php

declare(strict_types=1);

namespace GeorgRinger\News\ViewHelpers\Tag;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Get usage count
 *
 * Example usage
 * {n:tag.count(tagUid:tag.uid) -> f:variable(name: 'tagUsageCount')}
 * {tagUsageCount}
 */
class CountViewHelper extends AbstractViewHelper implements ViewHelperInterface
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('tagUid', 'int', 'Uid of the tag', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return int
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): int {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');

        $count = $queryBuilder
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
                $queryBuilder->expr()->eq('tx_news_domain_model_tag.uid', $queryBuilder->createNamedParameter($arguments['tagUid'], \PDO::PARAM_INT))
            )
            ->execute()
            ->fetchColumn(0);

        return $count;
    }
}
