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
	protected $categories;

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
	 * @var string
	 */
	protected $contentElements;

	/**
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_Tag>
	 * @lazy
	 */
	protected $tags;

	/**
	 * @var integer
	 */
	protected $editlock;

	/**
	 * @var string
	 */
	protected $importId;

	/**
	 * @var string
	 */
	protected $importSource;

	/**
	 * @var integer
	 */
	protected $sorting;

	/**
	 * Initialize categories and media relation
	 *
	 * @return void
	 */
	public function __construct() {
		$this->categories = new Tx_Extbase_Persistence_ObjectStorage();
		$this->relatedFiles = new Tx_Extbase_Persistence_ObjectStorage();
		$this->media = new Tx_Extbase_Persistence_ObjectStorage();
	}

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
	 * Get Teaser text
	 *
	 * @return string
	 */
	public function getTeaser() {
		return $this->teaser;
	}

	/**
	 * Set Teaser text
	 *
	 * @param string $teaser teaser text
	 * @return void
	 */
	public function setTeaser($teaser) {
		$this->teaser = $teaser;
	}

	/**
	 * Get bodytext
	 *
	 * @return string
	 */
	public function getBodytext() {
		return $this->bodytext;
	}

	/**
	 * Set bodytext
	 *
	 * @param string $bodytext main content
	 * @return void
	 */
	public function setBodytext($bodytext) {
		$this->bodytext = $bodytext;
	}

	/**
	 * Get datetime
	 *
	 * @return DateTime
	 */
	public function getDatetime() {
		return $this->datetime;
	}

	/**
	 * Set date time
	 *
	 * @param DateTime $datetime datetime
	 * @return void
	 */
	public function setDatetime($datetime) {
		$this->datetime = $datetime;
	}

	/**
	 * Get year of datetime
	 *
	 * @return integer
	 */
	public function getYearOfDatetime() {
		return $this->datetime->format('Y');
	}

	/**
	 * Get month of datetime
	 *
	 * @return integer
	 */
	public function getMonthOfDatetime() {
		return $this->datetime->format('m');
	}

	/**
	 * Get archive date
	 *
	 * @return DateTime
	 */
	public function getArchive() {
		return $this->archive;
	}

	/**
	 * Set archive date
	 *
	 * @param DateTime $archive archive date
	 * @return void
	 */
	public function setArchive($archive) {
		$this->archive = $archive;
	}

	/**
	 * Get year of archive date
	 *
	 * @return integer
	 */
	public function getYearOfArchive() {
		return $this->archive->format('Y');
	}

	/**
	 * Get Month or archive date
	 *
	 * @return integer
	 */
	public function getMonthOfArchive() {
		return $this->archive->format('m');
	}

	/**
	 * Get author
	 *
	 * @return string
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * Set author
	 *
	 * @param string $author author
	 * @return void
	 */
	public function setAuthor($author) {
		$this->author = $author;
	}

	/**
	 * Gett author's email
	 *
	 * @return string
	 */
	public function getAuthorEmail() {
		return $this->authorEmail;
	}

	/**
	 * Set author's email
	 *
	 * @param string $authorEmail author's email
	 * @return void
	 */
	public function setAuthorEmail($authorEmail) {
		$this->authorEmail = $authorEmail;
	}

	/**
	 * Get categories
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_Category>
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * Get first category
	 *
	 * @return Tx_News2_Domain_Model_Category
	 */
	public function getFirstCategory() {
		$categories = $this->getCategories();
		$categories->rewind();

		return $categories->current();
	}

	/**
	 * Set categories
	 *
	 * @param  Tx_Extbase_Persistence_ObjectStorage $categories
	 * @return void
	 */
	public function setCategories($categories) {
		$this->categories = $categories;
	}

	/**
	 * Adds a category to this categories.
	 *
	 * @param Tx_News2_Domain_Model_Category $category
	 * @return void
	 */
	public function addCategory(Tx_News2_Domain_Model_Category $category) {
		$this->categories->attach($category);
	}

	/**
	 * Get related news
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_News>
	 */
	public function getRelated() {
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

		usort($items, create_function('$a, $b', 'return $a->getDatetime() < $b->getDatetime();'));
		return $items;
	}

	/**
	 * Set related news
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage $related related news
	 * @return void
	 */
	public function setRelated($related) {
		$this->related = $related;
	}

	/**
	 * Get related files
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_File>
	 */
	public function getRelatedFiles() {
		return $this->relatedFiles;
	}

	/**
	 * Set related files
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage $relatedFiles related files
	 * @return void
	 */
	public function setRelatedFiles($relatedFiles) {
		$this->relatedFiles = $relatedFiles;
	}

	/**
	 * Adds a file to this files.
	 *
	 * @param Tx_News2_Domain_Model_File $file
	 * @return void
	 */
	public function addRelatedFile(Tx_News2_Domain_Model_File $file) {
		$this->relatedFiles->attach($file);
	}

	/**
	 * Get related links
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_Link>
	 */
	public function getRelatedLinks() {
		return $this->relatedLinks;
	}

	/**
	 * Set related links
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_News2_Domain_Model_Link> $relatedLinks related links relation
	 * @return void
	 */
	public function setRelatedLinks($relatedLinks) {
		$this->relatedLinks = $relatedLinks;
	}

	/**
	 * Get type of news
	 *
	 * @return integer
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Set type of news
	 *
	 * @param integer $type type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Get keywords
	 *
	 * @return string
	 */
	public function getKeywords() {
		return $this->keywords;
	}

	/**
	 * Set keywords
	 *
	 * @param string $keywords keywords
	 * @return void
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
	 * Load Media elements
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage
	 */
	public function getMedia() {
		return $this->media;
	}

	/**
	 * Get all media elements which are tagged as preview
	 *
	 * @return array
	 */
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

	/**
	 * Get all media elements which are not tagged as preview
	 *
	 * @return array
	 */
	public function getNonMediaPreviews() {
		$mediaElements = $this->media;

		$collection = array();
		foreach ($mediaElements as $mediaElement) {
			if (!$mediaElement->getShowinpreview()) {
				$collection[] = $mediaElement;
			}
		}

		if (count($collection) > 0) {
			return $collection;
		}

		return NULL;
	}

	/**
	 * Adds a media to this media.
	 *
	 * @param Tx_News2_Domain_Model_Media $media
	 * @return void
	 */
	public function addMedia(Tx_News2_Domain_Model_Media $media) {
		$this->media->attach($media);

	}

	/**
	 * Get first media element which is tagged as preview and is of type image
	 *
	 * @return Tx_News2_Domain_Model_Media
	 */
	public function getFirstImagePreview() {
		$mediaElements = $this->getMedia();

		foreach ($mediaElements as $mediaElement) {
			if ($mediaElement->getShowinpreview() && $mediaElement->getType() == 0) {
				return $mediaElement;
			}
		}

		return NULL;
	}

	/**
	 * Set media relation
	 *
	 * @param   Tx_Extbase_Persistence_ObjectStorage $media
	 * @return void
	 */
	public function setMedia(Tx_Extbase_Persistence_ObjectStorage $media) {
		$this->media = $media;
	}

	/**
	 * Get internal url
	 *
	 * @return string
	 */
	public function getInternalurl() {
		return $this->internalurl;
	}

	/**
	 * Set internal url
	 *
	 * @param string $internalurl internal url
	 * @return void
	 */
	public function setInternalurl($internalurl) {
		$this->internalurl = $internalurl;
	}

	/**
	 * Get external url
	 *
	 * @return string
	 */
	public function getExternalurl() {
		return $this->externalurl;
	}

	/**
	 * Set external url
	 *
	 * @param string $externalurl external url
	 * @return void
	 */
	public function setExternalurl($externalurl) {
		$this->externalurl = $externalurl;
	}

	/**
	 * Get top news flag
	 *
	 * @return integer
	 */
	public function getIstopnews() {
		return $this->istopnews;
	}

	/**
	 * Set top news flag
	 *
	 * @param integer $istopnews top news flag
	 * @return void
	 */
	public function setIstopnews($istopnews) {
		$this->istopnews = $istopnews;
	}

	/**
	 * Get content elements
	 *
	 * @return string
	 */
	public function getContentElements() {
		return $this->contentElements;
	}

	/**
	 * Set content element list
	 *
	 * @param string $contentElements list of ce uids
	 * @return void
	 */
	public function setContentElements($contentElements) {
		$this->contentElements = $contentElements;
	}

	/**
	 * Get Tags
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * Set Tags
	 * @param Tx_Extbase_Persistence_ObjectStorage $tags tags
	 * @return void
	 */
	public function setTags($tags) {
		$this->tags = $tags;
	}


	/**
	 * Get creation date
	 *
	 * @return integer
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Set creation date
	 *
	 * @param integer $crdate
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Get timestamp
	 *
	 * @return integer
	 */
	public function getTstamp() {
		return $this->tstamp;
	}

	/**
	 * Set time stamp
	 *
	 * @param integer $tstamp time stamp
	 * @return void
	 */
	public function setTstamp($tstamp) {
		$this->tstamp = $tstamp;
	}

	/**
	 * Get id of creator user
	 *
	 * @return integer
	 */
	public function getCruserId() {
		return $this->cruserId;
	}

	/**
	 * Set cruser id
	 *
	 * @param integer $cruserId id of creator user
	 * @return void
	 */
	public function setCruserId($cruserId) {
		$this->cruserId = $cruserId;
	}

	/**
	 * Get editlock flag
	 *
	 * @return integer
	 */
	public function getEditlock() {
		return $this->editlock;
	}

	/**
	 * Set edit lock flag
	 *
	 * @param integer $editlock editlock flag
	 * @return void
	 */
	public function setEditlock($editlock) {
		$this->editlock = $editlock;
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
	 * Get start time
	 *
	 * @return integer
	 */
	public function getStarttime() {
		return $this->starttime;
	}

	/**
	 * Set start time
	 *
	 * @param integer $starttime start time
	 * @return void
	 */
	public function setStarttime($starttime) {
		$this->starttime = $starttime;
	}

	/**
	 * Get enddtime
	 *
	 * @return integer
	 */
	public function getEndtime() {
		return $this->endtime;
	}

	/**
	 * Set end time
	 *
	 * @param integer $endtime end time
	 * @return void
	 */
	public function setEndtime($endtime) {
		$this->endtime = $endtime;
	}

	/**
	 * Get fe groups
	 *
	 * @return string
	 */
	public function getFeGroup() {
		return $this->feGroup;
	}

	/**
	 * Set fe group
	 *
	 * @param string $feGroup comma seperated list
	 * @return void
	 */
	public function setFeGroup($feGroup) {
		$this->feGroup = $feGroup;
	}

	/**
	 * Get import id
	 *
	 * @return integer
	 */
	public function getImportId() {
		return $this->importId;
	}

	/**
	 * Set import id
	 *
	 * @param integer $importId import id
	 * @return void
	 */
	public function setImportId($importId) {
		$this->importId = $importId;
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
?>