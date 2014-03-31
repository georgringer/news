<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Tests for Tx_News_ViewHelpers_Widget_Controller_PaginateController
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_ViewHelpers_Widget_Controller_PaginateControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var Tx_News_ViewHelpers_Widget_Controller_PaginateController
	 */
	protected $controller;

	/**
	 * Sets up this test case
	 *
	 * @return void
	 */
	public function setUp() {
		$this->controller = $this->getAccessibleMock('Tx_News_ViewHelpers_Widget_Controller_PaginateController', array('dummy'), array(), '', FALSE);
	}

	/**
	 * @test
	 */
	public function initializationIsCorrect() {
		$controller = $this->getAccessibleMock('Tx_News_ViewHelpers_Widget_Controller_PaginateController', array('dummy'));
		$objects = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14);
		$configuration = array(
			'templatePath' => 'fo/bar',
			'itemsPerPage' => '3',
		);
		$widgetConfiguration = array('fo' => 'bar');
		$controller->_set('configuration', $configuration);
		$controller->_set(
			'widgetConfiguration',
			array(
				'configuration' => $widgetConfiguration,
				'objects' => $objects
			));

		$controller->initializeAction();
		\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($configuration, $widgetConfiguration, TRUE);
		$this->assertEquals($controller->_get('objects'), $objects);
		$this->assertEquals($controller->_get('configuration'), $configuration);
		$this->assertEquals($controller->_get('numberOfPages'), 5);
		$this->assertEquals($controller->_get('templatePath'), PATH_site . 'fo/bar');
	}


	/**
	 * @test
	 */
	public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForEvenMaximumNumberOfLinks() {
		$this->controller->_set('maximumNumberOfLinks', 8);
		$this->controller->_set('numberOfPages', 100);
		$this->controller->_set('currentPage', 50);
		$this->controller->_call('calculateDisplayRange');
		$this->assertSame(46, $this->controller->_get('displayRangeStart'));
		$this->assertSame(53, $this->controller->_get('displayRangeEnd'));
	}

	/**
	 * @test
	 */
	public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForOddMaximumNumberOfLinks() {
		$this->controller->_set('maximumNumberOfLinks', 7);
		$this->controller->_set('numberOfPages', 100);
		$this->controller->_set('currentPage', 50);
		$this->controller->_call('calculateDisplayRange');
		$this->assertSame(47, $this->controller->_get('displayRangeStart'));
		$this->assertSame(53, $this->controller->_get('displayRangeEnd'));
	}

	/**
	 * @test
	 */
	public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForEvenMaximumNumberOfLinksWhenOnFirstPage() {
		$this->controller->_set('maximumNumberOfLinks', 8);
		$this->controller->_set('numberOfPages', 100);
		$this->controller->_set('currentPage', 1);
		$this->controller->_call('calculateDisplayRange');
		$this->assertSame(1, $this->controller->_get('displayRangeStart'));
		$this->assertSame(8, $this->controller->_get('displayRangeEnd'));
	}

	/**
	 * @test
	 */
	public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForOddMaximumNumberOfLinksWhenOnFirstPage() {
		$this->controller->_set('maximumNumberOfLinks', 7);
		$this->controller->_set('numberOfPages', 100);
		$this->controller->_set('currentPage', 1);
		$this->controller->_call('calculateDisplayRange');
		$this->assertSame(1, $this->controller->_get('displayRangeStart'));
		$this->assertSame(7, $this->controller->_get('displayRangeEnd'));
	}

	/**
	 * @test
	 */
	public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForEvenMaximumNumberOfLinksWhenOnLastPage() {
		$this->controller->_set('maximumNumberOfLinks', 8);
		$this->controller->_set('numberOfPages', 100);
		$this->controller->_set('currentPage', 100);
		$this->controller->_call('calculateDisplayRange');
		$this->assertSame(93, $this->controller->_get('displayRangeStart'));
		$this->assertSame(100, $this->controller->_get('displayRangeEnd'));
	}

	/**
	 * @test
	 */
	public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForOddMaximumNumberOfLinksWhenOnLastPage() {
		$this->controller->_set('maximumNumberOfLinks', 7);
		$this->controller->_set('numberOfPages', 100);
		$this->controller->_set('currentPage', 100);
		$this->controller->_call('calculateDisplayRange');
		$this->assertSame(94, $this->controller->_get('displayRangeStart'));
		$this->assertSame(100, $this->controller->_get('displayRangeEnd'));
	}

}
