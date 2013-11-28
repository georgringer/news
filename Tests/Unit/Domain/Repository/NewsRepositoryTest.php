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
 * @subpackage tx_news
 *
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 * @author Georg Ringer <mail@ringerge.org>
 */
class Tx_News_Tests_Unit_Domain_Repository_NewsRepositoryTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * dataProvider for createCategoryConstraint_creates_correct_junctions
	 *
	 * @return array
	 */
	public function conjunctionNameProvider() {
		return array(
			'or' => array('or', Tx_Extbase_Persistence_QOM_LogicalOr, NULL),
			'notor' => array('notor', Tx_Extbase_Persistence_QOM_LogicalNot, Tx_Extbase_Persistence_QOM_LogicalOr),
			'notand' => array('notand', Tx_Extbase_Persistence_QOM_LogicalNot, Tx_Extbase_Persistence_QOM_LogicalAnd),
			'and' => array('and', Tx_Extbase_Persistence_QOM_LogicalAnd , NULL),
			'anything' => array('and', Tx_Extbase_Persistence_QOM_LogicalAnd , NULL),
			'' => array('and', Tx_Extbase_Persistence_QOM_LogicalAnd , NULL));
	}

	/**
	 * @test
	 * @dataProvider conjunctionNameProvider
	 * @return void
	 */
	public function createCategoryConstraintCreatesCorrectJunctions($junctionName, $outerConstraintType, $innerConstraintType) {
		if (version_compare(TYPO3_branch, '6.2', '>=')) {
			$this->markTestSkipped('Skipped for 6.2');
			return;
		}

		$newsRepository = $this->getAccessibleMock('Tx_News_Domain_Repository_NewsRepository', array('dummy'));
		$query = $newsRepository->createQuery();

		$resultQuery = $newsRepository->_call('createCategoryConstraint', $query, array(1, 2), $junctionName);

		$this->assertInstanceOf($outerConstraintType, $resultQuery);

		if ($innerConstraintType !== NULL) {
			$this->assertInstanceOf($innerConstraintType, $resultQuery->getConstraint());
		}
	}

	/**
	 * @test
	 * @return void
	 */
	public function findDemandedCallsCreateConstraintsFromDemand_once() {
		if (version_compare(TYPO3_branch, '6.2', '>=')) {
			$this->markTestSkipped('Skipped for 6.2');
			return;
		}
		$mockDemand = $this->getMock('Tx_News_Domain_Model_Dto_NewsDemand');
		if (version_compare(TYPO3_branch, '6.2', '>=')) {
			$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface');
			$newsRepository = $this->getAccessibleMock(
				'Tx_News_Domain_Repository_NewsRepository', array('createConstraintsFromDemand'), array($objectManager)
			);
		} else {
			$newsRepository = $this->getAccessibleMock(
				'Tx_News_Domain_Repository_NewsRepository', array('createConstraintsFromDemand')
			);
		}
		$newsRepository->expects($this->once())->method('createConstraintsFromDemand');

		$newsRepository->findDemanded($mockDemand);
	}

	/**
	 * @test
	 * @return void
	 */
	public function findDemandedCallsCreateOrderingsFromDemand_once() {
		if (version_compare(TYPO3_branch, '6.2', '>=')) {
			$this->markTestSkipped('Skipped for 6.2');
			return;
		}
		$mockDemand = $this->getMock('Tx_News_Domain_Model_Dto_NewsDemand');
		if (version_compare(TYPO3_branch, '6.2', '>=')) {
			$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface');
			$newsRepository = $this->getAccessibleMock(
				'Tx_News_Domain_Repository_NewsRepository', array('createOrderingsFromDemand'), array($objectManager)
			);
		} else {
			$newsRepository = $this->getAccessibleMock(
				'Tx_News_Domain_Repository_NewsRepository', array('createOrderingsFromDemand')
			);
		}
		$newsRepository->expects($this->once())->method('createOrderingsFromDemand');
		$newsRepository->findDemanded($mockDemand);
	}

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

		$query = $this->getMock('Tx_Extbase_Persistence_QueryInterface');
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
}
