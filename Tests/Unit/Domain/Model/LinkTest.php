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
 * Tests for domains model Link
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Domain_Model_LinkTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var Tx_News_Domain_Model_Link
	 */
	protected $linkDomainModelInstance;

	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->linkDomainModelInstance = $this->objectManager->get('Tx_News_Domain_Model_Link');
	}

	/**
	 * Test if title can be set
	 *
	 * @test
	 * @return void
	 */
	public function titleCanBeSet() {
		$title = 'File title';
		$this->linkDomainModelInstance->setTitle($title);
		$this->assertEquals($title, $this->linkDomainModelInstance->getTitle());
	}

	/**
	 * Test if crdate can be set
	 *
	 * @test
	 * @return void
	 */
	public function crdateCanBeSet() {
		$time = time();
		$this->linkDomainModelInstance->setCrdate($time);
		$this->assertEquals($time, $this->linkDomainModelInstance->getCrdate());
	}

	/**
	 * Test if tstamp can be set
	 *
	 * @test
	 * @return void
	 */
	public function tstampCanBeSet() {
		$time = time();
		$this->linkDomainModelInstance->setTstamp($time);
		$this->assertEquals($time, $this->linkDomainModelInstance->getTstamp());
	}

	/**
	 * Test if description can be set
	 *
	 * @test
	 * @return void
	 */
	public function descriptionCanBeSet() {
		$description = 'This is a description';
		$this->linkDomainModelInstance->setDescription($description);
		$this->assertEquals($description, $this->linkDomainModelInstance->getDescription());
	}

	/**
	 * Test if uri can be set
	 *
	 * @test
	 * @return void
	 */
	public function uriCanBeSet() {
		$uri = 'http://typo3.org';
		$this->linkDomainModelInstance->setUri($uri);
		$this->assertEquals($uri, $this->linkDomainModelInstance->getUri());
	}
}
