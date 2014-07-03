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
