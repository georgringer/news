<?php

namespace GeorgRinger\News\Tests\Unit\Controller;

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
use GeorgRinger\News\Controller\NewsController;
use GeorgRinger\News\Domain\Model\Dto\AdministrationDemand;
use GeorgRinger\News\Domain\Model\News;

/**
 * Testcase for the NewsController class.
 *
 *
 */
class NewsControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var NewsController
	 */
	private $fixture = NULL;

	/**
	 * @var NewsController
	 */
	private $newsRepository = NULL;

	/**
	 * Set up framework
	 *
	 * @return void
	 */
	public function setUp() {
		$this->fixture = new NewsController();

		$this->newsRepository = $this->getMock(
			'GeorgRinger\\News\\Domain\\Repository\\NewsRepository', [], [], '', FALSE
		);
		$this->fixture->injectNewsRepository($this->newsRepository);
	}

	/**
	 * Tear down framework
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->fixture, $this->newsRepository);
	}

	/**
	 * Test for creating correct demand call
	 *
	 * @test
	 * @return void
	 */
	public function listActionFindsDemandedNewsByDemandFromSettings() {
		$demand = clone new AdministrationDemand();
		$settings = ['list' => 'foo', 'orderByAllowed' => NULL];

		$configurationManager = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface'
		);
		$configurationManager->expects($this->any())->method('getConfiguration')
			->will($this->returnValue($settings));

		$fixture = $this->getMock(
			'GeorgRinger\\News\\Controller\\NewsController',
			['createDemandObjectFromSettings', 'emitActionSignal']
		);
		$fixture->injectNewsRepository($this->newsRepository);
		$fixture->injectConfigurationManager($configurationManager);
		$fixture->setView($this->getMock('TYPO3\CMS\Fluid\View\TemplateView', [], [], '', FALSE));

		$fixture->expects($this->once())->method('emitActionSignal')->will($this->returnValue([]));
		$fixture->expects($this->once())->method('createDemandObjectFromSettings')
			->with($settings)->will($this->returnValue($demand));

		$this->newsRepository->expects($this->once())->method('findDemanded')
			->with($demand);

		$fixture->listAction();
	}

	/**
	 * @test
	 */
	public function checkPidOfNewsRecordWorks() {
		$mockedSignalDispatcher = $this->getAccessibleMock('\TYPO3\CMS\Extbase\SignalSlot\Dispatcher', ['dummy']);
		$mockedController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsController',
			['dummy']);
		$mockedController->_set('signalSlotDispatcher', $mockedSignalDispatcher);

		$news = new News();

		// No startingpoint
		$mockedController->_set('settings', ['startingpoint' => '']);
		$news->setPid(45);

		$this->assertEquals($news, $mockedController->_call('checkPidOfNewsRecord', $news));

		// startingpoint defined
		$mockedController->_set('settings', ['startingpoint' => '1,2,123,456']);
		$news->setPid(123);

		$this->assertEquals($news, $mockedController->_call('checkPidOfNewsRecord', $news));

		// startingpoint is different
		$mockedController->_set('settings', ['startingpoint' => '123,456']);
		$news->setPid(45);

		$this->assertEquals(NULL, $mockedController->_call('checkPidOfNewsRecord', $news));
	}

	/**
	 * @test
	 * @expectedException \UnexpectedValueException
	 */
	public function exceptionForInvalidDemandClass() {
		$mockedController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsController', ['dummy']);
		$mockedObjectManager = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager', ['get']);
		$mockedObjectManager->expects($this->once())->method('get')->willReturn('fo');
		$mockedController->_set('objectManager', $mockedObjectManager);
		$settings = ['fo' => 'bar'];
		$mockedController->_call('createDemandObjectFromSettings', $settings, 'fo');
	}

}
