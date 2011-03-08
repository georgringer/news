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
 */
class Tx_News2_Tests_Unit_Domain_Repository_NewsRepositoryDemandTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @var Tx_Phpunit_Framework
	 */
	protected $testingFramework;

	public function setUp() {
		$this->testingFramework = new Tx_Phpunit_Framework('tx_news2');
	}

	/**
	 * Test if top news constraint works
	 *
	 * @test
	 * @return void
	 */
	public function findTopNewsRecords() {
		$pid = 2;
		$newsRepository = $this->objectManager->get('Tx_News2_Domain_Repository_NewsRepository');

		/** @var $demand Tx_News2_Domain_Model_NewsDemand */
		$demand = $this->objectManager->get('Tx_News2_Domain_Model_NewsDemand');
		$demand->setIsDummyRecord(1);
		$demand->setStoragePage($pid);

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
		$demand->setTopNewsRestriction(0);
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 5);

			// Only Top news
		$demand->setTopNewsRestriction(1);
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 2);

			// Only non Top news
		$demand->setTopNewsRestriction(2);
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 3);
	}

	/**
	 * Test if latestlimit constraint works
	 *
	 * @test
	 * @return void
	 */
	public function findLatestLimitRecords() {
		$pid = 91;
		$newsRepository = $this->objectManager->get('Tx_News2_Domain_Repository_NewsRepository');

		/** @var $demand Tx_News2_Domain_Model_NewsDemand */
		$demand = $this->objectManager->get('Tx_News2_Domain_Model_NewsDemand');
		$demand->setIsDummyRecord(1);
		$demand->setStoragePage($pid);

			// create some dummy records
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
				'datetime' => (time() + (-10 * 86400)), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
				'datetime' => (time() + (-7 * 86400)), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
				'datetime' => (time() + (-4 * 86400)), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
				'datetime' => (time() + (-3 * 86400)), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
				'datetime' => (time() + (1 * 86400)), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
				'datetime' => (time() + (3 * 86400)), 'pid' => $pid));

			// maximum 8 days old
		$demand->setTimeRestriction('-8 days');
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 5);

			// get all news maximum 6 days old
		$demand->setTimeRestriction((6 * 86400));
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 4);

			// no restriction should get you all
		$demand->setTimeRestriction(0);
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 6);
	}

	/**
	 * Test if record by month/year constraint works
	 *
	 * @test
	 * @return void
	 */
	public function findRecordsByMonthAndYear() {
		$pid = 92;
		$newsRepository = $this->objectManager->get('Tx_News2_Domain_Repository_NewsRepository');

		/** @var $demand Tx_News2_Domain_Model_NewsDemand */
		$demand = $this->objectManager->get('Tx_News2_Domain_Model_NewsDemand');
		$demand->setIsDummyRecord(1);
		$demand->setStoragePage($pid);

			// create some dummy records
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
					'datetime' => strtotime('2011/02/20'), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
					'datetime' => strtotime('2011/02/22'), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
					'datetime' => strtotime('2011/03/1'), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
					'datetime' => strtotime('2011/03/10'), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
					'datetime' => strtotime('2011/03/31'), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
					'datetime' => strtotime('2011/03/10'), 'pid' => $pid));
		$this->testingFramework->createRecord(
				'tx_news2_domain_model_news', array(
					'datetime' => strtotime('2011/04/1'), 'pid' => $pid));

			// set month and year with integers
		$demand->setDateField('datetime');
		$demand->setMonth(4);
		$demand->setYear(2011);
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 1);

		$demand->setDateField('datetime');
		$demand->setMonth('4');
		$demand->setYear('2011');
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 1);

			// set month and year with strings
		$demand->setMonth('3');
		$demand->setYear('2011');
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 4);
	}

	/**
	 * Test if record by month/year constraint doesn't work with no datefield set
	 *
	 * @test
	 * @return void
	 * @expectedException InvalidArgumentException
	 */
	public function findRecordsByMonthAndYearWithNoDateField() {
		$pid = 92;
		$newsRepository = $this->objectManager->get('Tx_News2_Domain_Repository_NewsRepository');

		/** @var $demand Tx_News2_Domain_Model_NewsDemand */
		$demand = $this->objectManager->get('Tx_News2_Domain_Model_NewsDemand');
		$demand->setIsDummyRecord(1);
		$demand->setStoragePage($pid);
		$demand->setMonth(4);
		$demand->setYear(2011);
		$count = $newsRepository->findDemanded($demand)->count();
	}

	/**
	 * Tear down and remove records
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->testingFramework->cleanUp();
		unset($this->testingFramework);
	}
}
?>