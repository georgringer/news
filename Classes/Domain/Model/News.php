<?php
namespace GeorgRinger\News\Domain\Model;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\File;
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\Link;

/**
 * News model
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class News extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var \DateTime
	 */
	protected $crdate;

	/**
	 * @var \DateTime
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
	 * @var \DateTime
	 */
	protected $starttime;

	/**
	 * @var \DateTime
	 */
	protected $endtime;

	/**
	 * keep it as string as it should be only used during imports
	 *
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
	protected $alternativeTitle;

	/**
	 * @var string
	 */
	protected $teaser;

	/**
	 * @var string
	 */
	protected $bodytext;

	/**
	 * @var \DateTime
	 */
	protected $datetime;

	/**
	 * @var \DateTime
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
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\Category>
	 * @lazy
	 */
	protected $categories;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\News>
	 * @lazy
	 */
	protected $related;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\News>
	 * @lazy
	 */
	protected $relatedFrom;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\File>
	 * @lazy
	 */
	protected $relatedFiles;

	/**
	 * Fal related files
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\FileReference>
	 * @lazy
	 */
	protected $falRelatedFiles;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\Link>
	 * @lazy
	 */
	protected $relatedLinks;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $keywords;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\Media>
	 * @lazy
	 */
	protected $media;

	/**
	 * Fal media items
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\FileReference>
	 * @lazy
	 */
	protected $falMedia;

	/**
	 * Fal media items with showinpreview set
	 *
	 * @var array
	 * @transient
	 */
	protected $falMediaPreviews;

	/**
	 * Fal media items with showinpreview not set
	 *
	 * @var array
	 * @transient
	 */
	protected $falMediaNonPreviews;

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
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\TtContent>
	 * @lazy
	 */
	protected $contentElements;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\Tag>
	 * @lazy
	 */
	protected $tags;

	/**
	 * @var string
	 */
	protected $pathSegment;

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
	 * @return \GeorgRinger\News\Domain\Model\News
	 */
	public function __construct() {
		$this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->contentElements = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->relatedFiles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->relatedLinks = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->media = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->falMedia = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->falRelatedFiles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
	 * Get alternative title
	 *
	 * @return string
	 */
	public function getAlternativeTitle() {
		return $this->alternativeTitle;
	}

	/**
	 * Set alternative title
	 *
	 * @param string $alternativeTitle
	 * @return void
	 */
	public function setAlternativeTitle($alternativeTitle) {
		$this->alternativeTitle = $alternativeTitle;
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
	 * @return \DateTime
	 */
	public function getDatetime() {
		return $this->datetime;
	}

	/**
	 * Set date time
	 *
	 * @param \DateTime $datetime datetime
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
		return $this->getDatetime()->format('Y');
	}

	/**
	 * Get month of datetime
	 *
	 * @return integer
	 */
	public function getMonthOfDatetime() {
		return $this->getDatetime()->format('m');
	}

	/**
	 * Get day of datetime
	 *
	 * @return integer
	 */
	public function getDayOfDatetime() {
		return (int)$this->datetime->format('d');
	}

	/**
	 * Get archive date
	 *
	 * @return \DateTime
	 */
	public function getArchive() {
		return $this->archive;
	}

	/**
	 * Set archive date
	 *
	 * @param \DateTime $archive archive date
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
		return $this->getArchive()->format('Y');
	}

	/**
	 * Get Month or archive date
	 *
	 * @return integer
	 */
	public function getMonthOfArchive() {
		return $this->getArchive()->format('m');
	}

	/**
	 * Get day of archive date
	 *
	 * @return integer
	 */
	public function getDayOfArchive() {
		return (int)$this->archive->format('d');
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
	 * Get author's email
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
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\Category>
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * Get first category
	 *
	 * @return Category
	 */
	public function getFirstCategory() {
		$categories = $this->getCategories();
		if (!is_null($categories)) {
			$categories->rewind();
			return $categories->current();
		} else {
			return NULL;
		}
	}

	/**
	 * Set categories
	 *
	 * @param  \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
	 * @return void
	 */
	public function setCategories($categories) {
		$this->categories = $categories;
	}

	/**
	 * Adds a category to this categories.
	 *
	 * @param Category $category
	 * @return void
	 */
	public function addCategory(Category $category) {
		$this->getCategories()->attach($category);
	}

	/**
	 * Get related news
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\News>
	 */
	public function getRelated() {
		return $this->related;
	}

	/**
	 * Set related from
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\News> $relatedFrom
	 * @return void
	 */
	public function setRelatedFrom($relatedFrom) {
		$this->relatedFrom = $relatedFrom;
	}

	/**
	 * Get related from
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\News>
	 */
	public function getRelatedFrom() {
		return $this->relatedFrom;
	}

	/**
	 * Return related from items sorted by datetime
	 *
	 * @return array
	 */
	public function getRelatedFromSorted() {
		$items = $this->getRelatedFrom();
		if ($items) {
			$items = $items->toArray();
			usort($items, create_function('$a, $b', 'return $a->getDatetime() < $b->getDatetime();'));
		}
		return $items;
	}

	/**
	 * Return related from items sorted by datetime
	 *
	 * @return array
	 */
	public function getAllRelatedSorted() {
		$all = array();
		$itemsRelated = $this->getRelated();
		if ($itemsRelated) {
			$all = array_merge($all, $itemsRelated->toArray());
		}

		$itemsRelatedFrom = $this->getRelatedFrom();
		if ($itemsRelatedFrom) {
			$all = array_merge($all, $itemsRelatedFrom->toArray());
		}

		if (count($all) > 0) {
			usort($all, create_function('$a, $b', 'return $a->getDatetime() < $b->getDatetime();'));
		}
		return $all;
	}


	/**
	 * Return related items sorted by datetime
	 *
	 * @return array
	 */
	public function getRelatedSorted() {
		$items = $this->getRelated();
		if ($items) {
			$items = $items->toArray();
			usort($items, create_function('$a, $b', 'return $a->getDatetime() < $b->getDatetime();'));
		}
		return $items;
	}

	/**
	 * Set related news
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $related related news
	 * @return void
	 */
	public function setRelated($related) {
		$this->related = $related;
	}

	/**
	 * Get related files
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\File>
	 */
	public function getRelatedFiles() {
		return $this->relatedFiles;
	}

	/**
	 * Set related files
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $relatedFiles related files
	 * @return void
	 */
	public function setRelatedFiles($relatedFiles) {
		$this->relatedFiles = $relatedFiles;
	}

	/**
	 * Adds a file to this files.
	 *
	 * @param File $file
	 * @return void
	 */
	public function addRelatedFile(File $file) {
		if ($this->getRelatedFiles() === NULL) {
			$this->relatedFiles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		}
		$this->getRelatedFiles()->attach($file);
	}

	/**
	 * Get related links
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\Link>
	 */
	public function getRelatedLinks() {
		return $this->relatedLinks;
	}

	/**
	 * Get FAL related files
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\FileReference>
	 */
	public function getFalRelatedFiles() {
		return $this->falRelatedFiles;
	}

	/**
	 * Set FAL related files
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $falRelatedFiles FAL related files
	 * @return void
	 */
	public function setFalRelatedFiles($falRelatedFiles) {
		$this->falRelatedFiles = $falRelatedFiles;
	}

	/**
	 * Adds a file to this files.
	 *
	 * @param FileReference $file
	 * @return void
	 */
	public function addFalRelatedFile(FileReference $file) {
		if ($this->getFalRelatedFiles() === NULL) {
			$this->falRelatedFiles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		}
		$this->getFalRelatedFiles()->attach($file);
	}

	/**
	 * Set related links
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\Link> $relatedLinks related links relation
	 * @return void
	 */
	public function setRelatedLinks($relatedLinks) {
		$this->relatedLinks = $relatedLinks;
	}

	/**
	 * Get type of news
	 *
	 * @return string
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
	 * Load Media elements
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
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
		$mediaElements = $this->getMedia();

		if (is_null($mediaElements)) {
			return NULL;
		}

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
		$mediaElements = $this->getMedia();

		if (is_null($mediaElements)) {
			return NULL;
		}

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
	 * Get all media elements of type image
	 *
	 * @return array|null
	 */
	public function getMediaTypeImage() {
		return $this->getMediaSelection(\GeorgRinger\News\Domain\Model\Media::MEDIA_TYPE_IMAGE);
	}

	/**
	 * Get all media elements of type multimedia
	 *
	 * @return array|null
	 */
	public function getMediaTypeMultimedia() {
		return $this->getMediaSelection(\GeorgRinger\News\Domain\Model\Media::MEDIA_TYPE_MULTIMEDIA);
	}

	/**
	 * Adds a media to this media.
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $media
	 * @return void
	 */
	public function addMedia(\GeorgRinger\News\Domain\Model\Media $media) {
		if ($this->getMedia() === NULL) {
			$this->media = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		}
		$this->getMedia()->attach($media);
	}

	/**
	 * Adds a related link.
	 *
	 * @param Link $relatedLink
	 * @return void
	 */
	public function addRelatedLink(Link $relatedLink) {
		if ($this->relatedLinks === NULL) {
			$this->relatedLinks = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		}
		$this->relatedLinks->attach($relatedLink);
	}

	/**
	 * Get first media element which is tagged as preview and is of type image
	 *
	 * @return \GeorgRinger\News\Domain\Model\Media
	 */
	public function getFirstImagePreview() {
		$mediaElements = $this->getMedia();

		if (is_null($mediaElements)) {
			return NULL;
		}

		foreach ($mediaElements as $mediaElement) {
			if ($mediaElement->getShowinpreview()) {
				return $mediaElement;
			}
		}

		return NULL;
	}

	/**
	 * Set media relation
	 *
	 * @param   \TYPO3\CMS\Extbase\Persistence\ObjectStorage $media
	 * @return void
	 */
	public function setMedia(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $media) {
		$this->media = $media;
	}

	/**
	 * Get the Fal media items
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getFalMedia() {
		return $this->falMedia;
	}

	/**
	 * Set Fal media relation
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $falMedia
	 * @return void
	 */
	public function setFalMedia(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $falMedia) {
		$this->falMedia = $falMedia;
	}

	/**
	 * Add a Fal media file reference
	 *
	 * @param FileReference $falMedia
	 */
	public function addFalMedia(FileReference $falMedia) {
		if ($this->getFalMedia() === NULL) {
			$this->falMedia = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		}
		$this->falMedia->attach($falMedia);
	}

	/**
	 * Get the Fal media items
	 *
	 * @return array
	 */
	public function getFalMediaPreviews() {
		if ($this->falMediaPreviews === NULL && $this->getFalMedia()) {
			$this->falMediaPreviews = array();
			/** @var $mediaItem FileReference */
			foreach ($this->getFalMedia() as $mediaItem) {
				if ($mediaItem->getOriginalResource()->getProperty('showinpreview')) {
					$this->falMediaPreviews[] = $mediaItem;
				}
			}
		}
		return $this->falMediaPreviews;
	}


	/**
	 * Get all media elements which are not tagged as preview
	 *
	 * @return array
	 */
	public function getFalMediaNonPreviews() {
		if ($this->falMediaNonPreviews === NULL && $this->getFalMedia()) {
			$this->falMediaNonPreviews = array();
			/** @var $mediaItem FileReference */
			foreach ($this->getFalMedia() as $mediaItem) {
				if (!$mediaItem->getOriginalResource()->getProperty('showinpreview')) {
					$this->falMediaNonPreviews[] = $mediaItem;
				}
			}
		}
		return $this->falMediaNonPreviews;
	}

	/**
	 * Get first media element which is tagged as preview and is of type image
	 *
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	public function getFirstFalImagePreview() {

		$mediaElements = $this->getFalMediaPreviews();
		if (is_array($mediaElements)) {
			foreach ($mediaElements as $mediaElement) {
				return $mediaElement;
			}
		}
		return NULL;
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
	 * @param string $internalUrl internal url
	 * @return void
	 */
	public function setInternalurl($internalUrl) {
		$this->internalurl = $internalUrl;
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
	 * @param string $externalUrl external url
	 * @return void
	 */
	public function setExternalurl($externalUrl) {
		$this->externalurl = $externalUrl;
	}

	/**
	 * Get top news flag
	 *
	 * @return boolean
	 */
	public function getIstopnews() {
		return $this->istopnews;
	}

	/**
	 * Set top news flag
	 *
	 * @param boolean $istopnews top news flag
	 * @return void
	 */
	public function setIstopnews($istopnews) {
		$this->istopnews = $istopnews;
	}

	/**
	 * Get content elements
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getContentElements() {
		return $this->contentElements;
	}

	/**
	 * Set content element list
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $contentElements content elements
	 * @return void
	 */
	public function setContentElements($contentElements) {
		$this->contentElements = $contentElements;
	}

	/**
	 * Adds a content element to the record
	 *
	 * @param \GeorgRinger\News\Domain\Model\TtContent $contentElement
	 * @return void
	 */
	public function addContentElement(\GeorgRinger\News\Domain\Model\TtContent $contentElement) {
		if ($this->getContentElements() === NULL) {
			$this->contentElements = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		}
		$this->contentElements->attach($contentElement);
	}

	/**
	 * Get id list of content elements
	 *
	 * @return string
	 */
	public function getContentElementIdList() {
		$idList = array();
		foreach ($this->getContentElements() as $contentElement) {
			$idList[] = $contentElement->getUid();
		}
		return implode(',', $idList);
	}

	/**
	 * Get Tags
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * Set Tags
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags tags
	 * @return void
	 */
	public function setTags($tags) {
		$this->tags = $tags;
	}

	/**
	 * Get path segment
	 *
	 * @return string
	 */
	public function getPathSegment() {
		return $this->pathSegment;
	}

	/**
	 * Set path segment
	 *
	 * @param string $pathSegment
	 * @return void
	 */
	public function setPathSegment($pathSegment) {
		$this->pathSegment = $pathSegment;
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
	 * Get year of crdate
	 *
	 * @return integer
	 */
	public function getYearOfCrdate() {
		return $this->getCrdate()->format('Y');
	}

	/**
	 * Get month of crdate
	 *
	 * @return integer
	 */
	public function getMonthOfCrdate() {
		return $this->getCrdate()->format('m');
	}

	/**
	 * Get day of crdate
	 *
	 * @return integer
	 */
	public function getDayOfCrdate() {
		return (int)$this->crdate->format('d');
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
	 * Set sys language
	 *
	 * @param int $sysLanguageUid
	 * @return void
	 */
	public function setSysLanguageUid($sysLanguageUid) {
		$this->_languageUid = $sysLanguageUid;
	}

	/**
	 * Get sys language
	 *
	 * @return int
	 */
	public function getSysLanguageUid() {
		return $this->_languageUid;
	}

	/**
	 * Set l10n parent
	 *
	 * @param int $l10nParent
	 * @return void
	 */
	public function setL10nParent($l10nParent) {
		$this->l10nParent = $l10nParent;
	}

	/**
	 * Get l10n parent
	 *
	 * @return int
	 */
	public function getL10nParent() {
		return $this->l10nParent;
	}

	/**
	 * Get year of tstamp
	 *
	 * @return integer
	 */
	public function getYearOfTstamp() {
		return $this->getTstamp()->format('Y');
	}

	/**
	 * Get month of tstamp
	 *
	 * @return integer
	 */
	public function getMonthOfTstamp() {
		return $this->getTstamp()->format('m');
	}

	/**
	 * Get day of tstamp
	 *
	 * @return integer
	 */
	public function getDayOfTimestamp() {
		return (int)$this->tstamp->format('d');
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
	 * @return \DateTime
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
	 * Get year of starttime
	 *
	 * @return integer
	 */
	public function getYearOfStarttime() {
		return $this->getStarttime()->format('Y');
	}

	/**
	 * Get month of starttime
	 *
	 * @return integer
	 */
	public function getMonthOfStarttime() {
		return $this->getStarttime()->format('m');
	}

	/**
	 * Get day of starttime
	 *
	 * @return integer
	 */
	public function getDayOfStarttime() {
		return (int)$this->starttime->format('d');
	}

	/**
	 * Get endtime
	 *
	 * @return \DateTime
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
	 * Get year of endtime
	 *
	 * @return integer
	 */
	public function getYearOfEndtime() {
		return $this->getEndtime()->format('Y');
	}

	/**
	 * Get month of endtime
	 *
	 * @return integer
	 */
	public function getMonthOfEndtime() {
		return $this->getEndtime()->format('m');
	}

	/**
	 * Get day of endtime
	 *
	 * @return integer
	 */
	public function getDayOfEndtime() {
		return (int)$this->endtime->format('d');
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
	 * @param string $feGroup comma separated list
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

	/**
	 * Get a sub selection of media elements
	 *
	 * @param $type
	 * @return array|null
	 */
	protected function getMediaSelection($type) {
		$mediaElements = $this->getMedia();

		if ($mediaElements === NULL) {
			return NULL;
		}

		$collection = array();
		foreach ($mediaElements as $mediaElement) {
			if ((int)$mediaElement->getType() === $type) {
				$collection[] = $mediaElement;
			}
		}

		if (count($collection) > 0) {
			return $collection;
		}

		return NULL;
	}
}
