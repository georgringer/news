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
 * Media model
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class Tx_News2_Domain_Model_Media extends Tx_Extbase_DomainObject_AbstractEntity {
	/**
	 * @var DateTime
	 */
	protected $crdate;

	/**
	 * @var DateTime
	 */
	protected $tstamp;

	/**
	 * @var boolean
	 */
	protected $hidden;

	/**
	 * @var boolean
	 */
	protected $deleted;

	/**
	 * @var integer
	 */
	protected $cruserId;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $caption;

	/**
	 * @var string
	 */
	protected $alt;

	/**
	 * @var integer
	 */
	protected $type;

	/**
	 * @var boolean;
	 */
	protected $showinpreview;

	/**
	 * @var integer;
	 */
	protected $width;

	/**
	 * @var integer;
	 */
	protected $height;

	/**
	 * @var integer;
	 */
	protected $sorting;

	/**
	 * @var string
	 */
	protected $content;


	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getCaption() {
		return $this->caption;
	}

	public function setCaption($caption) {
		$this->caption = $caption;
	}

	public function getAlt() {
		return $this->alt;
	}

	public function setAlt($alt) {
		$this->alt = $alt;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getShowinpreview() {
		return $this->showinpreview;
	}

	public function setShowinpreview($showinpreview) {
		$this->showinpreview = $showinpreview;
	}

	public function getCrdate() {
		return $this->crdate;
	}

	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	public function getTstamp() {
		return $this->tstamp;
	}

	public function setTstamp($tstamp) {
		$this->tstamp = $tstamp;
	}

	public function getHidden() {
		return $this->hidden;
	}

	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}

	public function getDeleted() {
		return $this->deleted;
	}

	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}

	public function getCruserId() {
		return $this->cruserId;
	}

	public function setCruserId($cruserId) {
		$this->cruserId = $cruserId;
	}

	public function getWidth() {
		return $this->width;
	}

	public function setWidth($width) {
		$this->width = $width;
	}

	public function getHeight() {
		return $this->height;
	}

	public function setHeight($height) {
		$this->height = $height;
	}

	public function getSorting() {
		return $this->sorting;
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		$this->content = $content;
	}


}


?>