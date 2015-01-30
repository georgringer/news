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
use GeorgRinger\News\MediaRenderer\Video\File;

/**
 * Tests for File
 */
class FileTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @dataProvider flvFileIsRecognizedDataProvider
	 * @return void
	 */
	public function flvFileIsRecognized($expected, $expectedOutput) {
		$mediaElement = new Media();
		$mediaElement->setMultimedia($expected);

		$renderer = new File();
		$this->assertEquals($expectedOutput, $renderer->enabled($mediaElement));
	}

	/**
	 * @return array
	 */
	public function flvFileIsRecognizedDataProvider() {
		return array(
			'workingFlv' => array(
				'fileadmin/fo/bar.flv', TRUE
			),
			'workingFlvWithUpperCaseFileType' => array(
				'fileadmin/fo/bar.FLV', TRUE
			),
			'otherFileType' => array(
				'fileadmin/someMusic.mp3', FALSE
			),
			'noMediaFileGiven' => array(
				NULL, FALSE
			),
			'emptyMediaFileGiven' => array(
				'', FALSE
			),
		);
	}

}
