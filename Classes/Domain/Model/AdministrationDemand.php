<?php

class Tx_News_Domain_Model_AdministrationDemand extends Tx_News_Domain_Model_NewsDemand {

	/**
	 * @var string
	 */
	protected $recursive;

	/**
	 * @var array
	 */
	protected $selectedCategories = array();

	/**
	 * @var string
	 */
	protected $sortingField;

	/**
	 * @var string
	 */
	protected $sortingDirection;

	public function getRecursive() {
		return $this->recursive;
	}

	public function setRecursive($recursive) {
		$this->recursive = $recursive;
	}

	public function getSelectedCategories() {
		return $this->selectedCategories;
	}

	public function setSelectedCategories($selectedCategories) {
		$this->selectedCategories = $selectedCategories;
	}

	public function getSortingField() {
		return $this->sortingField;
	}

	public function setSortingField($sortingField) {
		$this->sortingField = $sortingField;
	}

	public function getSortingDirection() {
		return $this->sortingDirection;
	}

	public function setSortingDirection($sortingDirection) {
		$this->sortingDirection = $sortingDirection;
	}
}

?>