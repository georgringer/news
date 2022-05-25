<?php
declare(strict_types=1);

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\NewsController;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
final class NewsInitializeActionEvent
{
    private $newsController;

    private $defaultViewObjectName;

    private $action;

    /**
     * @param NewsController $newsController
     * @param string $defaultViewObjectName
     * @param string $action
     */
    public function __construct(NewsController $newsController, string $defaultViewObjectName, string $action = '')
    {
        $this->newsController = $newsController;
        $this->defaultViewObjectName = $defaultViewObjectName;
        $this->action = $action;
    }

    /**
     * @return NewsController
     */
    public function getNewsController(): NewsController
    {
        return $this->newsController;
    }

    /**
     * @param NewsController $newsController
     * @return $this
     */
    public function setNewsController(NewsController $newsController): self
    {
        $this->newsController = $newsController;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultViewObjectName(): string
    {
        return $this->defaultViewObjectName;
    }

    /**
     * @param string $defaultViewObjectName
     * @return NewsInitializeActionEvent
     */
    public function setDefaultViewObjectName(string $defaultViewObjectName): self
    {
        $this->defaultViewObjectName = $defaultViewObjectName;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return NewsInitializeActionEvent
     */
    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }
}
