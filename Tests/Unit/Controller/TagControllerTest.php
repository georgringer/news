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
use GeorgRinger\News\Controller\TagController;
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;

/**
 * Testcase for the TagController class.
 *
 * @package TYPO3
 * @subpackage tx_news
 *
 * @author Georg Ringer <typo3@ringerge.org>
 */
class TagControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {


	/**
	 * @var TagController
	 */
	private $fixture = NULL;

	/**
	 * @var \GeorgRinger\News\Domain\Repository\TagRepository
	 */
	private $tagRepository = NULL;

	/**
	 * Set up framework
	 *
	 * @return void
	 */
	public function setUp() {
		$this->fixture = new TagController();

		$this->tagRepository = $this->getMock(
			'GeorgRinger\\News\\Domain\\Repository\\TagRepository', array(), array(), '', FALSE
		);
		$this->fixture->injectTagRepository($this->tagRepository);
	}

	/**
	 * Tear down framework
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->fixture, $this->tagRepository);
	}

	/**
	 * Test for creating correct demand call
	 *
	 * @test
	 * @return void
	 */
	public function listActionFindsDemandedTagsByDemandFromSettings() {
		$demand = new NewsDemand();
		$settings = array('list' => 'foo', 'orderBy' => 'datetime');

		$mockedSignalSlotDispatcher = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher', array('dispatch'));
		$configurationManager = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface'
		);

		$fixture = $this->getAccessibleMock(
			'GeorgRinger\\News\\Controller\\TagController',
			array('createDemandObjectFromSettings')
		);
		$fixture->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

		$fixture->injectTagRepository($this->tagRepository);
		$fixture->injectConfigurationManager($configurationManager);
		$fixture->setView($this->getMock('Tx_Fluid_View_TemplateView', array(), array(), '', FALSE));
		$fixture->_set('settings', $settings);

		$fixture->expects($this->once())->method('createDemandObjectFromSettings')
			->will($this->returnValue($demand));

		$this->tagRepository->expects($this->once())->method('findDemanded')
			->with($demand);

		$fixture->listAction();

		// datetime must be removed
		$this->assertEquals($fixture->_get('settings'), array('list' => 'foo'));
	}


}
