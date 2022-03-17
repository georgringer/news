<?php

declare(strict_types=1);

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Pagination;

use TYPO3\CMS\Core\Pagination\AbstractPaginator;

abstract class CustomAbstractPaginator extends AbstractPaginator
{

    /**
     * @var int
     */
    protected $currentPageNumber = 1;

    /**
     * @var int
     */
    protected $itemsPerPage = 10;

    protected $initialOffset = 0;
    protected $initialLimit = 0;

    /**
     * This method is the heart of the pagination. It updates all internal params and then calls the
     * {@see updatePaginatedItems} method which must update the set of paginated items.
     */
    protected function updateInternalState(): void
    {
        // offset
        if ($this->currentPageNumber > 1) {
            $offset = ($this->itemsPerPage * ($this->currentPageNumber - 1));
            $offset += $this->initialOffset;
        } elseif ($this->initialOffset > 0) {
            $offset = $this->initialOffset;
        } else {
            $offset = 0;
        }

        // limit
        $limit = $this->itemsPerPage;

        $totalAmountOfItems = $this->getTotalAmountOfItems();

        /*
         * If the total amount of items is zero, then the number of pages is mathematically zero as
         * well. As that looks strange in the frontend, the number of pages is forced to be at least
         * one.
         */
        $this->numberOfPages = max(1, (int)ceil($totalAmountOfItems / $this->itemsPerPage));

        /*
         * To prevent empty results in case the given current page number exceeds the maximum number
         * of pages, we set the current page number to the last page and update the internal state
         * with this value again. Such situation should in the first place be prevented by not allowing
         * those values to be passed, e.g. by using the "max" attribute in the view. However there are
         * valid cases. For example when a user deletes a record while the pagination is already visible
         * to another user with, until then, a valid "max" value. Passing invalid values unintentionally
         * should therefore just silently be resolved.
         */
        if ($this->currentPageNumber > $this->numberOfPages) {
            $this->currentPageNumber = $this->numberOfPages;
            $this->updateInternalState();
            return;
        }

        $isUpdated = false;
        if ($this->currentPageNumber === $this->numberOfPages && $this->initialLimit > 0) {
            $difference = $this->initialLimit - ((integer)($this->itemsPerPage * ($this->currentPageNumber - 1)));
            if ($difference > 0) {
                $this->updatePaginatedItems($difference, $offset);
                $isUpdated = true;
                $totalAmountOfItems = $this->getTotalAmountOfItems();
                $this->numberOfPages = max(1, (int)ceil($totalAmountOfItems / $this->itemsPerPage));
            }
        }

        if (!$isUpdated) {
            $this->updatePaginatedItems($limit, $offset);
        }

        if (!$this->hasItemsOnCurrentPage()) {
            $this->keyOfFirstPaginatedItem = 0;
            $this->keyOfLastPaginatedItem = 0;
            return;
        }

        $indexOfLastPaginatedItem = min($offset + $this->itemsPerPage, $totalAmountOfItems);

        $this->keyOfFirstPaginatedItem = $offset;
        $this->keyOfLastPaginatedItem = $indexOfLastPaginatedItem - 1;
    }

    public function getCurrentPageNumber(): int
    {
        return $this->currentPageNumber;
    }

    protected function setItemsPerPage(int $itemsPerPage): void
    {
        if ($itemsPerPage < 1) {
            throw new \InvalidArgumentException(
                'Argument $itemsPerPage must be greater than 0',
                1573061766
            );
        }

        $this->itemsPerPage = $itemsPerPage;
    }

    protected function setCurrentPageNumber(int $currentPageNumber): void
    {
        if ($currentPageNumber < 1) {
            throw new \InvalidArgumentException(
                'Argument $currentPageNumber must be greater than 0',
                1573047338
            );
        }

        $this->currentPageNumber = $currentPageNumber;
    }
}
