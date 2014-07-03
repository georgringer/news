<?php
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

/**
 * Tests for Tx_News_MediaRenderer_Video_Quicktime
 */
class Tx_News_Tests_Unit_MediaRenderer_Video_QuicktimeTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @dataProvider quicktimeFileIsRecognizedDataProvider
	 * @return void
	 */
	public function flvFileIsRecognized($expected, $expectedOutput) {
		$mediaElement = new Tx_News_Domain_Model_Media();
		$mediaElement->setMultimedia($expected);
		$mediaElement->setType(Tx_News_Domain_Model_Media::MEDIA_TYPE_MULTIMEDIA);

		$renderer = new Tx_News_MediaRenderer_Video_Quicktime();
		$this->assertEquals($expectedOutput, $renderer->enabled($mediaElement));
	}

	/**
	 * @return array
	 */
	public function quicktimeFileIsRecognizedDataProvider() {
		return array(
			'workingFile' => array(
				'fileadmin/fo/bar.mov', TRUE
			),
			'workingFileWithUpperCaseFileType' => array(
				'fileadmin/fo/bar.mov', TRUE
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
