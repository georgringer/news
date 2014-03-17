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
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Tests for Tx_News_ViewHelpers_IfIsActiveViewHelper
 */
class Tx_News_Tests_Unit_ViewHelpers_IfIsActiveViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var Tx_News_ViewHelpers_IfIsActiveViewHelper
	 */
	protected $viewHelper;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_IfIsActiveViewHelper', array('renderThenChild', 'renderElseChild'));
	}

	/**
	 * @test
	 * @return void
	 */
	public function elseChildIsCalledWithNoGetArguments() {
		$newsItem = new Tx_News_Domain_Model_News();
		$newsItem->_setProperty('uid', 123);

		$this->viewHelper->expects($this->once())
			->method('renderElseChild');

		$this->viewHelper->render($newsItem);
	}

	/**
	 * @test
	 * @return void
	 */
	public function elseChildIsCalledWithWrongGetArguments() {
		$_GET['tx_news_pi1']['news'] = 456;
		$newsItem = new Tx_News_Domain_Model_News();
		$newsItem->_setProperty('uid', 123);

		$this->viewHelper->expects($this->once())
			->method('renderElseChild');

		$this->viewHelper->render($newsItem);
	}

		/**
	 * @test
	 * @return void
	 */
	public function thenChildIsCalledWithCorrectArguments() {
		$_GET['tx_news_pi1']['news'] = '789';
		$newsItem = new Tx_News_Domain_Model_News();
		$newsItem->_setProperty('uid', 789);

		$this->viewHelper->expects($this->once())
			->method('renderThenChild');

		$this->viewHelper->render($newsItem);
	}

}