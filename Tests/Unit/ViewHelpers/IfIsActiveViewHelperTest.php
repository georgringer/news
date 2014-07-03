<?php
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