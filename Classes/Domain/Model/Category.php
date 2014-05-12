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
 * Category Model
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Domain_Model_Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	/**
	 * @var integer
	 */
	protected $sorting;

	/**
	 * @var DateTime
	 */
	protected $crdate;

	/**
	 * @var DateTime
	 */
	protected $tstamp;

	/**
	 * @var DateTime
	 */
	protected $starttime;

	/**
	 * @var boolean
	 */
	protected $hidden;

	/**
	 * @var DateTime
	 */
	protected $endtime;

	/**
	 * @var integer
	 */
	protected $sysLanguageUid;

	/**
	 * @var integer
	 */
	protected $l10nParent;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var Tx_News_Domain_Model_Category
	 * @lazy
	 */
	protected $parentcategory;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_News_Domain_Model_FileReference>
	 * @lazy
	 */
	protected $images;

	/**
	 * @var integer
	 */
	protected $shortcut;

	/**
	 * @var integer
	 */
	protected $singlePid;

	/**
	 * @var string
	 */
	protected $importId;

	/**
	 * @var string
	 */
	protected $importSource;

	/**
	 * keep it as string as it should be only used during imports
	 * @var string
	 */
	protected $feGroup;

	/**
	 * @var integer
	 */
	protected $countRelatedNews = 0;

	/**
	 * Get creation date
	 *
	 * @return DateTime
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Set Creation Date
	 *
	 * @param DateTime $crdate crdate
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Get Tstamp
	 *
	 * @return DateTime
	 */
	public function getTstamp() {
		return $this->tstamp;
	}

	/**
	 * Set tstamp
	 *
	 * @param DateTime $tstamp tstamp
	 * @return void
	 */
	public function setTstamp($tstamp) {
		$this->tstamp = $tstamp;
	}

	/**
	 * Get starttime
	 *
	 * @return DateTime
	 */
	public function getStarttime() {
		return $this->starttime;
	}

	/**
	 * Set starttime
	 *
	 * @param DateTime $starttime starttime
	 * @return void
	 */
	public function setStarttime($starttime) {
		$this->starttime = $starttime;
	}

	/**
	 * Get Endtime
	 *
	 * @return DateTime
	 */
	public function getEndtime() {
		return $this->endtime;
	}

	/**
	 * Set Endtime
	 *
	 * @param DateTime $endtime endttime
	 * @return void
	 */
	public function setEndtime($endtime) {
		$this->endtime = $endtime;
	}

	/**
	 * Get Hidden
	 *
	 * @return boolean
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * Set Hidden
	 *
	 * @param boolean $hidden
	 * @return void
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}

	/**
	 * Get sys language
	 *
	 * @return integer
	 */
	public function getSysLanguageUid() {
		return $this->_languageUid;
	}

	/**
	 * Set sys language
	 *
	 * @param integer $sysLanguageUid language uid
	 * @return void
	 */
	public function setSysLanguageUid($sysLanguageUid) {
		$this->_languageUid = $sysLanguageUid;
	}

	/**
	 * Get language parent
	 *
	 * @return integer
	 */
	public function getL10nParent() {
		return $this->l10nParent;
	}

	/**
	 * Set language parent
	 *
	 * @param integer $l10nParent l10nParent
	 * @return void
	 */
	public function setL10nParent($l10nParent) {
		$this->l10nParent = $l10nParent;
	}

	/**
	 * Get category title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set category title
	 *
	 * @param string $title title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Set description
	 *
	 * @param string $description description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
	 */
	public function setImages($images) {
		$this->images = $images;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * Add image
	 *
	 * @param Tx_News_Domain_Model_FileReference $image
	 */
	public function addImage(Tx_News_Domain_Model_FileReference $image) {
		$this->images->attach($image);
	}

	/**
	 * Remove image
	 *
	 * @param Tx_News_Domain_Model_FileReference $image
	 */
	public function removeImage(Tx_News_Domain_Model_FileReference $image) {
		$this->images->detach($image);
	}

	/**
	 * Get the first image
	 *
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
	 */
	public function getFirstImage() {
		$images = $this->getImages();
		foreach($images as $image) {
			return $image;
		}

		return NULL;
	}

	/**
	 * Get parent category
	 *
	 * @return Tx_News_Domain_Model_Category
	 */
	public function getParentcategory() {
		return $this->parentcategory;
	}

	/**
	 * Set parent category
	 *
	 * @param Tx_News_Domain_Model_Category $category parent category
	 * @return void
	 */
	public function setParentcategory(Tx_News_Domain_Model_Category $category) {
		$this->parentcategory = $category;
	}

	/**
	 * Get shortcut
	 *
	 * @return integer
	 */
	public function getShortcut() {
		return $this->shortcut;
	}

	/**
	 * Set shortcut
	 *
	 * @param integer $shortcut shortcut
	 * @return void
	 */
	public function setShortcut($shortcut) {
		$this->shortcut = $shortcut;
	}

	/**
	 * Get single pid of category
	 *
	 * @return integer
	 */
	public function getSinglePid() {
		return $this->singlePid;
	}

	/**
	 * Set single pid
	 *
	 * @param integer $singlePid single pid
	 * @return void
	 */
	public function setSinglePid($singlePid) {
		$this->singlePid = $singlePid;
	}

	/**
	 * Get import id
	 *
	 * @return string
	 */
	public function getImportId() {
		return $this->importId;
	}

	/**
	 * Set import id
	 *
	 * @param string $importId import id
	 * @return void
	 */
	public function setImportId($importId) {
		$this->importId = $importId;
	}

	/**
	 * Get sorting id
	 *
	 * @return integer sorting id
	 */
	public function getSorting() {
		return $this->sorting;
	}

	/**
	 * Set sorting id
	 *
	 * @param integer $sorting sorting id
	 * @return void
	 */
	public function setSorting($sorting) {
		$this->sorting = $sorting;
	}

	/**
	 * Get feGroup
	 *
	 * @return string
	 */
	public function getFeGroup() {
		return $this->feGroup;
	}

	/**
	 * Get feGroup
	 *
	 * @param string $feGroup feGroup
	 * @return void
	 */
	public function setFeGroup($feGroup) {
		$this->feGroup = $feGroup;
	}

	/**
	 * Set importSource
	 *
	 * @param  string $importSource
	 * @return void
	 */
	public function setImportSource($importSource) {
		$this->importSource = $importSource;
	}

	/**
	 * Get importSource
	 *
	 * @return string
	 */
	public function getImportSource() {
		return $this->importSource;
	}
}
