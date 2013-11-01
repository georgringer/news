<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Georg Ringer <typo3@ringerge.org>
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
