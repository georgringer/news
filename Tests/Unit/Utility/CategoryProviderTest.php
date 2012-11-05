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
 * Tests for Tx_News_Utility_CategoryProvider
 */
class Tx_News_Tests_Unit_Utility_CategoryProviderTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @var array
	 */
	private $backupGlobalVariables;

	public function setUp() {
		$this->backupGlobalVariables = array(
			'BE_USER' => $GLOBALS['BE_USER'],
		);
	}

	public function tearDown() {
		foreach ($this->backupGlobalVariables as $key => $data) {
			$GLOBALS[$key] = $data;
		}
		unset($this->backupGlobalVariables);
	}

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @dataProvider mountDataProvider
	 * @return void
	 */
	public function testForValidOrdering($expectedFields, $expected) {
		$categoryMountProvider = new Tx_News_Utility_CategoryProvider();
		$GLOBALS['BE_USER']->user['tx_news_categorymounts'] = $expectedFields['user'];
		$GLOBALS['BE_USER']->userGroups = $expectedFields['group'];

		$this->assertEquals($expected, $categoryMountProvider::getUserMounts());
	}

	public function mountDataProvider() {
		return array(
			'emptyValues' => array(
				array('user' => '', 'group' => NULL), ''
			),
			'setUserDataAndEmptyGroup' => array(
				array('user' => '1,2,3,4', 'group' => array()), '1,2,3,4'
			),
			'emptyUserDataAndFilledGroup' => array(
				array('user' => NULL, 'group' => array(1 => array('tx_news_categorymounts' => '1,2,3,4'))), '1,2,3,4'
			),
			'allDataGiven' => array(
				array('user' => '1,2,3', 'group' => array(1 => array('tx_news_categorymounts' => '4,5,6'))), '4,5,6,1,2,3'
			),
			'identDoubleKeysGiven' => array(
				array('user' => '1,2,3', 'group' => array(1 => array('tx_news_categorymounts' => '1,2,3'))), '1,2,3'
			),
			'someDoubleKeysGiven' => array(
				array('user' => '1,2,3', 'group' => array(1 => array('tx_news_categorymounts' => '3,4,5'))), '3,4,5,1,2'
			),
			'multipleGroupsGiven' => array(
				array('user' => '1,7', 'group' => array(1 => array('tx_news_categorymounts' => '2,3'), 2 => array('tx_news_categorymounts' => '1,3,4'))), '2,3,1,4,7'
			),

		);
	}

}
?>