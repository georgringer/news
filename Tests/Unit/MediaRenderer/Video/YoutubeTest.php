<?php

namespace GeorgRinger\News\Tests\Unit\MediaRenderer\Video;

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
use GeorgRinger\News\Domain\Model\Media;

/**
 * Tests for Youtube
 */
class YoutubeTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @dataProvider fileIsRecognizedDataProvider
	 * @return void
	 */
	public function flvFileIsRecognized($expected, $expectedOutput) {
		$mediaElement = new Media();
		$mediaElement->setMultimedia($expected);
		$mediaElement->setType(Media::MEDIA_TYPE_MULTIMEDIA);

		$mockedSettingsService = $this->getMock('GeorgRinger\\News\\Service\\SettingsService', ['getSettings']);

		$renderer = $this->getAccessibleMock('GeorgRinger\\News\\MediaRenderer\\Video\\Youtube', ['dummy']);
		$renderer->_set('pluginSettingsService', $mockedSettingsService);
		$this->assertEquals($expectedOutput, $renderer->enabled($mediaElement));
	}

	/**
	 * @return array
	 */
	public function fileIsRecognizedDataProvider() {
		return [
			'defaultUrl' => [
				'http://www.youtube.com/watch?v=IGX2dXpTyns', TRUE
			],
			'shortUrl' => [
				'http://youtu.be/ko5CCSomDMY', TRUE
			],
			'noMediaFileGiven' => [
				NULL, FALSE
			],
			'emptyMediaFileGiven' => [
				'', FALSE
			],
			'localFileGiven' => [
				'fileadmin/fobar.flv', FALSE
			],
			'wrongDomainGiven' => [
				'http://www.somedomain.com/watch/1234', FALSE
			],
		];
	}

}
