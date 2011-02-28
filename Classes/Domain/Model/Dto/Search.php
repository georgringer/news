<?php

class Tx_News2_Domain_Model_Dto_Search {

	/**
	 * @var string
	 */
	protected $searchString;

	/**
	 * @var string
	 */
	protected $fromDate;

	/**
	 * @var string
	 */
	protected $toDate;

	public function getSearchString() {
		return $this->searchString;
	}

	public function setSearchString($searchString) {
		$this->searchString = $searchString;
	}

	public function getFromDate() {
		return $this->fromDate;
	}

	public function setFromDate($fromDate) {
		$this->fromDate = $fromDate;
	}

	public function getToDate() {
		return $this->toDate;
	}

	public function setToDate($toDate) {
		$this->toDate = $toDate;
	}



}