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
 * Administration Demand model
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Domain_Model_Dto_AdministrationDemand extends Tx_News_Domain_Model_Dto_NewsDemand {

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

	/**
	 * @param $recursive
	 * @return void
	 */
	public function setRecursive($recursive) {
		$this->recursive = $recursive;
	}

	/**
	 * @return array
	 */
	public function getSelectedCategories() {
		return $this->selectedCategories;
	}

	/**
	 * @param $selectedCategories
	 * @return void
	 */
	public function setSelectedCategories($selectedCategories) {
		$this->selectedCategories = $selectedCategories;
	}

	/**
	 * @return string
	 */
	public function getSortingField() {
		return $this->sortingField;
	}

	/**
	 * @param $sortingField
	 * @return void
	 */
	public function setSortingField($sortingField) {
		$this->sortingField = $sortingField;
	}

	/**
	 * @return string
	 */
	public function getSortingDirection() {
		return $this->sortingDirection;
	}

	/**
	 * @param $sortingDirection
	 * @return void
	 */
	public function setSortingDirection($sortingDirection) {
		$this->sortingDirection = $sortingDirection;
	}
}
