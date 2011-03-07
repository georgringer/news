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
 * @subpackage tx_news2
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News2_Tests_Unit_Domain_Model_NewsDemandTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * Test if order can be set
	 *
	 * @test
	 * @return void
	 */
	public function orderCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$order = 'Test order';
		$domainModelInstance->setOrder($order);
		$this->assertEquals($order, $domainModelInstance->getOrder());
	}

	/**
	 * Test of top news can be set
	 *
	 * @test
	 * @return void
	 */
	public function orderRespectTopNewsCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$orderRespectTopNews = 'Test orderRespectTopNews';
		$domainModelInstance->setOrderRespectTopNews($orderRespectTopNews);
		$this->assertEquals($orderRespectTopNews, $domainModelInstance->getOrderRespectTopNews());
	}

	/**
	 * Test if categories can be set
	 *
	 * @test
	 * @return void
	 */
	public function categoriesCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$categories = 'Test categories';
		$domainModelInstance->setCategories($categories);
		$this->assertEquals($categories, $domainModelInstance->getCategories());
	}

	/**
	 * Test if categorysetting can be set
	 *
	 * @test
	 * @return void
	 */
	public function categorySettingCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$categorySetting = 'Test categorySetting';
		$domainModelInstance->setCategorySetting($categorySetting);
		$this->assertEquals($categorySetting, $domainModelInstance->getCategorySetting());
	}

	/**
	 * Test if archive can be set
	 *
	 * @test
	 * @return void
	 */
	public function archiveSettingCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$archiveSetting = 'Test archiveSetting';
		$domainModelInstance->setArchiveSetting($archiveSetting);
		$this->assertEquals($archiveSetting, $domainModelInstance->getArchiveSetting());
	}

	/**
	 * Test if timelimit can be set
	 *
	 * @test
	 * @return void
	 */
	public function latestTimeLimitCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$latestTimeLimit = 'Test latestTimeLimit';
		$domainModelInstance->setLatestTimeLimit($latestTimeLimit);
		$this->assertEquals($latestTimeLimit, $domainModelInstance->getLatestTimeLimit());
	}

	/**
	 * Test if searchfield can be set
	 *
	 * @test
	 * @return void
	 */
	public function searchFieldsCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$searchFields = 'Test searchFields';
		$domainModelInstance->setSearchFields($searchFields);
		$this->assertEquals($searchFields, $domainModelInstance->getSearchFields());
	}

	/**
	 * Test if storagepage can be set
	 *
	 * @test
	 * @return void
	 */
	public function storagePageCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$storagePage = 'Test storagePage';
		$domainModelInstance->setStoragePage($storagePage);
		$this->assertEquals($storagePage, $domainModelInstance->getStoragePage());
	}

	/**
	 * Test if topnews setting can be set
	 *
	 * @test
	 * @return void
	 */
	public function topNewsSettingCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$topNewsSetting = 'Test topNewsSetting';
		$domainModelInstance->setTopNewsSetting($topNewsSetting);
		$this->assertEquals($topNewsSetting, $domainModelInstance->getTopNewsSetting());
	}

	/**
	 * Test if limit can be set
	 *
	 * @test
	 * @return void
	 */
	public function limitCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$limit = 10;
		$domainModelInstance->setLimit($limit);
		$this->assertEquals($limit, $domainModelInstance->getLimit());
	}

	/**
	 * Test if offset can be set
	 *
	 * @test
	 * @return void
	 */
	public function offsetCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$offset = 10;
		$domainModelInstance->setOffset($offset);
		$this->assertEquals($offset, $domainModelInstance->getOffset());
	}

}
?>