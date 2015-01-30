<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Format;

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
use GeorgRinger\News\ViewHelpers\Format\DateViewHelper;

/**
 * Tests for \GeorgRinger\News\ViewHelpers\Format\DateViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class DateViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if given format works
	 *
	 * @test
	 * @dataProvider correctDateIsReturnedDataProvider
	 * @return void
	 */
	public function correctDateIsReturned($expectedResult, $settings) {
		$viewHelper = new DateViewHelper();
		$this->assertEquals($viewHelper->render($settings['date'], $settings['format'], $settings['currentDate'], $settings['strftime']), $expectedResult);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function correctDateIsReturnedDataProvider() {
		$currentDate = new \DateTime('@' . $GLOBALS['EXEC_TIME']);

		return array(
			'stfTimeDateGiven' => array(
				'08 2012', array(
					'date' => new \DateTime('2012-07-08 11:14:15'),
					'format' => '%d %Y',
					'currentDate' => FALSE,
					'strftime' => TRUE
				)
			),
			'dateTimeGiven' => array(
				'2012', array(
					'date' => new \DateTime('2012-07-08 11:14:15'),
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
