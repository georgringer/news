<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Utility;

use GeorgRinger\News\Utility\ImportJob;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Test class for ImportJob
 */
class ImportJobTest extends BaseTestCase
{
    #[Test]
    public function classCanBeRegistered(): void
    {
        $importJobInstance = new ImportJob();

        $jobs = [];
        self::assertEquals($importJobInstance->getRegisteredJobs(), $jobs);

        // Add job #1
        $jobs[] = [
            'className' => 'Class 1',
            'title' => 'Some title',
            'description' => '',
        ];
        $importJobInstance->register('Class 1', 'Some title', '');
        self::assertEquals($importJobInstance->getRegisteredJobs(), $jobs);

        // Add job #2
        $jobs[] = [
            'className' => 'Class 2',
            'title' => '',
            'description' => 'Some description',
        ];
        $importJobInstance->register('Class 2', '', 'Some description');
        self::assertEquals($importJobInstance->getRegisteredJobs(), $jobs);
    }
}
