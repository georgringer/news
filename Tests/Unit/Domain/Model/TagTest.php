<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * Tests for domains model Tag
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News2_Tests_Unit_Domain_Model_TagTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @var Tx_News2_Domain_Model_Tag
	 */
	protected $tagDomainModelInstance;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->tagDomainModelInstance = $this->objectManager->get('Tx_News2_Domain_Model_Tag');
	}

	/**
	 * Test if title can be set
	 *
	 * @test
	 * @return void
	 */
	public function titleCanBeSet() {
		$title = 'Tag title';
		$this->tagDomainModelInstance->setTitle($title);
		$this->assertEquals($title, $this->tagDomainModelInstance->getTitle());
	}

	/**
	 * Test if crdate can be set
	 *
	 * @test
	 * @return void
	 */
	public function crdateCanBeSet() {
		$time = time();
		$this->tagDomainModelInstance->setCrdate($time);
		$this->assertEquals($time, $this->tagDomainModelInstance->getCrdate());
	}

	/**
	 * Test if tstamp can be set
	 *
	 * @test
	 * @return void
	 */
	public function tstampCanBeSet() {
		$time = time();
		$this->tagDomainModelInstance->setTstamp($time);
		$this->assertEquals($time, $this->tagDomainModelInstance->getTstamp());
	}

}
?>