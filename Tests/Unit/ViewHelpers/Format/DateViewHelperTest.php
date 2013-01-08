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
 * Tests for Tx_News_ViewHelpers_Format_DateViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_ViewHelpers_Format_DateViewHelperTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * Test if given format works
	 *
	 * @test
	 * @dataProvider correctDateIsReturnedDataProvider
	 * @return void
	 */
	public function correctDateIsReturned($expectedResult, $settings) {
		$viewHelper = new Tx_News_ViewHelpers_Format_DateViewHelper();
		$this->assertEquals($viewHelper->render($settings['date'], $settings['format'], $settings['currentDate'], $settings['strftime']), $expectedResult);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function correctDateIsReturnedDataProvider() {
		$currentDate = new DateTime('@' . $GLOBALS['EXEC_TIME']);

		return array(
			'stfTimeDateGiven' => array(
				'08 2012', array(
					'date' => new DateTime("2012-07-08 11:14:15"),
					'format' => '%d %Y',
					'currentDate' => FALSE,
					'strftime' => TRUE
				)
			),
			'dateTimeGiven' => array(
				'2012', array(
					'date' => new DateTime("2012-07-08 11:14:15"),
					'format' => 'Y',
					'currentDate' => FALSE,
					'strftime' => FALSE
				)
			),
			'currentDate' => array(
				strftime('%Y', $currentDate->format('U')), array(
					'date' => $currentDate,
					'format' => 'Y',
					'currentDate' => TRUE,
					'strftime' => FALSE
				)
			),
		);
	}
}

?>