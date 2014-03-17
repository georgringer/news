<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Georg Ringer <typo3@ringerge.org>
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
