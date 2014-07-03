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
 * Tests for Tx_News_MediaRenderer_Video_Vimeo
 */
class Tx_News_Tests_Unit_MediaRenderer_Video_VimeoTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @dataProvider fileIsRecognizedDataProvider
	 * @return void
	 */
	public function VimeoLinkIsRecognized($expected, $expectedOutput) {
		$mediaElement = new Tx_News_Domain_Model_Media();
		$mediaElement->setMultimedia($expected);
		$mediaElement->setType(Tx_News_Domain_Model_Media::MEDIA_TYPE_MULTIMEDIA);

		$renderer = new Tx_News_MediaRenderer_Video_Vimeo();
		$this->assertEquals($expectedOutput, $renderer->enabled($mediaElement));
	}

	/**
	 * @return array
	 */
	public function fileIsRecognizedDataProvider() {
		return array(
			'defaultUrl' => array(
				'http://vimeo.com/16850096', TRUE
			),
			'noMediaFileGiven' => array(
				NULL, FALSE
			),
			'emptyMediaFileGiven' => array(
				'', FALSE
			),
			'localFileGiven' => array(
				'fileadmin/fobar.flv', FALSE
			),
			'wrongDomainGiven' => array(
				'http://youtu.be/ko5CCSomDMY', FALSE
			),
		);
	}

}
