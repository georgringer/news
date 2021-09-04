<?php

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\NewsController;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
final class NewsListActionEvent
{
    /**
     * @var NewsController
     */
    private $newsController;

    /**
     * @var array
     */
    private $assignedValues;

    public function __construct(NewsController $newsController, array $assignedValues)
    {
        $this->newsController = $newsController;
        $this->assignedValues = $assignedValues;
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
}
