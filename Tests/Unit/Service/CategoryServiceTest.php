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
 * Test class for Tx_News_Service_CategoryService
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Service_CategoryServiceTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @test
	 * @dataProvider removeValuesFromStringDataProvider
	 */
	public function removeValuesFromString($expected, $given) {
		$result = Tx_News_Service_CategoryService::removeValuesFromString($given[0], $given[1]);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function removeValuesFromStringDataProvider() {
		return array(
			'simpleExampleWithRemovalAtEnd' => array(
				'1,2,3,4', array('1,2,3,4,5', '5')
			),
			'simpleExampleWithMixedRemovals' => array(
				'1,2,3,4', array('1,7,2,9,3,4', '9,7')
			),
			'removalIsSame' => array(
				'', array('1,2,3', '3,2,1')
			),
			'noRemovalFound' => array(
				'1,2,3', array('1,2,3', '9,8,7')
			),
			'noInputGiven' => array(
				'', array('', '9,8,7')
			),
		);
	}
}

?>