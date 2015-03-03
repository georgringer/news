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

/**
 * Testcase for the GeorgRinger\\News\\Controller\\NewsBaseController class.
 *
 * @package TYPO3
 * @subpackage tx_news
 *
 * @author Georg Ringer <mail@ringerge.org>
 */
class NewsBaseControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
	 */
	protected $tsfe = NULL;

	/**
	 * Set up
	 */
	public function setUp() {
		$this->tsfe = $this->getAccessibleMock('tslib_fe', array('pageNotFoundAndExit'), array(), '', FALSE);
		$GLOBALS['TSFE'] = $this->tsfe;
	}

	/**
	 * @test
	 */
	public function emptyNoNewsFoundConfigurationReturnsNull() {
		$mockedController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController', array('dummy'));
		$result = $mockedController->_call('handleNoNewsFoundError', '');
		$this->assertNull($result);
	}

	/**
	 * @test
	 */
	public function invalidNoNewsFoundConfigurationReturnsNull() {
		$mockedController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController', array('dummy'));
		$result = $mockedController->_call('handleNoNewsFoundError', 'fo');
		$this->assertNull($result);
	}

	/**
	 * @test
	 */
	public function NoNewsFoundConfigurationRedirectsToListView() {
		$mock = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController',
			array('redirect'));
		$mock->expects($this->once())
			->method('redirect')->with('list');
		$mock->_call('handleNoNewsFoundError', 'redirectToListView');
	}

	/**
	 * @test
	 */
	public function NoNewsFoundConfigurationCallsPageNotFoundHandler() {
		$mock = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController',
			array('dummy'));

		$this->tsfe->expects($this->once())
			->method('pageNotFoundAndExit');
		$mock->_call('handleNoNewsFoundError', 'pageNotFoundHandler');
	}

	/**
	 * @test
	 * @expectedException \InvalidArgumentException
	 */
	public function NoNewsFoundConfigurationThrowsExceptionWithTooLessRedirectToPageOptions() {
		$mock = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController',
			array('dummy'));
		$mock->_call('handleNoNewsFoundError', 'redirectToPage');
	}

	/**
	 * @test
	 * @expectedException \InvalidArgumentException
	 */
	public function NoNewsFoundConfigurationThrowsExceptionWithTooManyRedirectToPageOptions() {
		$mock = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController',
			array('dummy'));
		$mock->_call('handleNoNewsFoundError', 'redirectToPage,argumentOne,argumentTwo,argumentThree');
	}

	/**
	 * @test
	 */
	public function NoNewsFoundConfigurationRedirectsToCorrectPage() {
		$mockController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController', array('redirectToUri'));

		$mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
		$mockController->_set('uriBuilder', $mockUriBuilder);

		$mockUriBuilder->expects($this->once())
			->method('setTargetPageUid')->with('123');
		$mockUriBuilder->expects($this->once())
			->method('build');

		$mockController->_call('handleNoNewsFoundError', 'redirectToPage,123');
	}

	/**
	 * @test
	 */
	public function NoNewsFoundConfigurationRedirectsToCorrectPageAndStatus() {
		$mockController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController', array('redirectToUri'));

		$mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
		$mockController->_set('uriBuilder', $mockUriBuilder);

		$mockUriBuilder->expects($this->once())
			->method('setTargetPageUid')->with('456');
		$mockUriBuilder->expects($this->once())
			->method('build');
		$mockController->expects($this->once())
			->method('redirectToUri')->with(NULL, 0, 301);
		$mockController->_call('handleNoNewsFoundError', 'redirectToPage,456,301');
	}

	/**
	 * @test
	 */
	public function signalSlotGetsEmitted() {
		$mockedSignalSlotDispatcher = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher', array('dispatch'));
		$mockedController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController', array('dummy'));
		$mockedController->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

		$classPart = 'FoController';
		$signalArguments = array('fo' => 'bar');
		$name = 'foAction';

		$mockedSignalSlotDispatcher->expects($this->once())
			->method('dispatch')->with('GeorgRinger\\News\\Controller\\' . $classPart, $name, $signalArguments);

		$mockedController->_call('emitActionSignal', $classPart, $name, $signalArguments);
	}


}
