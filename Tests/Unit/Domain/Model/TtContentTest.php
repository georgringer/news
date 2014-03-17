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
 * Tests for tt_content model
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Domain_Model_TtContentTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var Tx_News_Domain_Model_TtContent
	 */
	protected $ttContentDomainModelInstance;

	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

		/** @var Tx_News_Domain_Model_TtContent ttContentDomainModelInstance */
		$this->ttContentDomainModelInstance = $this->objectManager->get('Tx_News_Domain_Model_TtContent');
	}

	/**
	 * @test
	 */
	public function crdateCanBeSet() {
		$fieldValue = new DateTime();
		$this->ttContentDomainModelInstance->setCrdate($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCrdate());
	}

	/**
	 * @test
	 */
	public function tstampCanBeSet() {
		$fieldValue = new DateTime();
		$this->ttContentDomainModelInstance->setTstamp($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getTstamp());
	}

	/**
	 * @test
	 */
	public function cTypeCanBeSet() {
		$fieldValue = 'fo123';
		$this->ttContentDomainModelInstance->setCType($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCType());
	}

	/**
	 * @test
	 */
	public function headerCanBeSet() {
		$fieldValue = 'fo123';
		$this->ttContentDomainModelInstance->setHeader($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeader());
	}

	/**
	 * @test
	 */
	public function headerPositionCanBeSet() {
		$fieldValue = 'fo123';
		$this->ttContentDomainModelInstance->setHeaderPosition($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderPosition());
	}

	/**
	 * @test
	 */
	public function bodytextCanBeSet() {
		$fieldValue = 'fo123';
		$this->ttContentDomainModelInstance->setBodytext($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getBodytext());
	}

	/**
	 * @test
	 */
	public function colPosCanBeSet() {
		$fieldValue = 1;
		$this->ttContentDomainModelInstance->setColPos($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getColPos());
	}

	/**
	 * @test
	 */
	public function imageCanBeSet() {
		$fieldValue = 'fo123';
		$this->ttContentDomainModelInstance->setImage($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImage());
	}

	/**
	 * @test
	 */
	public function imageWidthCanBeSet() {
		$fieldValue = 123;
		$this->ttContentDomainModelInstance->setImagewidth($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagewidth());
	}

	/**
	 * @test
	 */
	public function imageOrientCanBeSet() {
		$fieldValue = 'Test123';
		$this->ttContentDomainModelInstance->setImageorient($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageorient());
	}

	/**
	 * @test
	 */
	public function imageCaptionCanBeSet() {
		$fieldValue = 'Test123';
		$this->ttContentDomainModelInstance->setImagecaption($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagecaption());
	}

	/**
	 * @test
	 */
	public function imageColsCanBeSet() {
		$fieldValue = 123;
		$this->ttContentDomainModelInstance->setImagecols($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagecols());
	}

	/**
	 * @test
	 */
	public function imageBorderCanBeSet() {
		$fieldValue = 123;
		$this->ttContentDomainModelInstance->setImageborder($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageborder());
	}

	/**
	 * @test
	 */
	public function mediaCanBeSet() {
		$fieldValue = 'Test 123';
		$this->ttContentDomainModelInstance->setMedia($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getMedia());
	}

	/**
	 * @test
	 */
	public function layoutCanBeSet() {
		$fieldValue = 'Test 123';
		$this->ttContentDomainModelInstance->setLayout($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getLayout());
	}

	/**
	 * @test
	 */
	public function colsCanBeSet() {
		$fieldValue = 123;
		$this->ttContentDomainModelInstance->setCols($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCols());
	}

	/**
	 * @test
	 */
	public function subheaderCanBeSet() {
		$fieldValue = 'Test 123';
		$this->ttContentDomainModelInstance->setSubheader($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getSubheader());
	}

	/**
	 * @test
	 */
	public function headerLinkCanBeSet() {
		$fieldValue = 'Test 123';
		$this->ttContentDomainModelInstance->setHeaderLink($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderLink());
	}

	/**
	 * @test
	 */
	public function imageLinkCanBeSet() {
		$fieldValue = 'Test 123';
		$this->ttContentDomainModelInstance->setImageLink($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageLink());
	}

	/**
	 * @test
	 */
	public function imageZoomCanBeSet() {
		$fieldValue = 'Test 123';
		$this->ttContentDomainModelInstance->setImageZoom($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageZoom());
	}

	/**
	 * @test
	 */
	public function altTextCanBeSet() {
		$fieldValue = 'Test 123';
		$this->ttContentDomainModelInstance->setAltText($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getAltText());
	}

	/**
	 * @test
	 */
	public function titleTextCanBeSet() {
		$fieldValue = 'Test 123';
		$this->ttContentDomainModelInstance->setTitleText($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getTitleText());
	}

	/**
	 * @test
	 */
	public function headerLayoutCanBeSet() {
		$fieldValue = 'Test 123';
		$this->ttContentDomainModelInstance->setHeaderLayout($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderLayout());
	}

	/**
	 * @test
	 */
	public function listTypeCanBeSet() {
		$fieldValue = 'Test 123';
		$this->ttContentDomainModelInstance->setListType($fieldValue);
		$this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getListType());
	}

}
