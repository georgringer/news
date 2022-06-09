<?php

declare(strict_types=1);

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Pagination;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

final class QueryResultPaginator extends CustomAbstractPaginator
{
    /**
     * @var QueryResultInterface
     */
    private $queryResult;

    /**
     * @var QueryResultInterface
     */
    private $paginatedQueryResult;

    public function __construct(
        QueryResultInterface $queryResult,
        int $currentPageNumber = 1,
        int $itemsPerPage = 10,
        int $initialLimit = 0,
        int $initialOffset = 0
    ) {
        $this->queryResult = $queryResult;
        $this->setCurrentPageNumber($currentPageNumber);
        $this->setItemsPerPage($itemsPerPage);
        $this->initialLimit = $initialLimit;
        $this->initialOffset = $initialOffset;
        $this->updateInternalState();
    }

    /**
     * @return iterable|QueryResultInterface
     */
    public function getPaginatedItems(): iterable
    {
        return $this->paginatedQueryResult;
    }

    protected function updatePaginatedItems(int $limit, int $offset): void
    {
        $this->paginatedQueryResult = $this->queryResult
            ->getQuery()
            ->setLimit($limit)
            ->setOffset($offset)
            ->execute();
    }

    protected function getTotalAmountOfItems(): int
    {
        return count($this->queryResult);
    }

    protected function getAmountOfItemsOnCurrentPage(): int
    {
        return count($this->paginatedQueryResult);
    }
}
