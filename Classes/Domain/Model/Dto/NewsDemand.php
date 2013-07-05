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
 * News Demand object which holds all information to get the correct
 * news records.
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Domain_Model_Dto_NewsDemand
	extends Tx_Extbase_DomainObject_AbstractEntity implements Tx_News_Domain_Model_DemandInterface {

	/**
	 * @var string
	 */
	protected $categories;

	/**
	 * @var string
	 */
	protected $categoryConjunction;

	/**
	 * @var boolean
	 */
	protected $includeSubCategories = FALSE;

	/** @var Tx_Extbase_Persistence_ObjectStorage */
	protected $tags;

	/**
	 * @var string
	 */
	protected $archiveRestriction;

	/**
	 * @var string
	 */
	protected $timeRestriction = NULL;

	/** @var string */
	protected $timeRestrictionHigh = NULL;

	/** @var boolean */
	protected $topNewsRestriction;

	/** @var string */
	protected $dateField;

	/** @var integer */
	protected $month;

	/** @var integer */
	protected $year;

	/** @var integer */
	protected $day;

	/** @var string */
	protected $searchFields;

	/** @var Tx_News_Domain_Model_Dto_Search */
	protected $search;

	/** @var string */
	protected $order;

	/** @var string */
	protected $orderByAllowed;

	/** @var boolean */
	protected $topNewsFirst;

	/** @var integer */
	protected $storagePage;

	/** @var integer */
	protected $limit;

	/** @var integer */
	protected $offset;

	/** @var boolean */
	protected $excludeAlreadyDisplayedNews;

	/** @var boolean */
	protected $isDummyRecord;

	/**
	 * Set archive settings
	 *
	 * @param string $archiveRestriction archive setting
	 * @return void
	 */
	public function setArchiveRestriction($archiveRestriction) {
		$this->archiveRestriction = $archiveRestriction;
	}

	/**
	 * Get archive setting
	 *
	 * @return string
	 */
	public function getArchiveRestriction() {
		return $this->archiveRestriction;
	}

	/**
	 * List of allowed categories
	 *
	 * @param string $categories categories
	 * @return void
	 */
	public function setCategories($categories) {
		$this->categories = $categories;
	}

	/**
	 * Get allowed categories
	 *
	 * @return string
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * Set category mode
	 *
	 * @param string $categoryConjunction
	 * @return void
	 */
	public function setCategoryConjunction($categoryConjunction) {
		$this->categoryConjunction = $categoryConjunction;
	}

	/**
	 * Get category mode
	 *
	 * @return string
	 */
	public function getCategoryConjunction() {
		return $this->categoryConjunction;
	}

	/**
	 * Get include sub categories
	 * @return boolean
	 */
	public function getIncludeSubCategories() {
		return (boolean)$this->includeSubCategories;
	}

	/**
	 * @param boolean $includeSubCategories
	 * @return void
	 */
	public function setIncludeSubCategories($includeSubCategories) {
		$this->includeSubCategories = $includeSubCategories;
	}


	/**
	 * Get Tags
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * Set Tags
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage $tags tags
	 * @return void
	 */
	public function setTags($tags) {
		$this->tags = $tags;
	}


	/**
	 * Set time limit low, either integer or string
	 *
	 * @param mixed $timeRestriction
	 * @return void
	 */
	public function setTimeRestriction($timeRestriction) {
		$this->timeRestriction = $timeRestriction;
	}

	/**
	 * Get time limit low
	 *
	 * @return mixed
	 */
	public function getTimeRestriction() {
		return $this->timeRestriction;
	}

	/**
	 * Get time limit high
	 *
	 * @return mixed
	 */
	public function getTimeRestrictionHigh() {
		return $this->timeRestrictionHigh;
	}

	/**
	 * Set time limit high
	 *
	 * @param mixed $timeRestrictionHigh
	 * @return void
	 */
	public function setTimeRestrictionHigh($timeRestrictionHigh) {
		$this->timeRestrictionHigh = $timeRestrictionHigh;
	}


	/**
	 * Set order
	 *
	 * @param string $order order
	 * @return void
	 */
	public function setOrder($order) {
		$this->order = $order;
	}

	/**
	 * Get order
	 *
	 * @return string
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 * Set order allowed
	 *
	 * @param string $orderByAllowed allowed fields for ordering
	 * @return void
	 */
	public function setOrderByAllowed($orderByAllowed) {
		$this->orderByAllowed = $orderByAllowed;
	}

	/**
	 * Get allowed order fields
	 *
	 * @return string
	 */
	public function getOrderByAllowed() {
		return $this->orderByAllowed;
	}

	/**
	 * Set order respect top news flag
	 *
	 * @param integer $topNewsFirst respect top news flag
	 * @return void
	 */
	public function setTopNewsFirst($topNewsFirst) {
		$this->topNewsFirst = $topNewsFirst;
	}

	/**
	 * Get order respect top news flag
	 *
	 * @return integer
	 */
	public function getTopNewsFirst() {
		return $this->topNewsFirst;
	}

	/**
	 * Set search fields
	 *
	 * @param string $searchFields search fields
	 * @return void
	 */
	public function setSearchFields($searchFields) {
		$this->searchFields = $searchFields;
	}

	/**
	 * Get search fields
	 *
	 * @return string
	 */
	public function getSearchFields() {
		return $this->searchFields;
	}

	/**
	 * Set top news setting
	 *
	 * @param string $topNewsFirst top news settings
	 * @return void
	 */
	public function setTopNewsRestriction($topNewsFirst) {
		$this->topNewsRestriction = $topNewsFirst;
	}

	/**
	 * Get top news setting
	 *
	 * @return string
	 */
	public function getTopNewsRestriction() {
		return $this->topNewsRestriction;
	}

	/**
	 * Set list of storage pages
	 *
	 * @param string $storagePage storage page list
	 * @return void
	 */
	public function setStoragePage($storagePage) {
		$this->storagePage = $storagePage;
	}

	/**
	 * Get list of storage pages
	 *
	 * @return string
	 */
	public function getStoragePage() {
		return $this->storagePage;
	}

	/**
	 * Get day restriction
	 *
	 * @return integer
	 */
	public function getDay() {
		return $this->day;
	}

	/**
	 * Set day restriction
	 *
	 * @param integer $day
	 * @return void
	 */
	public function setDay($day) {
		$this->day = $day;
	}

	/**
	 * Get month restriction
	 *
	 * @return integer
	 */
	public function getMonth() {
		return $this->month;
	}

	/**
	 * Set month restriction
	 *
	 * @param integer $month month
	 * @return void
	 */
	public function setMonth($month) {
		$this->month = $month;
	}

	/**
	 * Get year restriction
	 *
	 * @return integer
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 * Set year restriction
	 *
	 * @param integer $year year
	 * @return void
	 */
	public function setYear($year) {
		$this->year = $year;
	}

	/**
	 * Set limit
	 *
	 * @param integer $limit limit
	 * @return void
	 */
	public function setLimit($limit) {
		$this->limit = (int)$limit;
	}

	/**
	 * Get limit
	 *
	 * @return integer
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * Set offset
	 *
	 * @param integer $offset offset
	 * @return void
	 */
	public function setOffset($offset) {
		$this->offset = (int)$offset;
	}

	/**
	 * Get offset
	 *
	 * @return integer
	 */
	public function getOffset() {
		return $this->offset;
	}

	/**
	 * Set date field which is used for datemenu
	 *
	 * @param string $dateField datefield
	 * @return void
	 */
	public function setDateField($dateField) {
		$this->dateField = $dateField;
	}

	/**
	 * Get datefield which is used for datemenu
	 *
	 * @return string
	 */
	public function getDateField() {
		return $this->dateField;
	}

	/**
	 * Get search object
	 *
	 * @return Tx_News_Domain_Model_Dto_Search
	 */
	public function getSearch() {
		return $this->search;
	}

	/**
	 * Set search object
	 *
	 * @param Tx_News_Domain_Model_Dto_Search $search search object
	 * @return void
	 */
	public function setSearch($search) {
		$this->search = $search;
	}

	/**
	 * Set flag if displayed news records should be excluded
	 *
	 * @param boolean $excludeAlreadyDisplayedNews
	 * @return void
	 */
	public function setExcludeAlreadyDisplayedNews($excludeAlreadyDisplayedNews) {
		$this->excludeAlreadyDisplayedNews = (bool)$excludeAlreadyDisplayedNews;
	}

	/**
	 * Get flag if displayed news records should be excluded
	 *
	 * @return boolean
	 */
	public function getExcludeAlreadyDisplayedNews() {
		return $this->excludeAlreadyDisplayedNews;
	}

	/**
	 * Get dummy record flag, used for unit tests
	 *
	 * @return integer
	 */
	public function getIsDummyRecord() {
		return $this->isDummyRecord;
	}

	/**
	 * Set dummy record flag, used for unit tests
	 *
	 * @param integer $isDummyRecord dummy record flag
	 * @return void
	 */
	public function setIsDummyRecord($isDummyRecord) {
		$this->isDummyRecord = $isDummyRecord;
	}

}

?>