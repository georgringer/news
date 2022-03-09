<?php

namespace GeorgRinger\News\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
/**
 * News model
 */
class News extends AbstractEntity
{

    /**
     * @var DateTime
     */
    protected $crdate;

    /**
     * @var DateTime
     */
    protected $tstamp;

    /**
     * @var int
     */
    protected $sysLanguageUid = 0;

    /**
     * @var int
     */
    protected $l10nParent = 0;

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
     *
     * @var string
     */
    protected $feGroup = '';

    /**
     * @var bool
     */
    protected $hidden = false;

    /**
     * @var bool
     */
    protected $deleted = false;

    /**
     * @var int
     */
    protected $cruserId = 0;

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $alternativeTitle = '';

    /**
     * @var string
     */
    protected $teaser ='';

    /**
     * @var string
     */
    protected $bodytext = '';

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
    protected $author = '';

    /**
     * @var string
     */
    protected $authorEmail = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\Category>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $categories;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $related;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $relatedFrom;

    /**
     * Fal related files
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $falRelatedFiles;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\Link>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $relatedLinks;

    /**
     * @var array
     */
    protected $sortingForeign;

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var string
     */
    protected $keywords = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * Fal media items
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $falMedia;

    /**
     * Fal media items with showinpreview set
     *
     * @var array
     * @TYPO3\CMS\Extbase\Annotation\ORM\Transient
     */
    protected $falMediaPreviews;

    /**
     * Fal media items with showinpreview not set
     *
     * @var array
     * @TYPO3\CMS\Extbase\Annotation\ORM\Transient
     */
    protected $falMediaNonPreviews;

    /**
     * @var string
     */
    protected $internalurl = '';

    /**
     * @var string
     */
    protected $externalurl = '';

    /**
     * @var bool
     */
    protected $istopnews = false;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\TtContent>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $contentElements = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\Tag>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $tags;

    /**
     * @var string
     */
    protected $pathSegment = '';

    /**
     * @var int
     */
    protected $editlock = 0;

    /**
     * @var string
     */
    protected $importId = '';

    /**
     * @var string
     */
    protected $importSource = '';

    /**
     * @var int
     */
    protected $sorting = 0;

    /** @var string */
    protected $notes ='';

