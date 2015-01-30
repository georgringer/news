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
use GeorgRinger\News\MediaRenderer\Video\Vimeo;

/**
 * Tests for Vimeo
 */
class VimeoTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @dataProvider fileIsRecognizedDataProvider
	 * @return void
	 */
	public function VimeoLinkIsRecognized($expected, $expectedOutput) {
		$mediaElement = new Media();
		$mediaElement->setMultimedia($expected);
		$mediaElement->setType(Media::MEDIA_TYPE_MULTIMEDIA);

		$renderer = new Vimeo();
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
