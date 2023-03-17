<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\NewsController;
use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Extbase\Mvc\Request;

final class NewsCheckPidOfNewsRecordFailedInDetailActionEvent
{
    /**
     * @var NewsController
     */
    private $newsController;

    /**
     * @var News
     */
    private $news;

    /** @var Request */
    private $request;

    public function __construct(NewsController $newsController, News $news, Request $request)
    {
        $this->newsController = $newsController;
        $this->news = $news;
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
     * Get the news
     */
    public function getNews(): News
    {
        return $this->news;
    }

    /**
     * Set the news
     */
    public function setNews(News $news): self
    {
        $this->news = $news;

        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
