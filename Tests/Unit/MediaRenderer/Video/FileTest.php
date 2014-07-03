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
 * Tests for Tx_News_Tests_Interfaces_Video_FileTest
 */
class Tx_News_Tests_Unit_MediaRenderer_Video_FileTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @dataProvider flvFileIsRecognizedDataProvider
	 * @return void
	 */
	public function flvFileIsRecognized($expected, $expectedOutput) {
		$mediaElement = new Tx_News_Domain_Model_Media();
		$mediaElement->setMultimedia($expected);

		$renderer = new Tx_News_MediaRenderer_Video_File();
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
