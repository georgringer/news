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
 * Test class for Tx_News_Service_FileService
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Service_FileServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @expectedException UnexpectedValueException
	 */
	public function emptyUrlThrowsException() {
		Tx_News_Service_FileService::getCorrectUrl('');
	}

	/**
	 * @test
	 * @expectedException UnexpectedValueException
	 */
	public function notAllowedPathThrowsException() {
		Tx_News_Service_FileService::getCorrectUrl('../../fo.mp3');
	}

	/**
	 * @test
	 * @dataProvider validUrlIsReturnedDataProvider
	 */
	public function validUrlIsReturned($expected, $actual) {
		$result = Tx_News_Service_FileService::getCorrectUrl($actual);

		$this->assertEquals($expected, $result, 'exp:' . $expected .  ', <br />' . $result);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function validUrlIsReturnedDataProvider() {
		$siteURL = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
		return array(
			'validUrl' => array(
				'http://www.domain.com/file.flv', 'http://www.domain.com/file.flv'
			),
			'simpleRelativeFileInFileadmin' => array(
				$siteURL . 'fileadmin/fo.flv', 'fileadmin/fo.flv'
			),
			'simpleRelativeFileInRoot' => array(
				$siteURL . 'bar.flv', 'bar.flv'
			)
		);
	}
}

