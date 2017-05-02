<?php

namespace GeorgRinger\News\Tests\Unit\Command;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Command\NewsImportCommandController;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Testcase for the GeorgRinger\\News\\Controller\\NewsImportCommandControllerTest class.
 */
class NewsImportCommandControllerTest extends UnitTestCase
{

    /**
     * @test
     */
    public function chosenJobsExitsWithNoJobFound()
    {
        $mockedCommand = $this->getAccessibleMock(NewsImportCommandController::class, ['output', 'sendAndExit']);
        $mockedCommand->expects($this->once())->method('output');
        $classes = ['class 1' => 'title 1', 'class 2' => 'title 2'];
        $mockedCommand->_call('getChosenClass', $classes, 'title 3');
    }

    /**
     * @test
     */
    public function chosenJobsReturnsCorrectClass()
    {
        $mockedCommand = $this->getAccessibleMock(NewsImportCommandController::class, ['dummy']);
        $classes = ['class 1' => 'title 1', 'class 2' => 'title 2'];
        $class = $mockedCommand->_call('getChosenClass', $classes, 'title 2');
        $this->assertEquals('class 2', $class);
    }
}
