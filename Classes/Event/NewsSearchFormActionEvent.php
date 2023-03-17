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

final class NewsSearchFormActionEvent
{
    /**
     * @var NewsController
     */
    private $newsController;

    /**
     * @var array
     */
    private $assignedValues;

    /** @var Request */
    private $request;

    public function __construct(NewsController $newsController, array $assignedValues, Request $request)
    {
        $this->newsController = $newsController;
        $this->assignedValues = $assignedValues;
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
     * Get the assignedValues
     */
    public function getAssignedValues(): array
    {
        return $this->assignedValues;
    }

    /**
     * Set the assignedValues
     */
    public function setAssignedValues(array $assignedValues): self
    {
        $this->assignedValues = $assignedValues;

        return $this;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
