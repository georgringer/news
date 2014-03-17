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
 * Tests for Tx_News_Hooks_Labels
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Hooks_LabelsTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {


	/**
	 * @test
	 * @dataProvider correctFieldOfArrayIsReturnedDataProvider
	 * @return void
	 */
	public function correctFieldOfArrayIsReturned($input, $expectedResult) {
		$mockTemplateParser = $this->getAccessibleMock('Tx_News_Hooks_Labels', array('dummy'));
		$result = $mockTemplateParser->_call('getTitleFromFields', $input[0], $input[1]);
		$this->assertEquals($expectedResult, $result);
	}

	public function correctFieldOfArrayIsReturnedDataProvider() {
		return array(
			'working example 1' => array(
				array('uid, title', array('title' => 'fobar')), 'fobar'
			),
			'1st found result is returned' => array(
				array('uid, title', array('uid' => '123', 'title' => 'fobar')), '123'
			),
			'empty fieldlist returns empty string' => array(
				array('', array('title' => 'fobar')), ''
			),
			'empty array returns empty string' => array(
				array('uid, title', array()), ''
			),
			'null returns empty string' => array(
				array('uid, title', NULL), ''
			),
		);
	}

	/**
	 * @test
	 * @dataProvider splitOfFileNameReturnsCorrectPartialDataProvider
	 * @return void
	 */
	public function splitOfFileNameReturnsCorrectPartial($string, $expectedResult) {
		$mockTemplateParser = $this->getAccessibleMock('Tx_News_Hooks_Labels', array('dummy'));
		$result = $mockTemplateParser->_call('splitFileName', $string);
		$this->assertEquals($expectedResult, $result);
	}

	public function splitOfFileNameReturnsCorrectPartialDataProvider() {
		return array(
			'working example 1' => array(
				'fobar|fobar', 'fobar'
			),
			'different strings' => array(
				'fo|bar', 'fo|bar'
			),
			'wrong count 1' => array(
				'fo|bar|xxx', 'fo|bar|xxx'
			),
			'wrong count 2' => array(
				'fobar', 'fobar'
			),
		);
	}
}
