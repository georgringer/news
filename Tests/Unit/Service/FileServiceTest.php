<?php

namespace GeorgRinger\News\Tests\Unit\Service;

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
use GeorgRinger\News\Service\FileService;

/**
 * Test class for FileService
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class FileServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @expectedException \UnexpectedValueException
	 */
	public function emptyUrlThrowsException() {
		FileService::getCorrectUrl('');
	}

	/**
	 * @test
	 * @expectedException \UnexpectedValueException
	 */
	public function notAllowedPathThrowsException() {
		FileService::getCorrectUrl('../../fo.mp3');
	}

	/**
	 * @test
	 * @dataProvider validUrlIsReturnedDataProvider
	 */
	public function validUrlIsReturned($expected, $actual) {
		$result = FileService::getCorrectUrl($actual);

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

