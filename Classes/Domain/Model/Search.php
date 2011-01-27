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
 * Search model
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Domain_Model_Search extends Tx_Extbase_DomainObject_AbstractEntity {
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
	
	/**
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_Category>
	 */
	protected $category;

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

	public function getCategory() {
		return $this->category;
	}

	public function setCategory($category) {
		$this->category = $category;
	}



}

?>