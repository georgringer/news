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
 * Tests for domain repository newsRepository
 *
 * @package TYPO3
 * @subpackage tx_news2
 *
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 * @author Georg Ringer <mail@ringerge.org>
 *
 * @version $Id$
 */
class Tx_News2_Tests_Unit_Domain_Repository_NewsRepositoryDemandTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
//	var $testingFram

	/**
	 * @var Tx_Phpunit_Framework
	 */
	protected $testingFramework;

	public function setUp() {
		$this->testingFramework = new Tx_Phpunit_Framework('tx_news2');
	}

	/**
	 * @test
	 */
	public function findTopNews1Records() {
		$pid = 2;
		$newsRepository = $this->objectManager->get('Tx_News2_Domain_Repository_NewsRepository');

		/** @var $demand Tx_News2_Domain_Model_NewsDemand */
		$demand = $this->objectManager->get('Tx_News2_Domain_Model_NewsDemand');
		$demand->setIsDummyRecord(1);
		$demand->setStoragePage(2);

			// create some dummy records
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array('istopnews' => 1, 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array('istopnews' => 1, 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array('istopnews' => 0, 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array('istopnews' => 0, 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array('istopnews' => 0, 'pid' => $pid));

				// no matter about top news
		$demand = $this->objectManager->get('Tx_News2_Domain_Model_NewsDemand');
		$demand->setIsDummyRecord(1);
		$demand->setStoragePage(2);
		$demand->setTopNewsSetting(0);
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 5);

				// Only Top news
		$demand = $this->objectManager->get('Tx_News2_Domain_Model_NewsDemand');
		$demand->setIsDummyRecord(1);
		$demand->setStoragePage(2);
		$demand->setTopNewsSetting(1);
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 2);

				// Only non Top news
		$demand = $this->objectManager->get('Tx_News2_Domain_Model_NewsDemand');
		$demand->setIsDummyRecord(1);
		$demand->setStoragePage(2);
		$demand->setTopNewsSetting(2);
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 3);


	}

	public function tearDown() {
		$this->testingFramework->cleanUp();
		unset($this->testingFramework);
	}
}
?>
