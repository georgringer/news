<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * Model of tt_content
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Domain_Model_External_TtContent extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * @var DateTime
	 */
	protected $crdate;

	/**
	 * @var DateTime
	 */
	protected $tstamp;

	/**
	 * @var string
	 */
	protected $CType;

	/**
	 * @var string
	 */
	protected $header;

	/**
	 * @var string
	 */
	protected $headerPosition;

	/**
	 * @var string
	 */
	protected $bodytext;

	/**
	 * @var integer
	 */
	protected $colPos;

	/**
	 * @var string
	 */
	protected $image;

	/**
	 * @var integer
	 */
	protected $imagewidth;

	/**
	 * @var integer
	 */
	protected $imageorient;

	/**
	 * @var string
	 */
	protected $imagecaption;

	/**
	 * @var integer
	 */
	protected $imagecols;

	/**
	 * @var integer
	 */
	protected $imageborder;

	/**
	 * @var string
	 */
	protected $media;

	/**
	 * @var string
	 */
	protected $layout;

	/**
	 * @var integer
	 */
	protected $cols;

	/**
	 * @var string
	 */
	protected $subheader;

	/**
	 * @var string
	 */
	protected $headerLink;

	/**
	 * @var string
	 */
	protected $imageLink;

	/**
	 * @var string
	 */
	protected $imageZoom;

	/**
	 * @var string
	 */
	protected $altText;

	/**
	 * @var string
	 */
	protected $titleText;

	/**
	 * @var string
	 */
	protected $headerLayout;

	/**
	 * @var string
	 */
	protected $listType;


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

	public function getCType() {
		return $this->CType;
	}

	public function setCType($CType) {
		$this->CType = $CType;
	}

	public function getHeader() {
		return $this->header;
	}

	public function setHeader($header) {
		$this->header = $header;
	}

	public function getHeaderPosition() {
		return $this->headerPosition;
	}

	public function setHeaderPosition($headerPosition) {
		$this->headerPosition = $headerPosition;
	}

	public function getBodytext() {
		return $this->bodytext;
	}

	public function setBodytext($bodytext) {
		$this->bodytext = $bodytext;
	}

	/**
	 * Get the colpos
	 *
	 * @return integer
	 */
	public function getColPos() {
		return (int)$this->colPos;
	}

	/**
	 * Set colpos
	 *
	 * @param type $colPos
	 * @return void
	 */
	public function setColPos($colPos) {
		$this->colPos = $colPos;
	}

	public function getImage() {
		return $this->image;
	}

	public function setImage($image) {
		$this->image = $image;
	}

	public function getImagewidth() {
		return $this->imagewidth;
	}

	public function setImagewidth($imagewidth) {
		$this->imagewidth = $imagewidth;
	}

	public function getImageorient() {
		return $this->imageorient;
	}

	public function setImageorient($imageorient) {
		$this->imageorient = $imageorient;
	}

	public function getImagecaption() {
		return $this->imagecaption;
	}

	public function setImagecaption($imagecaption) {
		$this->imagecaption = $imagecaption;
	}

	public function getImagecols() {
		return $this->imagecols;
	}

	public function setImagecols($imagecols) {
		$this->imagecols = $imagecols;
	}

	public function getImageborder() {
		return $this->imageborder;
	}

	public function setImageborder($imageborder) {
		$this->imageborder = $imageborder;
	}

	public function getMedia() {
		return $this->media;
	}

	public function setMedia($media) {
		$this->media = $media;
	}

	public function getLayout() {
		return $this->layout;
	}

	public function setLayout($layout) {
		$this->layout = $layout;
	}

	public function getCols() {
		return $this->cols;
	}

	public function setCols($cols) {
		$this->cols = $cols;
	}

	public function getSubheader() {
		return $this->subheader;
	}

	public function setSubheader($subheader) {
		$this->subheader = $subheader;
	}

	public function getHeaderLink() {
		return $this->headerLink;
	}

	public function setHeaderLink($headerLink) {
		$this->headerLink = $headerLink;
	}

	public function getImageLink() {
		return $this->imageLink;
	}

	public function setImageLink($imageLink) {
		$this->imageLink = $imageLink;
	}

	public function getImageZoom() {
		return $this->imageZoom;
	}

	public function setImageZoom($imageZoom) {
		$this->imageZoom = $imageZoom;
	}

	public function getAltText() {
		return $this->altText;
	}

	public function setAltText($altText) {
		$this->altText = $altText;
	}

	public function getTitleText() {
		return $this->titleText;
	}

	public function setTitleText($titleText) {
		$this->titleText = $titleText;
	}

	public function getHeaderLayout() {
		return $this->headerLayout;
	}

	public function setHeaderLayout($headerLayout) {
		$this->headerLayout = $headerLayout;
	}

	public function getListType() {
		return $this->listType;
	}

	public function setListType($listType) {
		$this->listType = $listType;
	}



}

?>