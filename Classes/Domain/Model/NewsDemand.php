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

class Tx_News2_Domain_Model_NewsDemand extends Tx_Extbase_DomainObject_AbstractEntity implements Tx_News2_Domain_Model_DemandInterface {

	protected $order;
	protected $orderRespectTopNews;

	protected $categories;
	protected $categorySetting;

	protected $archiveSetting;
	protected $latestTimeLimit = NULL;

	protected $searchFields;
	protected $storagePage;

	protected $topNewsSetting;

	protected $dateField;
	protected $month;
	protected $year;

	protected $limit;
	protected $offset;

	protected $search;

	public function setArchiveSetting($archiveSetting) {
		$this->archiveSetting = $archiveSetting;
	}

	public function getArchiveSetting() {
		return $this->archiveSetting;
	}

	public function setCategories($categories) {
		$this->categories = $categories;
	}

	public function getCategories() {
		return $this->categories;
	}

	public function setCategorySetting($categorySetting) {
		$this->categorySetting = $categorySetting;
	}

	public function getCategorySetting() {
		return $this->categorySetting;
	}

	public function setLatestTimeLimit($latestTimeLimit) {
		$this->latestTimeLimit = $latestTimeLimit;
	}

	public function getLatestTimeLimit() {
		return $this->latestTimeLimit;
	}

	public function setOrder($order) {
		$this->order = $order;
	}

	public function getOrder() {
		return $this->order;
	}

	public function setOrderRespectTopNews($orderRespectTopNews) {
		$this->orderRespectTopNews = $orderRespectTopNews;
	}

	public function getOrderRespectTopNews() {
		return $this->orderRespectTopNews;
	}

	public function setSearchFields($searchFields) {
		$this->searchFields = $searchFields;
	}

	public function getSearchFields() {
		return $this->searchFields;
	}

	public function setTopNewsSetting($topNewsSetting) {
		$this->topNewsSetting = $topNewsSetting;
	}

	public function getTopNewsSetting() {
		return $this->topNewsSetting;
	}

	public function setStoragePage($storagePage) {
		$this->storagePage = $storagePage;
	}

	public function getStoragePage() {
		return $this->storagePage;
	}

	public function getMonth() {
		return $this->month;
	}

	public function setMonth($month) {
		$this->month = $month;
	}

	public function getYear() {
		return $this->year;
	}

	public function setYear($year) {
		$this->year = $year;
	}

	public function setLimit($limit) {
		$this->limit = (int)$limit;
	}

	public function getLimit() {
		return $this->limit;
	}

	public function setOffset($offset) {
		$this->offset = (int)$offset;
	}

	public function getOffset() {
		return $this->offset;
	}

	public function setDateField($dateField) {
		$this->dateField = $dateField;
	}

	public function getDateField() {
		return $this->dateField;
	}

	public function getSearch() {
		return $this->search;
	}

	public function setSearch($search) {
		$this->search = $search;
	}


}

?>
