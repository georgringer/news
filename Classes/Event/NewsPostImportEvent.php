<?php

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Domain\Service\NewsImportService;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
final class NewsPostImportEvent
{
    /**
     * @var NewsImportService
     */
    private $newsImportService;

    /**
     * @var array
     */
    private $importData;

    public function __construct(NewsImportService $newsImportService, array $importData)
    {
        $this->newsImportService = $newsImportService;
        $this->importData = $importData;
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
     * Get the importData
     */
    public function getImportData(): array
    {
        return $this->importData;
    }

    /**
     * Set the importData
     */
    public function setImportData(array $importData): self
    {
        $this->importData = $importData;

        return $this;
    }
}
