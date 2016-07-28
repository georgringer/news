<?php

namespace GeorgRinger\News\Tests\Unit\Utility;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use GeorgRinger\News\Utility\ImportJob;

/**
 * Test class for ImportJob
 *
 */
class ImportJobTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
