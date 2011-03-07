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
	 * @var boolean
	 */
	protected $showinpreview;

	/**
	 * @var integer
	 */
	protected $width;

	/**
	 * @var integer
	 */
	protected $height;

	/**
	 * @var integer
	 */
	protected $sorting;

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set title
	 *
	 * @param string $title title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Get caption
	 *
	 * @return string
	 */
	public function getCaption() {
		return $this->caption;
	}

	/**
	 * Set caption
	 *
	 * @param string $caption caption
	 * @return void
	 */
	public function setCaption($caption) {
		$this->caption = $caption;
	}

	/**
	 * Get alt text
	 *
	 * @return string
	 */
	public function getAlt() {
		return $this->alt;
	}

	/**
	 * Set alt text
	 *
	 * @param string $alt alt text
	 * @return void
	 */
	public function setAlt($alt) {
		$this->alt = $alt;
	}

	/**
	 * Get type
	 *
	 * @return integer
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Set type
	 *
	 * @param integer $type type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Get flag if shown in preview
	 *
	 * @return integer
	 */
	public function getShowinpreview() {
		return $this->showinpreview;
	}

	/**
	 * Set show in preview flag
	 *
	 * @param integer $showinpreview flag if shown in preview
	 * @return void
	 */
	public function setShowinpreview($showinpreview) {
		$this->showinpreview = $showinpreview;
	}

	/**
	 * Get creation date
	 *
	 * @return DateTime
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Set creation date
	 *
	 * @param DateTime $crdate creation date
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Get timestamp
	 *
	 * @return DateTime
	 */
	public function getTstamp() {
		return $this->tstamp;
	}

	/**
	 * Set timestamp
	 *
	 * @param DateTime $tstamp timestamp
	 * @return void
	 */
	public function setTstamp($tstamp) {
		$this->tstamp = $tstamp;
	}

	/**
	 * Get hidden flag
	 *
	 * @return integer
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * Set hidden flag
	 *
	 * @param integer $hidden hidden flag
	 * @return void
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}

	/**
	 * Get deleted flag
	 *
	 * @return integer
	 */
	public function getDeleted() {
		return $this->deleted;
	}

	/**
	 * Set deleted flag
	 *
	 * @param integer $deleted deleted flag
	 * @return void
	 */
	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}

	/**
	 * Get creators user id
	 *
	 * @return integer
	 */
	public function getCruserId() {
		return $this->cruserId;
	}

	/**
	 * Set creators user id
	 *
	 * @param integer $cruserId creators user id
	 * @return void
	 */
	public function setCruserId($cruserId) {
		$this->cruserId = $cruserId;
	}

	/**
	 * Get width of element
	 *
	 * @return integer
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Set with of element
	 *
	 * @param integer $width integer
	 * @return void
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * Get height of element
	 *
	 * @return integer
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * Set height of element
	 *
	 * @param integer $height height
	 * @return void
	 */
	public function setHeight($height) {
		$this->height = $height;
	}

	/**
	 * Get sorting
	 *
	 * @return integer
	 */
	public function getSorting() {
		return $this->sorting;
	}

	/**
	 * Set sorting
	 *
	 * @param integer $sorting sorting
	 * @return void
	 */
	public function setSorting($sorting) {
		$this->sorting = $sorting;
	}

	/**
	 * Content of media element
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Set content of media element
	 *
	 * @param string $content content
	 * @return void
	 */
	public function setContent($content) {
		$this->content = $content;
	}

}

?>