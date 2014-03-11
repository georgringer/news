<?php
/***************************************************************
* Copyright notice
*
* (c) 2011 Oliver Klee (typo3-coding@oliverklee.de)
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Testcase for the Tx_News_Controller_NewsController class.
 *
 * @package TYPO3
 * @subpackage tx_news
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 * @author Georg Ringer <mail@ringerge.org>
 */
class Tx_News_Tests_Unit_Controller_NewsControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var Tx_News_Controller_NewsController
	 */
	private $fixture = NULL;

	/**
	 * @var Tx_News_Domain_Repository_NewsRepository
	 */
	private $newsRepository = NULL;

	/**
	 * Set up framework
	 *
	 * @return void
	 */
	public function setUp() {
		$this->fixture = new Tx_News_Controller_NewsController();

		$this->newsRepository = $this->getMock(
			'Tx_News_Domain_Repository_NewsRepository', array(), array(), '', FALSE
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
		$demand = clone new Tx_News_Domain_Model_Dto_AdministrationDemand();
		$settings = array('list' => 'foo');

		$configurationManager = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface'
		);
		$configurationManager->expects($this->any())->method('getConfiguration')
			->will($this->returnValue($settings));

		$fixture = $this->getMock(
			'Tx_News_Controller_NewsController',
			array('createDemandObjectFromSettings')
		);
		$fixture->injectNewsRepository($this->newsRepository);
		$fixture->injectConfigurationManager($configurationManager);
		$fixture->setView($this->getMock('Tx_Fluid_View_TemplateView', array(), array(), '', FALSE));

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
		$mockedSignalDispatcher = $this->getAccessibleMock('\TYPO3\CMS\Extbase\SignalSlot\Dispatcher', array('dummy'));
		$mockedController = $this->getAccessibleMock('Tx_News_Controller_NewsController',
			array('dummy'));
		$mockedController->_set('signalSlotDispatcher', $mockedSignalDispatcher);

		$news = new Tx_News_Domain_Model_News();

		// No startingpoint
		$mockedController->_set('settings', array('startingpoint' => ''));
		$news->setPid(45);

		$this->assertEquals($news, $mockedController->_call('checkPidOfNewsRecord', $news));

		// startingpoint defined
		$mockedController->_set('settings', array('startingpoint' => '1,2,123,456'));
		$news->setPid(123);

		$this->assertEquals($news, $mockedController->_call('checkPidOfNewsRecord', $news));

		// startingpoint is different
		$mockedController->_set('settings', array('startingpoint' => '123,456'));
		$news->setPid(45);

		$this->assertEquals(NULL, $mockedController->_call('checkPidOfNewsRecord', $news));
	}

}
