<?php

namespace GeorgRinger\News\Tests\Unit\Functional\Repository;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Georg Ringer <typo3@ringerge.org>
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Functional test for the Tx_News_Domain_Repository_NewsRepository
 */
class NewsRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase {

	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/** @var  Tx_News_Domain_Repository_NewsRepository */
	protected $newsRepository;

	protected $testExtensionsToLoad = array('typo3conf/ext/news');

	public function setUp() {
		parent::setUp();
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->newsRepository = $this->objectManager->get('Tx_News_Domain_Repository_NewsRepository');

		$this->importDataSet(__DIR__ . '/../Fixtures/tx_news_domain_model_news.xml');
	}

	/**
	 * Test if startingpoint is working
	 *
	 * @test
	 * @return void
	 */
	public function findRecordsByUid() {
		$news = $this->newsRepository->findByUid(1);

		$this->assertEquals($news->getTitle(), 'findRecordsByUid');
	}

	/**
	 * Test if by import source is done
	 *
	 * @test
	 * @return void
	 */
	public function findRecordsByImportSource() {
		$news = $this->newsRepository->findOneByImportSourceAndImportId('functional_test', '2');

		$this->assertEquals($news->getTitle(), 'findRecordsByImportSource');
	}


	/**
	 * Test if top news constraint works
	 *
	 * @test
	 * @return void
	 */
	public function findTopNewsRecords() {
		/** @var $demand Tx_News_Domain_Model_Dto_NewsDemand */
		$demand = $this->objectManager->get('Tx_News_Domain_Model_Dto_NewsDemand');
		$demand->setStoragePage(2);

		// no matter about top news
		$demand->setTopNewsRestriction(0);
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 5);

		// Only Top news
		$demand->setTopNewsRestriction(1);
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 3);

		// Only non Top news
		$demand->setTopNewsRestriction(2);
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 2);
	}

	/**
	 * Test if startingpoint is working
	 *
	 * @test
	 * @return void
	 */
	public function findRecordsByStartingpointRestriction() {
		/** @var $demand Tx_News_Domain_Model_Dto_NewsDemand */
		$demand = $this->objectManager->get('Tx_News_Domain_Model_Dto_NewsDemand');

		// simple starting point
		$demand->setStoragePage(3);
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 2);

		// multiple starting points
		$demand->setStoragePage('4,5');
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 5);

		// multiple starting points, including invalid values and commas
		$demand->setStoragePage('5 ,  x ,3');
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 3);
	}


	/**
	 * Test if record are found by archived/non archived flag
	 *
	 * @test
	 * @return void
	 */
	public function findRecordsByArchiveRestriction() {
		$newsRepository = $this->objectManager->get('Tx_News_Domain_Repository_NewsRepository');

		/** @var $demand Tx_News_Domain_Model_Dto_NewsDemand */
		$demand = $this->objectManager->get('Tx_News_Domain_Model_Dto_NewsDemand');
		$demand->setStoragePage(7);

		// Archived means: archive date must be lower than current time AND != 0
		$demand->setArchiveRestriction('archived');
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 3);

		// Active means: archive date must be in future or no archive date
		$demand->setArchiveRestriction('active');
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 2);

		// no value means: give all
		$demand->setArchiveRestriction('');
		$this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 5);
	}


	/**
	 * Test if record by month/year constraint works
	 *
	 * @test
	 * @return void
	 */
	public function findRecordsByMonthAndYear() {
		/** @var $demand Tx_News_Domain_Model_Dto_NewsDemand */
		$demand = $this->objectManager->get('Tx_News_Domain_Model_Dto_NewsDemand');
		$demand->setStoragePage(8);

		// set month and year with integers
		$demand->setDateField('datetime');
		$demand->setMonth(4);
		$demand->setYear(2011);
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 1);

		$demand->setMonth('4');
		$demand->setYear('2011');
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 1);

		// set month and year with strings
		$demand->setMonth('3');
		$demand->setYear('2011');
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 4);
	}


	/**
	 * Test if record by month/year constraint does not work with no datefield set
	 *
	 * @test
	 * @return void
	 * @expectedException InvalidArgumentException
	 */
	public function findRecordsByMonthAndYearWithNoDateField() {
		/** @var $demand Tx_News_Domain_Model_Dto_NewsDemand */
		$demand = $this->objectManager->get('Tx_News_Domain_Model_Dto_NewsDemand');
		$demand->setStoragePage(92);
		$demand->setMonth(4);
		$demand->setYear(2011);
		$this->newsRepository->findDemanded($demand)->count();
	}

	/**
	 * Test if latest limit constraint works
	 *
	 * @test
	 * @return void
	 */
	public function findLatestLimitRecords() {
		/** @var $demand Tx_News_Domain_Model_Dto_NewsDemand */
		$demand = $this->objectManager->get('Tx_News_Domain_Model_Dto_NewsDemand');
		$demand->setStoragePage(9);

		// maximum 8 days old
		$demand->setTimeRestriction('-8 days');
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 5);

		// get all news maximum 6 days old
		$demand->setTimeRestriction((6 * 86400));
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 4);

		// no restriction should get you all
		$demand->setTimeRestriction(0);
		$this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 6);
	}

}