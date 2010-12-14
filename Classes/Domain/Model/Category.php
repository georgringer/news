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
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Domain_Model_Category extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * @var integer
	 */
	protected $pid;

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
	 * @var Tx_News2_Domain_Model_Category
	 * @lazy
	 */
	protected $parentcategory;

	/**
	 * @var string
	 */
	protected $image;

	/**
	 * @var integer
	 */
	protected $shortcut;

	/**
	 * @var integer
	 */
	protected $singlePid;

	/**
	 * @var integer
	 */
	protected $importId;

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
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_Category>
	 * @lazy
	 */
	protected $childs;	

	public function getPid() {
		return $this->pid;
	}

	public function setPid($pid) {
		$this->pid = $pid;
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

	public function getStarttime() {
		return $this->starttime;
	}

	public function setStarttime($starttime) {
		$this->starttime = $starttime;
	}

	public function getEndtime() {
		return $this->endtime;
	}

	public function setEndtime($endtime) {
		$this->endtime = $endtime;
	}

	public function getSysLanguageUid() {
		return $this->sysLanguageUid;
	}

	public function setSysLanguageUid($sysLanguageUid) {
		$this->sysLanguageUid = $sysLanguageUid;
	}

	public function getL10nParent() {
		return $this->l10nParent;
	}

	public function setL10nParent($l10nParent) {
		$this->l10nParent = $l10nParent;
	}

	public function getTitle() {
	 return $this->title;
	}

	public function setTitle($title) {
	 $this->title = $title;
	}

	public function getDescription() {
	 return $this->description;
	}

	public function setDescription($description) {
	 $this->description = $description;
	}

	public function getImage() {
	 return $this->image;
	}

	public function setImage($image) {
	 $this->image = $image;
	}

	/**
	 *
	 * * @return Tx_News2_Domain_Model_Category
	 */
	public function getParentcategory() {
		if ($this->parentcategory instanceof Tx_Extbase_Persistence_LazyLoadingProxy) {
			$this->parentcategory->_loadRealInstance();
		}
	 return $this->parentcategory;
	}

	public function setParentcategory($category) {
	 $this->parentcategory = $category;
	}

	public function getShortcut() {
		return $this->shortcut;
	}

	public function setShortcut($shortcut) {
		$this->shortcut = $shortcut;
	}

	public function getSinglePid() {
		return $this->singlePid;
	}

	public function setSinglePid($singlePid) {
		$this->singlePid = $singlePid;
	}

	public function getImportId() {
		return $this->importId;
	}

	public function setImportId($importId) {
		$this->importId = $importId;
	}

	public function getSorting() {
		return $this->sorting;
	}

	public function setSorting($sorting) {
		$this->sorting = $sorting;
	}

	public function getFeGroup() {
		return $this->feGroup;
	}

	public function setFeGroup($feGroup) {
		$this->feGroup = $feGroup;
	}

	/**************************
	 * helper functions
	 */

	public function getCountRelatedNews() {
		/** @var Tx_News2_Domain_Repository_NewsRepository */
		$newsRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_NewsRepository');
		$newsRepository->setCategories($this->uid);
		return $newsRepository->countByTest();
	}

	public function getChilds() {
		/** @var Tx_News2_Domain_Repository_CategoryRepository */
		$categoryRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_CategoryRepository');
		$categoryRepository->setParentUidList($this->uid);
		$childs = $categoryRepository->findByParent();
		
		return $childs;
	}


}


?>