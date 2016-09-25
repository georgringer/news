<?php

namespace GeorgRinger\News\Tests\Unit\Command;

use GeorgRinger\News\Command\NewsImportCommandController;

/**
 * Testcase for the GeorgRinger\\News\\Controller\\NewsImportCommandControllerTest class.
 */
class NewsImportCommandControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
