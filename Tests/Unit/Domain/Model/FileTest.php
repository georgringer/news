<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Georg Ringer <typo3@ringerge.org>
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
 * Tests for domains model File
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Domain_Model_FileTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var Tx_News_Domain_Model_File
	 */
	protected $fileDomainModelInstance;

	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->fileDomainModelInstance = $this->objectManager->get('Tx_News_Domain_Model_File');
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
		$time = new DateTime('now');
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
		$time = new DateTime('now');
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
