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
 * Tests for domains model Media
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News2_Tests_Unit_Domain_Model_MediaTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @var Tx_News2_Domain_Model_Media
	 */
	protected $mediaDomainModelInstance;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->mediaDomainModelInstance = $this->objectManager->get('Tx_News2_Domain_Model_Media');
	}

	/**
	 * Test if title can be set
	 *
	 * @test
	 * @return void
	 */
	public function titleCanBeSet() {
		$title = 'File title';
		$this->mediaDomainModelInstance->setTitle($title);
		$this->assertEquals($title, $this->mediaDomainModelInstance->getTitle());
	}

	/**
	 * Test if crdate can be set
	 *
	 * @test
	 * @return void
	 */
	public function crdateCanBeSet() {
		$time = time();
		$this->mediaDomainModelInstance->setCrdate($time);
		$this->assertEquals($time, $this->mediaDomainModelInstance->getCrdate());
	}

	/**
	 * Test if tstamp can be set
	 *
	 * @test
	 * @return void
	 */
	public function tstampCanBeSet() {
		$time = time();
		$this->mediaDomainModelInstance->setTstamp($time);
		$this->assertEquals($time, $this->mediaDomainModelInstance->getTstamp());
	}

	/**
	 * Test if hidden can be set
	 *
	 * @test
	 * @return void
	 */
	public function hiddenCanBeSet() {
		$flag = 1;
		$this->mediaDomainModelInstance->setHidden($flag);
		$this->assertEquals($flag, $this->mediaDomainModelInstance->getHidden());
	}

	/**
	 * Test if deleted can be set
	 *
	 * @test
	 * @return void
	 */
	public function deletedCanBeSet() {
		$flag = 1;
		$this->mediaDomainModelInstance->setDeleted($flag);
		$this->assertEquals($flag, $this->mediaDomainModelInstance->getDeleted());
	}

	/**
	 * Test if showinpreview can be set
	 *
	 * @test
	 * @return void
	 */
	public function showinpreviewCanBeSet() {
		$flag = 1;
		$this->mediaDomainModelInstance->setShowinpreview($flag);
		$this->assertEquals($flag, $this->mediaDomainModelInstance->getShowinpreview());
	}

	/**
	 * Test if caption can be set
	 *
	 * @test
	 * @return void
	 */
	public function captionCanBeSet() {
		$caption = 'title';
		$this->mediaDomainModelInstance->setCaption($caption);
		$this->assertEquals($caption, $this->mediaDomainModelInstance->getCaption());
	}

	/**
	 * Test if alt can be set
	 *
	 * @test
	 * @return void
	 */
	public function altCanBeSet() {
		$alt = 'Fobar text';
		$this->mediaDomainModelInstance->setAlt($alt);
		$this->assertEquals($alt, $this->mediaDomainModelInstance->getAlt());
	}

	/**
	 * Test if type can be set
	 *
	 * @test
	 * @return void
	 */
	public function typeCanBeSet() {
		$type = 3;
		$this->mediaDomainModelInstance->setType($type);
		$this->assertEquals($type, $this->mediaDomainModelInstance->getType());
	}

	/**
	 * Test if width can be set
	 *
	 * @test
	 * @return void
	 */
	public function widthCanBeSet() {
		$width = 100;
		$this->mediaDomainModelInstance->setWidth($width);
		$this->assertEquals($width, $this->mediaDomainModelInstance->getWidth());
	}

	/**
	 * Test if height can be set
	 *
	 * @test
	 * @return void
	 */
	public function heightCanBeSet() {
		$height = 80;
		$this->mediaDomainModelInstance->setHeight($height);
		$this->assertEquals($height, $this->mediaDomainModelInstance->getHeight());
	}

	/**
	 * Test if sorting can be set
	 *
	 * @test
	 * @return void
	 */
	public function sortingCanBeSet() {
		$sorting = 12345678;
		$this->mediaDomainModelInstance->setSorting($sorting);
		$this->assertEquals($sorting, $this->mediaDomainModelInstance->getSorting());
	}

	/**
	 * Test if content can be set
	 *
	 * @test
	 * @return void
	 */
	public function contentCanBeSet() {
		$content = 'abcdefgh';
		$this->mediaDomainModelInstance->setContent($content);
		$this->assertEquals($content, $this->mediaDomainModelInstance->getContent());
	}
}
?>