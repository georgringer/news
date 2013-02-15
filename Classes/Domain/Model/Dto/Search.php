<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2011 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * News Demand object which holds all information to get the correct
 * news records.
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Domain_Model_Dto_Search extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * Basic search word
	 *
	 * @var string
	 */
	protected $subject;

	/**
	 * Search fields
	 *
	 * @var string
	 */
	protected $fields;

	/**
	 * Get the subject
	 *
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Set subject
	 *
	 * @param string $subject
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Get fields
	 *
	 * @return string
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * Set fields
	 *
	 * @param $fields
	 * @return void
	 */
	public function setFields($fields) {
		$this->fields = $fields;
	}

}

?>