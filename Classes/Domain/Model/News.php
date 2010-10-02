<?php

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



}


?>