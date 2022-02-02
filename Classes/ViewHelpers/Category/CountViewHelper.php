<?php

declare(strict_types=1);

namespace GeorgRinger\News\ViewHelpers\Category;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\Connection;
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
 * Get usage count. This ViewHelper retrieves a simple count and does *not* take any additional constraints into account!
 * If you need additional constraints like startingpoint, archive, ... duplicate the ViewHelper and implement it on your own!
 *
 * Example usage
 * {n:category.count(categoryUid:category.item.uid) -> f:variable(name: 'categoryUsageCount')}
 * {categoryUsageCount}
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
        $this->registerArgument('categoryUid', 'int', 'Uid of the category', true);
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

        $categoryUid = $arguments['categoryUid'];
        $languageUid = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getId();

        $count = $queryBuilder
            ->count('tx_news_domain_model_news.title')
            ->from('tx_news_domain_model_news')
            ->rightJoin(
                'tx_news_domain_model_news',
                'sys_category_record_mm',
                'sys_category_record_mm',
                $queryBuilder->expr()->eq('tx_news_domain_model_news.uid', $queryBuilder->quoteIdentifier('sys_category_record_mm.uid_foreign'))
            )
            ->rightJoin(
                'sys_category_record_mm',
                'sys_category',
                'sys_category',
                $queryBuilder->expr()->eq('sys_category.uid', $queryBuilder->quoteIdentifier('sys_category_record_mm.uid_local'))
            )
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq(
                        'sys_category.uid',
                        $queryBuilder->createNamedParameter($categoryUid, \PDO::PARAM_INT)
                    ),
                    $queryBuilder->expr()->eq(
                        'sys_category_record_mm.tablenames',
                        $queryBuilder->createNamedParameter('tx_news_domain_model_news', \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->eq(
                        'sys_category_record_mm.fieldname',
                        $queryBuilder->createNamedParameter('categories', \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->in(
                        'tx_news_domain_model_news.sys_language_uid',
                        $queryBuilder->createNamedParameter([-1, $languageUid], Connection::PARAM_INT_ARRAY)
                    )
                )
            )
            ->execute()
            ->fetchColumn(0);

        return $count;
    }
}
