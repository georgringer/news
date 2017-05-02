<?php

namespace GeorgRinger\News\Tests\Unit\Utility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Utility\ImportJob;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test class for ImportJob
 *
 */
class ImportJobTest extends UnitTestCase
{

    /**
     * @test
     */
    public function classCanBeRegistered()
    {
        $importJobInstance = new ImportJob();

        $jobs = [];
        $this->assertEquals($importJobInstance->getRegisteredJobs(), $jobs);

        // Add job #1
        $jobs[] = [
            'className' => 'Class 1',
            'title' => 'Some title',
            'description' => ''
        ];
        $importJobInstance->register('Class 1', 'Some title', '');
        $this->assertEquals($importJobInstance->getRegisteredJobs(), $jobs);

        // Add job #2
        $jobs[] = [
            'className' => 'Class 2',
            'title' => '',
            'description' => 'Some description'
        ];
        $importJobInstance->register('Class 2', '', 'Some description');
        $this->assertEquals($importJobInstance->getRegisteredJobs(), $jobs);
    }
}
