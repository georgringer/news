<?php

namespace GeorgRinger\News\Tests\Unit\Hooks;

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
 * Tests for Labels
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class LabelsTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {


	/**
	 * @test
	 * @dataProvider correctFieldOfArrayIsReturnedDataProvider
	 * @return void
	 */
	public function correctFieldOfArrayIsReturned($input, $expectedResult) {
		$mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\Hooks\\Labels', array('dummy'));
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
		$mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\Hooks\\Labels', array('dummy'));
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
