<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

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

use GeorgRinger\News\Domain\Model\File;

/**
 * Tests for domains model File
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class FileTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var File
	 */
	protected $fileDomainModelInstance;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->fileDomainModelInstance = new File();
	}

	/**
	 * Test if title can be set
	 *
	 * @test
	 * @return void
	 */
	public function titleCanBeSet() {
		$title = 'File title';
		$this->fileDomainModelInstance->setTitle($title);
		$this->assertEquals($title, $this->fileDomainModelInstance->getTitle());
	}

	/**
	 * Test if description can be set
	 *
	 * @test
	 * @return void
	 */
	public function descriptionCanBeSet() {
		$description = 'File description';
		$this->fileDomainModelInstance->setDescription($description);
		$this->assertEquals($description, $this->fileDomainModelInstance->getDescription());
	}

	/**
	 * Test if file can be set
	 *
	 * @test
	 * @return void
	 */
	public function fileCanBeSet() {
		$file = 'fileadmin/fo.pdf';
		$this->fileDomainModelInstance->setFile($file);
		$this->assertEquals($file, $this->fileDomainModelInstance->getFile());
	}

	/**
	 * Test if crdate can be set
	 *
	 * @test
	 * @return void
	 */
	public function crdateCanBeSet() {
		$time = new \DateTime('now');
		$this->fileDomainModelInstance->setCrdate($time);
		$this->assertEquals($time, $this->fileDomainModelInstance->getCrdate());
	}

	/**
	 * Test if tstamp can be set
	 *
	 * @test
	 * @return void
	 */
	public function tstampCanBeSet() {
		$time = new \DateTime('now');
		$this->fileDomainModelInstance->setTstamp($time);
		$this->assertEquals($time, $this->fileDomainModelInstance->getTstamp());
	}

	/**
	 * Test for getFileExtension
	 *
	 * @test
	 * @return void
	 */
	public function getFileExtensionGetsCorrectFileExtension() {
		$this->fileDomainModelInstance->setFile('fileadmin/fo.jpg');
		$this->assertEquals('jpg', $this->fileDomainModelInstance->getFileExtension());
	}

	/**
	 * Test if file is an image
	 *
	 * @test
	 * @return void
	 */
	public function jpgIsAnImage() {
		$this->fileDomainModelInstance->setFile('fileadmin/fo.jpg');
		$this->assertEquals(TRUE, $this->fileDomainModelInstance->getIsImageFile());
	}

	/**
	 * Test if doc is not an image
	 *
	 * @test
	 * @return void
	 */
	public function wordFileIsNotAnImage() {
		$this->fileDomainModelInstance->setFile('fileadmin/fo.docx');
		$this->assertEquals(FALSE, $this->fileDomainModelInstance->getIsImageFile());
	}

}
