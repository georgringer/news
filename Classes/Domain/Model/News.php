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
 * News model
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Domain_Model_News extends Tx_Extbase_DomainObject_AbstractEntity {
	/**
	 * @var DateTime
	 */
	protected $crdate;

	/**
	 * @var DateTime
	 */
	protected $tstamp;

	/**
	 * @var integer
	 */
	protected $sysLanguageUid;

	/**
	 * @var integer
	 */
	protected $l10nParent;

	/**
	 * @var DateTime
	 */
	protected $starttime;

	/**
	 * @var DateTime
	 */
	protected $endtime;

	/**
	 * keep it as string as it should be only used during imports
	 * @var string
	 */
	protected $feGroup;

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
	protected $teaser;

	/**
	 * @var string
	 */
	protected $bodytext;

	/**
	 * @var DateTime
	 */
	protected $datetime;

	/**
	 * @var DateTime
	 */
	protected $archive;

	/**
	 * @var string
	 */
	protected $author;

	/**
	 * @var string
	 */
	protected $authorEmail;

	/**
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_Category>
	 * @lazy
	 */
	protected $category;

	/**
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_News>
	 * @lazy
	 */
	protected $related;

	/**
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_File>
	 * @lazy
	 */
	protected $relatedFiles;

	/**
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_Link>
	 * @lazy
	 */
	protected $relatedLinks;

	/**
	 * @var integer
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $keywords;

	/**
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_Media>
	 * @lazy
	 */
	protected $media;

	/**
	 * @var string
	 */
	protected $internalurl;

	/**
	 * @var string
	 */
	protected $externalurl;

	/**
	 * @var boolean
	 */
	protected $istopnews;

	/**
	 * @var integer
	 */
	protected $editlock;

	/**
	 * @var integer
	 */
	protected $importId;

	/**
	 * @var integer
	 */
	protected $sorting;

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getTeaser() {
		return $this->teaser;
	}

	public function setTeaser($teaser) {
		$this->teaser = $teaser;
	}

	public function getBodytext() {
		return $this->bodytext;
	}

	public function setBodytext($bodytext) {
		$this->bodytext = $bodytext;
	}

	public function getDatetime() {
		return $this->datetime;
	}

	public function setDatetime($datetime) {
		$this->datetime = $datetime;
	}

	public function getArchive() {
		return $this->archive;
	}

	public function setArchive($archive) {
		$this->archive = $archive;
	}

	public function getAuthor() {
		return $this->author;
	}

	public function setAuthor($author) {
		$this->author = $author;
	}

	public function getAuthorEmail() {
		return $this->authorEmail;
	}

	public function setAuthorEmail($authorEmail) {
		$this->authorEmail = $authorEmail;
	}

	/**
	 *
	 * * @return Tx_News2_Domain_Model_Category
	 */
	public function getCategory() {
		if ($this->category instanceof Tx_Extbase_Persistence_LazyLoadingProxy) {
			$this->category->_loadRealInstance();
		}
		return $this->category;
	}

	public function getFirstCategory() {
		$categories = $this->getCategory();
		$categories->rewind();

		return $categories->current();
	}

	public function setCategory($category) {
		$this->category = $category;
	}

	/**
	 *
	 * * @return Tx_News2_Domain_Model_News
	 */
	public function getRelated() {
		if ($this->related instanceof Tx_Extbase_Persistence_LazyLoadingProxy) {
			$this->related->_loadRealInstance();
		}
		return $this->related;
	}

	/**
	 * Return related items sorted by datetime
	 * 
	 * @return array
	 */
	public function getRelatedSorted() {
		$items = $this->getRelated();
		$items = $items->toArray();

		usort($items, create_function('$a, $b',  'return $a->getDatetime() < $b->getDatetime();'));
		return $items;
	}

	public function setRelated($related) {
		$this->related = $related;
	}

	public function getRelatedFiles() {
		if ($this->relatedFiles instanceof Tx_Extbase_Persistence_LazyLoadingProxy) {
			$this->relatedFiles->_loadRealInstance();
		}
		return $this->relatedFiles;
	}

	public function setRelatedFiles($relatedFiles) {
		$this->relatedFiles = $relatedFiles;
	}

	public function getRelatedLinks() {
		if ($this->relatedLinks instanceof Tx_Extbase_Persistence_LazyLoadingProxy) {
			$this->relatedLinks->_loadRealInstance();
		}
		return $this->relatedLinks;
	}

	public function setRelatedLinks($relatedLinks) {
		$this->relatedLinks = $relatedLinks;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getKeywords() {
		return $this->keywords;
	}

	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
	 * Load Media elements
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage
	 */
	public function getMedia() {
		if ($this->media instanceof Tx_Extbase_Persistence_LazyLoadingProxy) {
			$this->media->_loadRealInstance();
		}

		return $this->media;
	}

	public function getMediaPreviews() {
		$mediaElements = $this->media;

		$previewCollection = array();
		foreach ($mediaElements as $mediaElement) {
			if ($mediaElement->getShowinpreview()) {
				$previewCollection[] = $mediaElement;
			}
		}

		if (count($previewCollection) > 0) {
			return $previewCollection;
		}

		return NULL;
	}

	public function setMedia($media) {
		$this->media = $media;
	}

	public function getInternalurl() {
		return $this->internalurl;
	}

	public function setInternalurl($internalurl) {
		$this->internalurl = $internalurl;
	}

	public function getExternalurl() {
		return $this->externalurl;
	}

	public function setExternalurl($externalurl) {
		$this->externalurl = $externalurl;
	}

	public function getIstopnews() {
		return $this->istopnews;
	}

	public function setIstopnews($istopnews) {
		$this->istopnews = $istopnews;
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

	public function getCruserId() {
		return $this->cruserId;
	}

	public function setCruserId($cruserId) {
		$this->cruserId = $cruserId;
	}

	public function getEditlock() {
		return $this->editlock;
	}

	public function setEditlock($editlock) {
		$this->editlock = $editlock;
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

	public function getFeGroup() {
		return $this->feGroup;
	}

	public function setFeGroup($feGroup) {
		$this->feGroup = $feGroup;
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


}

?>