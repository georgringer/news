<?php

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Service\NewsImportService;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
final class NewsImportPostHydrateEvent
{
    /**
     * @var NewsImportService
     */
    private $newsImportService;

    /**
     * @var array
     */
    private $importItem;

    /**
     * @var News
     */
    private $news;

    public function __construct(NewsImportService $newsImportService, array $importItem, News $news)
    {
        $this->newsImportService = $newsImportService;
        $this->importItem = $importItem;
        $this->news = $news;
    }

    /**
     * Get the importer service
     */
    public function getNewsImportService(): NewsImportService
    {
        return $this->newsImportService;
    }

    /**
     * Set the importer service
     */
    public function setNewsImportService(NewsImportService $newsImportService): self
    {
        $this->newsImportService = $newsImportService;

        return $this;
    }

    /**
     * Get the importItem
     */
    public function getImportItem(): array
    {
        return $this->importItem;
    }

    /**
     * Set the importItem
     */
    public function setImportItem(array $importItem): self
    {
        $this->importItem = $importItem;

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
}
