<?php
	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
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
	 * Tests for Tx_News_ViewHelpers_ExcludeDisplayedNewsViewHelper
	 *
	 * @package TYPO3
	 * @subpackage tx_news
	 * @author Georg Ringer <typo3@ringerge.org>
	 */
class Tx_News_Tests_Unit_ViewHelpers_ExcludeDisplayedNewsViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @return void
	 */
	public function newsIsAddedToExcludedList() {

		$viewHelper = new Tx_News_ViewHelpers_ExcludeDisplayedNewsViewHelper();
		$this->assertEquals($GLOBALS['EXT']['news']['alreadyDisplayed'], NULL);

		$newsItem1 = new Tx_News_Domain_Model_News();
		$newsItem1->_setProperty('uid', '123');

		$viewHelper->render($newsItem1);
		$this->assertEquals($GLOBALS['EXT']['news']['alreadyDisplayed'], array('123' => '123'));

		$newsItem1 = new Tx_News_Domain_Model_News();
		$newsItem1->_setProperty('uid', '123');
		$this->assertEquals($GLOBALS['EXT']['news']['alreadyDisplayed'], array('123' => '123'));

		$newsItem2 = new Tx_News_Domain_Model_News();
		$newsItem2->_setProperty('uid', '12');
		$viewHelper->render($newsItem2);
		$this->assertEquals($GLOBALS['EXT']['news']['alreadyDisplayed'], array('123' => '123', '12' => '12'));

		$newsItem3 = new Tx_News_Domain_Model_News();
		$newsItem3->_setProperty('uid', '12');
		$newsItem3->_setProperty('_localizedUid', '456');
		$viewHelper->render($newsItem3);
		$this->assertEquals($GLOBALS['EXT']['news']['alreadyDisplayed'], array('123' => '123', '12' => '12', '456' => '456'));
	}
}