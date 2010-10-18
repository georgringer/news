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

	/**
	 * @return DateTime
	 */
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

	public function setRelated($related) {
	 $this->related = $related;
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
	 * @param boolean $removeFirstElement remove first element
	 * @return Tx_Extbase_Persistence_ObjectStorage
	 */
	public function getMedia($removeFirstElement = FALSE) {
		if ($this->media instanceof Tx_Extbase_Persistence_LazyLoadingProxy) {
			$this->media->_loadRealInstance();
		}

		if ($removeFirstElement) {
			$currentElements = $this->media;
			$newElements = new Tx_Extbase_Persistence_ObjectStorage();
			$count = 0;
			foreach($currentElements as $media) {
				if ($count > 0) {
					$newElements->attach($media);
				}
				$count++;
			}
			return $newElements;
		}
		return $this->media;
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



}

?>