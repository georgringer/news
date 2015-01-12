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
 * Tests for domain repository newsRepository
 *
 * @package TYPO3
 * @subpackage tx_news
 *
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 * @author Georg Ringer <mail@ringerge.org>
 */
class Tx_News_Tests_Unit_Domain_Repository_NewsRepositoryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test create constraints from demand including topnews setting
	 *
	 * @test
	 * @return void
	 */
	public function createConstraintsFromDemandForDemandWithTopnewsSetting1QueriesForIsTopNews() {
		$demand = $this->getMock('Tx_News_Domain_Model_Dto_NewsDemand');
		$demand->expects($this->atLeastOnce())->method('getTopNewsRestriction')
			->will($this->returnValue(1));

		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\QueryInterface');
		$query->expects($this->once())->method('equals')->with('istopnews', 1);

		if (version_compare(TYPO3_branch, '6.2', '>=')) {
			$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface');
			$newsRepository = $this->getAccessibleMock(
				'Tx_News_Domain_Repository_NewsRepository', array('dummy'), array($objectManager)
			);
		} else {
			$newsRepository = $this->getAccessibleMock(
				'Tx_News_Domain_Repository_NewsRepository', array('dummy')
			);
		}
		$newsRepository->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @expectedException UnexpectedValueException
	 */
	public function getSearchConstraintsThrowsErrorIfNoSearchFieldIsGiven() {
		$mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
		$mockedRepository = $this->getAccessibleMock('Tx_News_Domain_Repository_NewsRepository', array('dummy'), array(), '', FALSE);

		$search = new Tx_News_Domain_Model_Dto_Search();
		$search->setSubject('fo');

		$demand = new Tx_News_Domain_Model_Dto_NewsDemand();
		$demand->setSearch($search);

		$mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
	}
//
	/**
	 * @test
	 * @expectedException UnexpectedValueException
	 */
	public function getSearchConstraintsThrowsErrorIfNoDateFieldForMaximumDateIsGiven() {
		$mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
		$mockedRepository = $this->getAccessibleMock('Tx_News_Domain_Repository_NewsRepository', array('dummy'), array(), '', FALSE);

		$search = new Tx_News_Domain_Model_Dto_Search();
		$search->setMaximumDate('2014-04-01');

		$demand = new Tx_News_Domain_Model_Dto_NewsDemand();
		$demand->setSearch($search);

		$mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
	}
//
	/**
	 * @test
	 * @expectedException UnexpectedValueException
	 */
	public function getSearchConstraintsThrowsErrorIfNoDateFieldForMinimumDateIsGiven() {
		$mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
		$mockedRepository = $this->getAccessibleMock('Tx_News_Domain_Repository_NewsRepository', array('dummy'), array(), '', FALSE);

		$search = new Tx_News_Domain_Model_Dto_Search();
		$search->setMinimumDate('2014-04-01');

		$demand = new Tx_News_Domain_Model_Dto_NewsDemand();
		$demand->setSearch($search);

		$mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
	}
//
	/**
	 * @test
	 */
	public function emptyConstraintIsReturnedForEmptySearchDemand() {
		$mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
		$mockedRepository = $this->getAccessibleMock('Tx_News_Domain_Repository_NewsRepository', array('dummy'), array(), '', FALSE);

		$demand = new Tx_News_Domain_Model_Dto_NewsDemand();
		$demand->setSearch(NULL);
		$result = $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
		$this->assertEmpty($result);
	}

	/**
	 * @test
	 */
	public function constraintsAreReturnedForSearchSubject() {
		$mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
		$mockedRepository = $this->getAccessibleMock('Tx_News_Domain_Repository_NewsRepository', array('dummy'), array(), '', FALSE);

		$search = new Tx_News_Domain_Model_Dto_Search();
		$search->setSubject('Lorem');
		$search->setFields('title,fo');

		$demand = new Tx_News_Domain_Model_Dto_NewsDemand();
		$demand->setSearch($search);

		$result = $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
		$this->assertEquals(1, count($result));
	}

	/**
	 * @test
	 */
	public function constraintsAreReturnedForDateFields() {
		$mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
		$mockedRepository = $this->getAccessibleMock('Tx_News_Domain_Repository_NewsRepository', array('dummy'), array(), '', FALSE);

		$search = new Tx_News_Domain_Model_Dto_Search();
		$search->setMinimumDate('2014-01-01');
		$search->setDateField('datetime');

		$demand = new Tx_News_Domain_Model_Dto_NewsDemand();
		$demand->setSearch($search);

		$result = $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
		$this->assertEquals(1, count($result));

		$search->setMaximumDate('2015-01-01');
		$demand->setSearch($search);

		$result = $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
		$this->assertEquals(2, count($result));

		$search->setMaximumDate('xyz');
		$demand->setSearch($search);

		$result = $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
		$this->assertEquals(1, count($result));
	}
}
