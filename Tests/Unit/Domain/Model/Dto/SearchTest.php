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
 * Tests for domains model News
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News_Tests_Unit_Domain_Model_Dto_SearchTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if subject can be set
	 *
	 * @test
	 * @return void
	 */
	public function subjectCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_Search();
		$subject = 'Test 123';
		$domainModelInstance->setSubject($subject);
		$this->assertEquals($subject, $domainModelInstance->getSubject());
	}

	/**
	 * Test if fields can be set
	 *
	 * @test
	 * @return void
	 */
	public function fieldsCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_Search();
		$fields = 'field1,field2';
		$domainModelInstance->setFields($fields);
		$this->assertEquals($fields, $domainModelInstance->getFields());
	}

	/**
	 * Test if minimumDate can be set
	 *
	 * @test
	 * @return void
	 */
	public function minimumDateCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_Search();
		$value = '123';
		$domainModelInstance->setMinimumDate($value);
		$this->assertEquals($value, $domainModelInstance->getMinimumDate());
	}

	/**
	 * Test if minimumDate can be set
	 *
	 * @test
	 * @return void
	 */
	public function maximumDateCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_Search();
		$value = '456';
		$domainModelInstance->setMaximumDate($value);
		$this->assertEquals($value, $domainModelInstance->getMaximumDate());
	}

}
