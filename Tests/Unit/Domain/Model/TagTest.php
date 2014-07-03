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
 * Tests for domains model Tag
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Domain_Model_TagTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var Tx_News_Domain_Model_Tag
	 */
	protected $tagDomainModelInstance;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->tagDomainModelInstance = new Tx_News_Domain_Model_Tag();
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
		$time = new DateTime('now');
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
		$time = new DateTime('now');
		$this->tagDomainModelInstance->setTstamp($time);
		$this->assertEquals($time, $this->tagDomainModelInstance->getTstamp());
	}

}