    /**
     * Initialize categories and media relation
     *
     * @return \GeorgRinger\News\Domain\Model\News
     */
    public function __construct()
    {
        $this->categories = new ObjectStorage();
        $this->contentElements = new ObjectStorage();
        $this->relatedLinks = new ObjectStorage();
        $this->falMedia = new ObjectStorage();
        $this->falRelatedFiles = new ObjectStorage();
        $this->tags = new ObjectStorage();
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title title
     *
     * @return void
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * Get alternative title
     *
     * @return string
     */
    public function getAlternativeTitle(): string
    {
        return $this->alternativeTitle;
    }

    /**
     * Set alternative title
     *
     * @param string $alternativeTitle
     *
     * @return void
     */
    public function setAlternativeTitle($alternativeTitle): void
    {
        $this->alternativeTitle = $alternativeTitle;
    }

    /**
     * Get Teaser text
     *
     * @return string
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * Set Teaser text
     *
     * @param string $teaser teaser text
     *
     * @return void
     */
    public function setTeaser($teaser): void
    {
        $this->teaser = $teaser;
    }

    /**
     * Get bodytext
     *
     * @return string
     */
    public function getBodytext(): string
    {
        return $this->bodytext;
    }

    /**
     * Set bodytext
     *
     * @param string $bodytext main content
     *
     * @return void
     */
    public function setBodytext($bodytext): void
    {
        $this->bodytext = $bodytext;
    }

    /**
     * Get datetime
     *
     * @return null|DateTime
     */
    public function getDatetime(): ?DateTime
    {
        return $this->datetime;
    }

    /**
     * Set date time
     *
     * @param DateTime $datetime datetime
     *
     * @return void
     */
    public function setDatetime($datetime): void
    {
        $this->datetime = $datetime;
    }

    /**
     * Get year of datetime
     *
     * @return false|string
     */
    public function getYearOfDatetime()
    {
        return $this->getDatetime()->format('Y');
    }

    /**
     * Get month of datetime
     *
     * @return false|string
     */
    public function getMonthOfDatetime()
    {
        return $this->getDatetime()->format('m');
    }

    /**
     * Get day of datetime
     *
     * @return int
     */
    public function getDayOfDatetime(): int
    {
        return (int)$this->datetime->format('d');
    }

    /**
     * Get archive date
     *
     * @return null|DateTime
     */
    public function getArchive(): ?DateTime
    {
        return $this->archive;
    }

    /**
     * Set archive date
     *
     * @param DateTime $archive archive date
     *
     * @return void
     */
    public function setArchive($archive): void
    {
        $this->archive = $archive;
    }

    /**
     * Get year of archive date
     *
     * @return int
     */
    public function getYearOfArchive(): int
    {
        if ($this->getArchive()) {
            return (int)$this->getArchive()->format('Y');
        }
        return 0;
    }

    /**
     * Get Month or archive date
     *
     * @return int
     */
    public function getMonthOfArchive(): int
    {
        if ($this->getArchive()) {
            return (int)$this->getArchive()->format('m');
        }
        return 0;
    }

    /**
     * Get day of archive date
     *
     * @return int
     */
    public function getDayOfArchive(): int
    {
        if ($this->archive) {
            return (int)$this->archive->format('d');
        }
        return 0;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param string $author author
     *
     * @return void
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * Get author's email
     *
     * @return string
     */
    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }

    /**
     * Set author's email
     *
     * @param string $authorEmail author's email
     *
     * @return void
     */
    public function setAuthorEmail($authorEmail): void
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * Get categories
     *
     * @return null|ObjectStorage
     */
    public function getCategories(): ?ObjectStorage
    {
        return $this->categories;
    }

    /**
     * Get first category
     *
     * @return null|Category
     */
    public function getFirstCategory(): ?Category
    {
        $categories = $this->getCategories();
        if (!is_null($categories) && $categories->count() > 0) {
            $categories->rewind();
            return $categories->current();
        }
        return null;
    }

    /**
     * Set categories
     *
     * @param  \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
     *
     * @return void
     */
    public function setCategories($categories): void
    {
        $this->categories = $categories;
    }

    /**
     * Adds a category to this categories.
     *
     * @param Category $category
     *
     * @return void
     */
    public function addCategory(Category $category): void
    {
        $this->getCategories()->attach($category);
    }

    /**
     * Get related news
     *
     * @return \GeorgRinger\News\Domain\Model\News[]
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * Set related from
     *
     * @param ObjectStorage $relatedFrom
     * @return void
     */
    public function setRelatedFrom($relatedFrom): void
    {
        $this->relatedFrom = $relatedFrom;
    }

    /**
     * Get related from
     *
     * @return \GeorgRinger\News\Domain\Model\News[]
     */
    public function getRelatedFrom()
    {
        return $this->relatedFrom;
    }

    /**
     * Return related from items sorted by datetime
     *
     * @return array
     */
    public function getRelatedFromSorted(): array
    {
        $items = $this->getRelatedFrom();
        if ($items) {
            $items = $items->toArray();
            usort($items, function ($a, $b) {
                return $a->getDatetime() < $b->getDatetime();
            });
        }
        return $items;
    }

    /**
     * Return related from items sorted by datetime
     *
     * @return array
     */
    public function getAllRelatedSorted(): array
    {
        $all = [];
        $itemsRelated = $this->getRelated();
        if ($itemsRelated) {
            $all = array_merge($all, $itemsRelated->toArray());
        }

        $itemsRelatedFrom = $this->getRelatedFrom();
        if ($itemsRelatedFrom) {
            $all = array_merge($all, $itemsRelatedFrom->toArray());
        }
        $all = array_unique($all);

        if (count($all) > 0) {
            usort($all, function ($a, $b) {
                return $a->getDatetime() < $b->getDatetime();
            });
        }
        return $all;
    }

    /**
     * Return related items sorted by datetime
     *
     * @return array
     */
    public function getRelatedSorted(): array
    {
        $items = $this->getRelated();
        if ($items) {
            $items = $items->toArray();
            usort($items, function ($a, $b) {
                return $a->getDatetime() < $b->getDatetime();
            });
        }
        return $items;
    }

    /**
     * Set related news
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $related related news
     *
     * @return void
     */
    public function setRelated($related): void
    {
        $this->related = $related;
    }

    /**
     * Get related links
     *
     * @return null|ObjectStorage
     */
    public function getRelatedLinks(): ?ObjectStorage
    {
        return $this->relatedLinks;
    }

    /**
     * Get FAL related files
     *
     * @return null|ObjectStorage
     */
    public function getFalRelatedFiles(): ObjectStorage
    {
        return $this->falRelatedFiles;
    }

    /**
     * Short method for getFalRelatedFiles
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getRelatedFiles(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->getFalRelatedFiles();
    }

    /**
     * Set FAL related files
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $falRelatedFiles FAL related files
     *
     * @return void
     */
    public function setFalRelatedFiles($falRelatedFiles): void
    {
        $this->falRelatedFiles = $falRelatedFiles;
    }

    /**
     * Adds a file to this files.
     *
     * @param FileReference $file
     *
     * @return void
     */
    public function addFalRelatedFile(FileReference $file): void
    {
        if ($this->getFalRelatedFiles() === null) {
            $this->falRelatedFiles = new ObjectStorage();
        }
        $this->getFalRelatedFiles()->attach($file);
    }

    /**
     * Set related links
     *
     * @param ObjectStorage $relatedLinks related links relation
     * @return void
     */
    public function setRelatedLinks($relatedLinks): void
    {
        $this->relatedLinks = $relatedLinks;
    }

    /**
     * Get type of news
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set type of news
     *
     * @param string $type type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * Set keywords
     *
     * @param string $keywords keywords
     *
     * @return void
     */
    public function setKeywords($keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description description
     *
     * @return void
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Adds a related link.
     *
     * @param Link $relatedLink
     *
     * @return void
     */
    public function addRelatedLink(Link $relatedLink): void
    {
        if ($this->relatedLinks === null) {
            $this->relatedLinks = new ObjectStorage();
        }
        $this->relatedLinks->attach($relatedLink);
    }

    /**
     * Get the Fal media items
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getFalMedia(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->falMedia;
    }

    /**
     * Short method for getFalMedia()
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getMedia(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->getFalMedia();
    }

    /**
     * Set Fal media relation
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $falMedia
     *
     * @return void
     */
    public function setFalMedia(ObjectStorage $falMedia): void
    {
        $this->falMedia = $falMedia;
    }

    /**
     * Add a Fal media file reference
     *
     * @param FileReference $falMedia
     *
     * @return void
     */
    public function addFalMedia(FileReference $falMedia): void
    {
        if ($this->getFalMedia() === null) {
            $this->falMedia = new ObjectStorage();
        }
        $this->falMedia->attach($falMedia);
    }

    /**
     * Get the Fal media items
     *
     * @return array
     */
    public function getMediaPreviews(): array
    {
        $configuration = [FileReference::VIEW_LIST_AND_DETAIL, FileReference::VIEW_LIST_ONLY];
        return $this->getMediaItemsByConfiguration($configuration);
    }

    /**
     * Get all media elements which are allowed for detail views
     *
     * @return array
     */
    public function getMediaNonPreviews(): array
    {
        $configuration = [FileReference::VIEW_LIST_AND_DETAIL, FileReference::VIEW_DETAIL_ONLY];
        return $this->getMediaItemsByConfiguration($configuration);
    }

    /**
     * Get all media elements which are only for list views
     *
     * @return array
     */
    public function getMediaListOnly(): array
    {
        $configuration = [FileReference::VIEW_LIST_ONLY];
        return $this->getMediaItemsByConfiguration($configuration);
    }

    /**
     * Get all media elements which are only for detail views
     *
     * @return array
     */
    public function getMediaDetailOnly(): array
    {
        $configuration = [FileReference::VIEW_DETAIL_ONLY];
        return $this->getMediaItemsByConfiguration($configuration);
    }

    /**
     * Get first preview
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     */
    public function getFirstPreview(): ?\TYPO3\CMS\Extbase\Domain\Model\FileReference
    {
        foreach ($this->getMediaPreviews() as $mediaElement) {
            return $mediaElement;
        }
        return null;
    }

    /**
     * Get first non preview
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     */
    public function getFirstNonePreview(): ?\TYPO3\CMS\Extbase\Domain\Model\FileReference
    {
        foreach ($this->getMediaNonPreviews() as $mediaElement) {
            return $mediaElement;
        }
        return null;
    }

    /**
     * @param array $list
     * @return array
     */
    protected function getMediaItemsByConfiguration(array $list): array
    {
        $items = [];
        if ($this->getFalMedia()) {
            foreach ($this->getFalMedia() as $mediaItem) {
                /** @var FileReference $mediaItem */
                $configuration = (int)$mediaItem->getOriginalResource()->getProperty('showinpreview');
                if (in_array($configuration, $list, true)) {
                    $items[] = $mediaItem;
                }
            }
        }
        return $items;
    }

    /**
     * Get internal url
     *
     * @return string
     */
    public function getInternalurl(): string
    {
        return $this->internalurl;
    }

    /**
     * Set internal url
     *
     * @param string $internalUrl internal url
     *
     * @return void
     */
    public function setInternalurl($internalUrl): void
    {
        $this->internalurl = $internalUrl;
    }

    /**
     * Get external url
     *
     * @return string
     */
    public function getExternalurl(): string
    {
        return $this->externalurl;
    }

    /**
     * Set external url
     *
     * @param string $externalUrl external url
     *
     * @return void
     */
    public function setExternalurl($externalUrl): void
    {
        $this->externalurl = $externalUrl;
    }

    /**
     * Get top news flag
     *
     * @return bool
     */
    public function getIstopnews(): bool
    {
        return $this->istopnews;
    }

    /**
     * Set top news flag
     *
     * @param bool $istopnews top news flag
     *
     * @return void
     */
    public function setIstopnews($istopnews): void
    {
        $this->istopnews = $istopnews;
    }

    /**
     * Get content elements
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getContentElements(): ?\TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->contentElements;
    }

    /**
     * Set content element list
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $contentElements content elements
     *
     * @return void
     */
    public function setContentElements($contentElements): void
    {
        $this->contentElements = $contentElements;
    }

    /**
     * Adds a content element to the record
     *
     * @param \GeorgRinger\News\Domain\Model\TtContent $contentElement
     *
     * @return void
     */
    public function addContentElement(TtContent $contentElement): void
    {
        if ($this->getContentElements() === null) {
            $this->contentElements = new ObjectStorage();
        }
        $this->contentElements->attach($contentElement);
    }

    /**
     * Get id list of content elements
     *
     * @return string
     */
    public function getContentElementIdList(): string
    {
        return $this->getIdOfContentElements();
    }

    /**
     * Get translated id list of content elements
     *
     * @return string
     */
    public function getTranslatedContentElementIdList(): string
    {
        return $this->getIdOfContentElements(false);
    }

    /**
     * Collect id list
     *
     * @param bool $original
     * @return string
     */
    protected function getIdOfContentElements($original = true): string
    {
        $idList = [];
        $contentElements = $this->getContentElements();
        if ($contentElements) {
            foreach ($this->getContentElements() as $contentElement) {
                if ($contentElement->getColPos() >= 0) {
                    $idList[] = $original ? $contentElement->getUid() : $contentElement->_getProperty('_localizedUid');
                }
            }
        }
        return implode(',', $idList);
    }

    /**
     * Get Tags
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getTags(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->tags;
    }

    /**
     * Set Tags
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags tags
     *
     * @return void
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    /**
     * Adds a tag
     *
     * @param \GeorgRinger\News\Domain\Model\Tag $tag
     *
     * @return void
     */
    public function addTag(Tag $tag): void
    {
        $this->tags->attach($tag);
    }

    /**
     * Removes a tag
     *
     * @param \GeorgRinger\News\Domain\Model\Tag $tag
     *
     * @return void
     */
    public function removeTag(Tag $tag): void
    {
        $this->tags->detach($tag);
    }

    /**
     * Get path segment
     *
     * @return string
     */
    public function getPathSegment(): string
    {
        return $this->pathSegment;
    }

    /**
     * Set path segment
     *
     * @param string $pathSegment
     *
     * @return void
     */
    public function setPathSegment($pathSegment): void
    {
        $this->pathSegment = $pathSegment;
    }

    /**
     * Get creation date
     *
     * @return DateTime
     */
    public function getCrdate(): DateTime
    {
        return $this->crdate;
    }

    /**
     * Set creation date
     *
     * @param DateTime $crdate
     *
     * @return void
     */
    public function setCrdate($crdate): void
    {
        $this->crdate = $crdate;
    }

    /**
     * Get year of crdate
     *
     * @return false|string
     */
    public function getYearOfCrdate()
    {
        return $this->getCrdate()->format('Y');
    }

    /**
     * Get month of crdate
     *
     * @return false|string
     */
    public function getMonthOfCrdate()
    {
        return $this->getCrdate()->format('m');
    }

    /**
     * Get day of crdate
     *
     * @return int
     */
    public function getDayOfCrdate(): int
    {
        return (int)$this->crdate->format('d');
    }

    /**
     * Get timestamp
     *
     * @return DateTime
     */
    public function getTstamp(): DateTime
    {
        return $this->tstamp;
    }

    /**
     * Set time stamp
     *
     * @param DateTime $tstamp time stamp
     *
     * @return void
     */
    public function setTstamp($tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Set sys language
     *
     * @param int $sysLanguageUid
     *
     * @return void
     */
    public function setSysLanguageUid($sysLanguageUid): void
    {
        $this->_languageUid = $sysLanguageUid;
    }

    /**
     * Get sys language
     *
     * @return int
     */
    public function getSysLanguageUid(): int
    {
        return $this->_languageUid;
    }

    /**
     * Set l10n parent
     *
     * @param int $l10nParent
     *
     * @return void
     */
    public function setL10nParent($l10nParent): void
    {
        $this->l10nParent = $l10nParent;
    }

    /**
     * Get l10n parent
     *
     * @return int
     */
    public function getL10nParent(): int
    {
        return $this->l10nParent;
    }

    /**
     * Get year of tstamp
     *
     * @return false|string
     */
    public function getYearOfTstamp()
    {
        return $this->getTstamp()->format('Y');
    }

    /**
     * Get month of tstamp
     *
     * @return false|string
     */
    public function getMonthOfTstamp()
    {
        return $this->getTstamp()->format('m');
    }

    /**
     * Get day of tstamp
     *
     * @return int
     */
    public function getDayOfTimestamp(): int
    {
        return (int)$this->tstamp->format('d');
    }

    /**
     * Get id of creator user
     *
     * @return int
     */
    public function getCruserId(): int
    {
        return $this->cruserId;
    }

    /**
     * Set cruser id
     *
     * @param int $cruserId id of creator user
     *
     * @return void
     */
    public function setCruserId($cruserId): void
    {
        $this->cruserId = $cruserId;
    }

    /**
     * Get editlock flag
     *
     * @return int
     */
    public function getEditlock(): int
    {
        return $this->editlock;
    }

    /**
     * Set edit lock flag
     *
     * @param int $editlock editlock flag
     *
     * @return void
     */
    public function setEditlock($editlock): void
    {
        $this->editlock = $editlock;
    }

    /**
     * Get hidden flag
     *
     * @return bool
     */
    public function getHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * Set hidden flag
     *
     * @param bool $hidden hidden flag
     * @return void
     */
    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    /**
     * Get deleted flag
     *
     * @return bool
     */
    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Set deleted flag
     *
     * @param bool $deleted deleted flag
     *
     * @return void
     */
    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * Get start time
     *
     * @return DateTime
     */
    public function getStarttime(): DateTime
    {
        return $this->starttime;
    }

    /**
     * Set start time
     *
     * @param DateTime $starttime start time
     *
     * @return void
     */
    public function setStarttime($starttime): void
    {
        $this->starttime = $starttime;
    }

    /**
     * Get year of starttime
     *
     * @return int
     */
    public function getYearOfStarttime(): int
    {
        if ($this->getStarttime()) {
            return (int)$this->getStarttime()->format('Y');
        }
        return 0;
    }

    /**
     * Get month of starttime
     *
     * @return int
     */
    public function getMonthOfStarttime(): int
    {
        if ($this->getStarttime()) {
            return (int)$this->getStarttime()->format('m');
        }
        return 0;
    }

    /**
     * Get day of starttime
     *
     * @return int
     */
    public function getDayOfStarttime(): int
    {
        if ($this->starttime) {
            return (int)$this->starttime->format('d');
        }
        return 0;
    }

    /**
     * Get endtime
     *
     * @return DateTime
     */
    public function getEndtime(): DateTime
    {
        return $this->endtime;
    }

    /**
     * Set end time
     *
     * @param DateTime $endtime end time
     *
     * @return void
     */
    public function setEndtime(DateTime $endtime): void
    {
        $this->endtime = $endtime;
    }

    /**
     * Get year of endtime
     *
     * @return int
     */
    public function getYearOfEndtime(): int
    {
        if ($this->getEndtime()) {
            return (int)$this->getEndtime()->format('Y');
        }
        return 0;
    }

    /**
     * Get month of endtime
     *
     * @return int
     */
    public function getMonthOfEndtime(): int
    {
        if ($this->getEndtime()) {
            return (int)$this->getEndtime()->format('m');
        }
        return 0;
    }

    /**
     * Get day of endtime
     *
     * @return int
     */
    public function getDayOfEndtime(): int
    {
        if ($this->endtime) {
            return (int)$this->endtime->format('d');
        }
        return 0;
    }

    /**
     * Get fe groups
     *
     * @return string
     */
    public function getFeGroup(): string
    {
        return $this->feGroup;
    }

    /**
     * Set fe group
     *
     * @param string $feGroup comma separated list
     *
     * @return void
     */
    public function setFeGroup($feGroup): void
    {
        $this->feGroup = $feGroup;
    }

    /**
     * Get import id
     *
     * @return string
     */
    public function getImportId(): string
    {
        return $this->importId;
    }

    /**
     * Set import id
     *
     * @param string $importId import id
     *
     * @return void
     */
    public function setImportId($importId): void
    {
        $this->importId = $importId;
    }

    /**
     * Get sorting
     *
     * @return int
     */
    public function getSorting(): int
    {
        return $this->sorting;
    }

    /**
     * Set sorting
     *
     * @param int $sorting sorting
     *
     * @return void
     */
    public function setSorting($sorting): void
    {
        $this->sorting = $sorting;
    }

    /**
     * Set importSource
     *
     * @param string $importSource
     *
     * @return void
     */
    public function setImportSource($importSource): void
    {
        $this->importSource = $importSource;
    }

    /**
     * Get importSource
     *
     * @return string
     */
    public function getImportSource(): string
    {
        return $this->importSource;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     *
     * @return void
     */
    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    /**
     * @return array
     */
    public function getFalMediaPreviews(): array
    {
        return $this->getMediaPreviews();
    }

    public function getFirstFalImagePreview(): ?\TYPO3\CMS\Extbase\Domain\Model\FileReference
    {
        return $this->getFirstPreview();
    }

    public function getFalMediaNonPreviews(): array
    {
        return $this->getMediaNonPreviews();
    }

    /**
     * @return array
     */
    public function getSortingForeign()
    {
        return $this->sortingForeign;
    }

    /**
     * @param array $sortingForeign
     */
    public function setSortingForeign($sortingForeign)
    {
        $this->sortingForeign = $sortingForeign;
    }

    /**
     * Return related items sorted by foreign sorting
     *
     * @return array
     */
    public function getRelatedFromSortedByForeign()
    {
        $items = $this->getRelated();
        if ($items) {
            $items = $items->toArray();
            usort($items, function ($a, $b) {
                return $a->getSortingForeign() < $b->getSortingForeign();
            });
        }
        return $items;
    }
}
