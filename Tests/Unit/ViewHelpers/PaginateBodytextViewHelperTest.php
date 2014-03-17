<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Georg Ringer <typo3@ringerge.org>
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Tests for PaginateBodytextViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_ViewHelpers_PaginateBodytextViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if given tag is a closing tag
	 *
	 * @test
	 * @dataProvider givenTagIsAClosingTagDataProvider
	 * @return void
	 */
	public function givenTagIsAClosingTag($tag, $expectedResult) {
		$mockTemplateParser = $this->getAccessibleMock('Tx_News_ViewHelpers_PaginateBodytextViewHelper', array('dummy'));
		$result = $mockTemplateParser->_call('isClosingTag', $tag);
		$this->assertEquals($expectedResult, $result);
	}

	public function givenTagIsAClosingTagDataProvider() {
		return array(
			'working example 1' => array(
				'</div>', TRUE
			),
			'working example 2' => array(
				'<div>', FALSE
			),
		);
	}

	/**
	 * Test if given tag is a self closing tag
	 *
	 * @test
	 * @dataProvider givenTagIsSelfClosingTagDataProvider
	 * @return void
	 */
	public function givenTagIsSelfClosingTag($tag, $expectedResult) {
		$mockTemplateParser = $this->getAccessibleMock('Tx_News_ViewHelpers_PaginateBodytextViewHelper', array('dummy'));
		$result = $mockTemplateParser->_call('isSelfClosingTag', $tag);
		$this->assertEquals($expectedResult, $result);
	}

	public function givenTagIsSelfClosingTagDataProvider() {
		return array(
			'working example 1' => array(
				'<hr />', TRUE
			),
			'working example 2' => array(
				'<div>', FALSE
			),
		);
	}

	/**
	 * Test if given tag is an opening tag
	 *
	 * @test
	 * @dataProvider givenTagIsAnOpeningTagDataProvider
	 * @return void
	 */
	public function givenTagIsAnOpeningTag($tag, $expectedResult) {
		$mockTemplateParser = $this->getAccessibleMock('Tx_News_ViewHelpers_PaginateBodytextViewHelper', array('dummy'));
		$result = $mockTemplateParser->_call('isOpeningTag', $tag);
		$this->assertEquals($expectedResult, $result);
	}

	public function givenTagIsAnOpeningTagDataProvider() {
		return array(
			array('<div>', TRUE),
			array('</div>', FALSE),
			array('<div/>', FALSE),
			array('<div />', FALSE)
		);
	}

	/**
	 * Test if given tag is an opening tag
	 *
	 * @test
	 * @dataProvider extractTagReturnsCorrectOneDataProvider
	 * @return void
	 */
	public function extractTagReturnsCorrectOne($tag, $expectedResult) {
		$mockTemplateParser = $this->getAccessibleMock('Tx_News_ViewHelpers_PaginateBodytextViewHelper', array('dummy'));
		$result = $mockTemplateParser->_call('extractTag', $tag);
		$this->assertEquals($expectedResult, $result, sprintf('"%s" (%s) : "%s" (%s)', $tag, strlen($tag), $expectedResult, strlen($expectedResult)), 1);
	}

	public function extractTagReturnsCorrectOneDataProvider() {
		return array(
			array('this <strong>is</strong> a <div>real</div>test', 'this <strong>'),
			array('this <br /> linebreak', 'this <br />'),
			array('this <br> linebreak', 'this <br>'),
			array('this is a test', 'this is a test'),
		);
	}


}
