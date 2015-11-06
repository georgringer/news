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
use GeorgRinger\News\MediaRenderer\Video\Quicktime;

/**
 * Tests for Quicktime
 */
class QuicktimeTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @dataProvider quicktimeFileIsRecognizedDataProvider
	 * @return void
	 */
	public function flvFileIsRecognized($expected, $expectedOutput) {
		$mediaElement = new Media();
		$mediaElement->setMultimedia($expected);
		$mediaElement->setType(Media::MEDIA_TYPE_MULTIMEDIA);

		$renderer = new Quicktime();
		$this->assertEquals($expectedOutput, $renderer->enabled($mediaElement));
	}

	/**
	 * @return array
	 */
	public function quicktimeFileIsRecognizedDataProvider() {
		return [
			'workingFile' => [
				'fileadmin/fo/bar.mov', TRUE
			],
			'workingFileWithUpperCaseFileType' => [
				'fileadmin/fo/bar.mov', TRUE
			],
			'otherFileType' => [
				'fileadmin/someMusic.mp3', FALSE
			],
			'noMediaFileGiven' => [
				NULL, FALSE
			],
			'emptyMediaFileGiven' => [
				'', FALSE
			],
		];
	}

}
