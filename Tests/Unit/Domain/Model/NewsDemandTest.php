<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
class Tx_News_Tests_Unit_Domain_Model_NewsDemandTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * Test if order can be set
	 *
	 * @test
	 * @return void
	 */
	public function orderCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
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
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$orderRespectTopNews = 'Test topNewsFirst';
		$domainModelInstance->setTopNewsFirst($orderRespectTopNews);
		$this->assertEquals($orderRespectTopNews, $domainModelInstance->getTopNewsFirst());
	}

	/**
	 * Test if categories can be set
	 *
	 * @test
	 * @return void
	 */
	public function categoriesCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
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
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$categorySetting = 'Test categoryConjunction';
		$domainModelInstance->setCategoryConjunction($categorySetting);
		$this->assertEquals($categorySetting, $domainModelInstance->getCategoryConjunction());
	}

	/**
	 * Test if includeSubCategories can be set
	 *
	 * @test
	 * @return void
	 */
	public function includeSubCategoriesCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$includeSubCategories = TRUE;
		$domainModelInstance->setIncludeSubCategories($includeSubCategories);
		$this->assertEquals($includeSubCategories, $domainModelInstance->getIncludeSubCategories());
	}

	/**
	 * Test if tags can be set
	 *
	 * @test
	 * @return void
	 */
	public function tagsCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$tag1 = new Tx_News_Domain_Model_Tag();
		$tag1->setTitle('Tag 1');
		$tag2 = new Tx_News_Domain_Model_Tag();
		$tag2->setTitle('Tag 2');
		$tags = new Tx_Extbase_Persistence_ObjectStorage();
		$tags->attach($tag1);
		$tags->attach($tag2);
		$domainModelInstance->setTags($tags);
		$this->assertEquals($tags, $domainModelInstance->getTags());
	}

	/**
	 * Test if archive can be set
	 *
	 * @test
	 * @return void
	 */
	public function archiveSettingCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$archiveSetting = 'Test archiveRestriction';
		$domainModelInstance->setArchiveRestriction($archiveSetting);
		$this->assertEquals($archiveSetting, $domainModelInstance->getArchiveRestriction());
	}

	/**
	 * Test if datefield can be set
	 *
	 * @test
	 * @return void
	 */
	public function dateFieldCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$dateField = 'datetime';
		$domainModelInstance->setDateField($dateField);
		$this->assertEquals($dateField, $domainModelInstance->getDateField());
	}

	/**
	 * Test if year can be set
	 *
	 * @test
	 * @return void
	 */
	public function yearCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$year = '2011';
		$domainModelInstance->setYear($year);
		$this->assertEquals($year, $domainModelInstance->getYear());
	}

	/**
	 * Test if month can be set
	 *
	 * @test
	 * @return void
	 */
	public function monthCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$month = '12';
		$domainModelInstance->setMonth($month);
		$this->assertEquals($month, $domainModelInstance->getMonth());
	}

	/**
	 * Test if timelimit can be set
	 *
	 * @test
	 * @return void
	 */
	public function latestTimeLimitCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$latestTimeLimit = 'Test timeRestriction';
		$domainModelInstance->setTimeRestriction($latestTimeLimit);
		$this->assertEquals($latestTimeLimit, $domainModelInstance->getTimeRestriction());
	}

	/**
	 * Test if search can be set
	 *
	 * @test
	 * @return void
	 */
	public function searchCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$searchInstance = new Tx_News_Domain_Model_Dto_Search();
		$searchInstance->setSubject('Fo');

		$domainModelInstance->setSearch($searchInstance);
		$this->assertEquals($searchInstance, $domainModelInstance->getSearch());
	}

	/**
	 * Test if searchfield can be set
	 *
	 * @test
	 * @return void
	 */
	public function searchFieldsCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
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
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
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
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$topNewsSetting = 'Test topNewsRestriction';
		$domainModelInstance->setTopNewsRestriction($topNewsSetting);
		$this->assertEquals($topNewsSetting, $domainModelInstance->getTopNewsRestriction());
	}

	/**
	 * Test if limit can be set
	 *
	 * @test
	 * @return void
	 */
	public function limitCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
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
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$offset = 10;
		$domainModelInstance->setOffset($offset);
		$this->assertEquals($offset, $domainModelInstance->getOffset());
	}

	/**
	 * Test if excludeAlreadyDisplayedNews setting can be set
	 *
	 * @test
	 * @return void
	 */
	public function excludeAlreadyDisplayedNewsSettingCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$setting = TRUE;
		$domainModelInstance->setExcludeAlreadyDisplayedNews($setting);
		$this->assertEquals($setting, $domainModelInstance->getExcludeAlreadyDisplayedNews());
	}

	/**
	 * Test if isDummyRecord can be set
	 *
	 * @test
	 * @return void
	 */
	public function isDummyRecordCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_Dto_NewsDemand();
		$isDummyRecord = TRUE;
		$domainModelInstance->setIsDummyRecord($isDummyRecord);
		$this->assertEquals($isDummyRecord, $domainModelInstance->getIsDummyRecord());
	}

}
?>