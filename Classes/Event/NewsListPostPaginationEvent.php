<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\NewsController;
use TYPO3\CMS\Extbase\Mvc\Request;

final class NewsListPostPaginationEvent
{
    private NewsController $newsController;

    private array $assignedPagination;

    private readonly Request $request;

    public function __construct(NewsController $newsController, array $assignedPagination, Request $request)
    {
        $this->newsController = $newsController;
        $this->assignedPagination = $assignedPagination;
        $this->request = $request;
    }

    /**
     * Get the news controller
     */
    public function getNewsController(): NewsController
    {
        return $this->newsController;
    }

    /**
     * Set the news controller
     */
    public function setNewsController(NewsController $newsController): self
    {
        $this->newsController = $newsController;

        return $this;
    }

    /**
     * Get the assignedPagination
     */
    public function getAssignedPagination(): array
    {
        return $this->assignedPagination;
    }

    /**
     * Set the assignedPagination
     */
    public function setAssignedPagination(array $assignedPagination): self
    {
        $this->assignedPagination = $assignedPagination;

        return $this;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
