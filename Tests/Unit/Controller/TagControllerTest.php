<?php
/***************************************************************
* Copyright notice
*
* (c) 2014 Georg Ringer (typo3@ringerge.org)
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
 * Testcase for the Tx_News_Controller_TagController class.
 *
 * @package TYPO3
 * @subpackage tx_news
 *
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Controller_TagControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {


	/**
	 * @var Tx_News_Controller_ListController
	 */
	private $fixture = NULL;

	/**
	 * @var Tx_News_Domain_Repository_TagRepository
	 */
	private $tagRepository = NULL;

	/**
	 * Set up framework
	 *
	 * @return void
	 */
	public function setUp() {
		$this->fixture = new Tx_News_Controller_TagController();

		$this->tagRepository = $this->getMock(
			'Tx_News_Domain_Repository_TagRepository', array(), array(), '', FALSE
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
		$demand = new Tx_News_Domain_Model_Dto_NewsDemand();
		$settings = array('list' => 'foo', 'orderBy' => 'datetime');

		$configurationManager = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface'
		);

		$fixture = $this->getAccessibleMock(
			'Tx_News_Controller_TagController',
			array('createDemandObjectFromSettings')
		);
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
