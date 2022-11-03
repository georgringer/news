<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Domain\Service\NewsImportService;

final class NewsPreImportEvent
{
    private $newsImportService;

    private $importData;

    public function __construct(NewsImportService $newsImportService, array $importData)
    {
        $this->newsImportService = $newsImportService;
        $this->importData = $importData;
    }

    public function getNewsImportService(): NewsImportService
    {
        return $this->newsImportService;
    }

    public function setNewsImportService(NewsImportService $newsImportService): self
    {
        $this->newsImportService = $newsImportService;

        return $this;
    }

    public function getImportData(): array
    {
        return $this->importData;
    }

    public function setImportData(array $importData): self
    {
        $this->importData = $importData;

        return $this;
    }
}
