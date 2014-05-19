<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * Test for Tx_News_ViewHelpers_SimplePrevNextViewHelper
 */
class Tx_News_Tests_Unit_Tx_News_ViewHelpers_SimplePrevNextViewHelperTest extends \TYPO3\CMS\Fluid\Tests\Unit\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface|\Tx_News_ViewHelpers_SimplePrevNextViewHelper
	 */
	protected $viewHelper;

	/**
	 * Set up
	 */
	public function setUp() {
		parent::setUp();

		$this->viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_SimplePrevNextViewHelper', array('dummy'));
	}

	/**
	 * @test
	 */
	public function wrongIdWillReturnNullForObject() {
		$out = $this->viewHelper->_call('getObject', 0);
		$this->assertEquals($out, NULL);
	}

	/**
	 * @test
	 */
	public function queryResultWillReturnCorrectOutputForAllLinks() {
		$viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_SimplePrevNextViewHelper', array('getObject'));

		$in = array(
			0 => array('uid' => 123),
			1 => array('uid' => 456),
			2 => array('uid' => 789),
		);
		$exp = array('prev' => 123, 'next' => 789);
		$viewHelper->expects($this->at(0))->method('getObject')->will($this->returnValue(123));
		$viewHelper->expects($this->at(1))->method('getObject')->will($this->returnValue(789));
		$out = $viewHelper->_call('mapResultToObjects', $in);
		$this->assertEquals($out, $exp);
	}

	/**
	 * @test
	 */
	public function queryResultWillReturnCorrectOutputFor2Links() {
		$viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_SimplePrevNextViewHelper', array('getObject'));

		$in = array(
			0 => array('uid' => 147),
			1 => array('uid' => 258),
		);
		$exp = array('prev' => 147);
		$viewHelper->expects($this->at(0))->method('getObject')->will($this->returnValue(147));
		$out = $viewHelper->_call('mapResultToObjects', $in);
		$this->assertEquals($out, $exp);
	}

	/**
	 * @test
	 */
	public function queryResultWillReturnCorrectOutputFor1Link() {
		$viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_SimplePrevNextViewHelper', array('getObject'));

		$in = array(
			0 => array('uid' => 369),
		);
		$exp = array('next' => 369);
		$viewHelper->expects($this->at(0))->method('getObject')->will($this->returnValue(369));
		$out = $viewHelper->_call('mapResultToObjects', $in);
		$this->assertEquals($out, $exp);
	}

	/**
	 * @test
	 * @expectedException \UnexpectedValueException
	 */
	public function queryResultWillReturnExceptionForUnknownCount() {
		$viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_SimplePrevNextViewHelper', array('getObject'));

		$in = array(
			0 => array('uid' => 369),
			1 => array('uid' => 369),
			2 => array('uid' => 369),
			3 => array('uid' => 369),
		);
		$out = $viewHelper->_call('mapResultToObjects', $in);
	}

}