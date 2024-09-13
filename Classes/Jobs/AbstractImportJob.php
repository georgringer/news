<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Jobs;

use GeorgRinger\News\Service\Import\DataProviderServiceInterface;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Abstract Import job
 */
abstract class AbstractImportJob implements ImportJobInterface
{
    /** @var DataProviderServiceInterface */
    protected $importDataProviderService;

    /** @var SingletonInterface */
    protected $importService;

    /** @var array */
    protected $importServiceSettings = [];

    /** @var array */
    protected $importItemOverwrite = [];

    /*
     * @var int
     */
    protected $numberOfRecordsPerRun;

    /** @var int */
    protected $increaseOffsetPerRunBy;

    /**
     * Get number of runs
     */
    public function getNumberOfRecordsPerRun(): int
    {
        // If not explicit defined by the job we import all records at once.
        if ($this->numberOfRecordsPerRun === null) {
            $this->numberOfRecordsPerRun = $this->importDataProviderService->getTotalRecordCount();
        }
        return $this->numberOfRecordsPerRun;
    }

    /**
     * Checks if this job is enabled. Do perform some checks her if you need to.
     * E.g. check if a certain extension is loaded or similar.
     */
    public function isEnabled(): bool
    {
        return true;
    }

    /**
     * Get job info.
     */
    public function getInfo(): array
    {
        $totalRecordCount = (int)$this->importDataProviderService->getTotalRecordCount();

        return [
            'totalRecordCount' => $totalRecordCount,
            'runsToComplete' => $totalRecordCount > 0 ? (ceil($totalRecordCount / $this->getNumberOfRecordsPerRun())) : 0,
            'increaseOffsetPerRunBy' => $this->getNumberOfRecordsPerRun(),
        ];
    }

    /**
     * The actual run method.
     *
     * @param int $offset
     */
    public function run($offset)
    {
        $importData = $this->importDataProviderService->getImportData($offset, $this->getNumberOfRecordsPerRun());
        $this->importService->import($importData, $this->importItemOverwrite, $this->importServiceSettings);
    }
}
