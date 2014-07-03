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
 * Tests for Tx_News_Domain_Model_Dto_AdministrationDemand
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Domain_Model_Dto_AdministrationDemandTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/** @var  Tx_News_Domain_Model_Dto_AdministrationDemand */
	protected $instance;

	public function setup() {
		$this->instance = new Tx_News_Domain_Model_Dto_AdministrationDemand();
	}

	/**
	 * Test if recursive can be set
	 *
	 * @test
	 * @return void
	 */
	public function recursiveCanBeSet() {
		$value = 'Test 123';
		$this->instance->setRecursive($value);
		$this->assertEquals($value, $this->instance->getRecursive());
	}

	/**
	 * Test if selectedCategories can be set
	 *
	 * @test
	 * @return void
	 */
	public function selectedCategoriesCanBeSet() {
		$value = array('Test 123');
		$this->instance->setCategories($value);
		$this->assertEquals($value, $this->instance->getCategories());
	}

	/**
	 * Test if sortingField can be set
	 *
	 * @test
	 * @return void
	 */
	public function sortingFieldCanBeSet() {
		$value = 'title';
		$this->instance->setSortingField($value);
		$this->assertEquals($value, $this->instance->getSortingField());
	}

	/**
	 * Test if sortingDirection can be set
	 *
	 * @test
	 * @return void
	 */
	public function sortingDirectionCanBeSet() {
		$value = 'asc';
		$this->instance->setSortingDirection($value);
		$this->assertEquals($value, $this->instance->getSortingDirection());
	}

}
