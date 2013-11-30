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
 * Testcase for the Tx_News_Controller_NewsBaseController class.
 *
 * @package TYPO3
 * @subpackage tx_news
 *
 * @author Georg Ringer <mail@ringerge.org>
 */
class Tx_News_Tests_Unit_Controller_NewsBaseControllerTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
	 */
	protected $tsfe = NULL;

	/**
	 * Set up
	 */
	public function setUp() {
		$this->tsfe = $this->getAccessibleMock('tslib_fe', array('dummy'), array(), '', FALSE);
		$GLOBALS['TSFE'] = $this->tsfe;
	}

	/**
	 * @test
	 */
	public function emptyNoNewsFoundConfigurationReturnsNull() {
		$mockedController = $this->getAccessibleMock('Tx_News_Controller_NewsBaseController', array('dummy'));
		$result = $mockedController->_call('handleNoNewsFoundError', '');
		$this->assertNull($result);
	}

	/**
	 * @test
	 */
	public function invalidNoNewsFoundConfigurationReturnsNull() {
		$mockedController = $this->getAccessibleMock('Tx_News_Controller_NewsBaseController', array('dummy'));
		$result = $mockedController->_call('handleNoNewsFoundError', 'fo');
		$this->assertNull($result);
	}

	/**
	 * @test
	 */
	public function NoNewsFoundConfigurationRedirectsToListView() {
		$mock = $this->getAccessibleMock('Tx_News_Controller_NewsBaseController',
			array('redirect'));
		$mock->expects($this->once())
			->method('redirect')->with('list');
		$mock->_call('handleNoNewsFoundError', 'redirectToListView');
	}

	/**
	 * @test
	 */
	public function NoNewsFoundConfigurationCallsPageNotFoundHandler() {
		$mock = $this->getAccessibleMock('Tx_News_Controller_NewsBaseController',
			array('dummy'));

		$this->tsfe->expects($this->once())
			->method('pageNotFoundAndExit');
		$mock->_call('handleNoNewsFoundError', 'pageNotFoundHandler');
	}


}
