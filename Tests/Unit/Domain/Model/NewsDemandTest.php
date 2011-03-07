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
	 * @test
	 */
	public function orderCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$order = 'Test order';
		$domainModelInstance->setOrder($order);
		$this->assertEquals($order, $domainModelInstance->getOrder());
	}

	/**
	 * @test
	 */
	public function orderRespectTopNewsCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$orderRespectTopNews = 'Test orderRespectTopNews';
		$domainModelInstance->setOrderRespectTopNews($orderRespectTopNews);
		$this->assertEquals($orderRespectTopNews, $domainModelInstance->getOrderRespectTopNews());
	}

	/**
	 * @test
	 */
	public function categoriesCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$categories = 'Test categories';
		$domainModelInstance->setCategories($categories);
		$this->assertEquals($categories, $domainModelInstance->getCategories());
	}

	/**
	 * @test
	 */
	public function categorySettingCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$categorySetting = 'Test categorySetting';
		$domainModelInstance->setCategorySetting($categorySetting);
		$this->assertEquals($categorySetting, $domainModelInstance->getCategorySetting());
	}

	/**
	 * @test
	 */
	public function archiveSettingCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$archiveSetting = 'Test archiveSetting';
		$domainModelInstance->setArchiveSetting($archiveSetting);
		$this->assertEquals($archiveSetting, $domainModelInstance->getArchiveSetting());
	}

	/**
	 * @test
	 */
	public function latestTimeLimitCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$latestTimeLimit = 'Test latestTimeLimit';
		$domainModelInstance->setLatestTimeLimit($latestTimeLimit);
		$this->assertEquals($latestTimeLimit, $domainModelInstance->getLatestTimeLimit());
	}

	/**
	 * @test
	 */
	public function searchFieldsCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$searchFields = 'Test searchFields';
		$domainModelInstance->setSearchFields($searchFields);
		$this->assertEquals($searchFields, $domainModelInstance->getSearchFields());
	}

	/**
	 * @test
	 */
	public function storagePageCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$storagePage = 'Test storagePage';
		$domainModelInstance->setStoragePage($storagePage);
		$this->assertEquals($storagePage, $domainModelInstance->getStoragePage());
	}

	/**
	 * @test
	 */
	public function topNewsSettingCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$topNewsSetting = 'Test topNewsSetting';
		$domainModelInstance->setTopNewsSetting($topNewsSetting);
		$this->assertEquals($topNewsSetting, $domainModelInstance->getTopNewsSetting());
	}

	/**
	 * @test
	 */
	public function limitCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$limit = 10;
		$domainModelInstance->setLimit($limit);
		$this->assertEquals($limit, $domainModelInstance->getLimit());
	}

	/**
	 * @test
	 */
	public function offsetCanBeSet() {
		$domainModelInstance = new Tx_News2_Domain_Model_NewsDemand();
		$offset = 10;
		$domainModelInstance->setOffset($offset);
		$this->assertEquals($offset, $domainModelInstance->getOffset());
	}

}
?>