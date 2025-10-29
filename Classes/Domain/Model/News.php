<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\ORM\Transient;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * News model
 */
class News extends AbstractEntity
{
    /** @var \DateTime */
    protected $crdate;

    /** @var \DateTime */
    protected $tstamp;

    /** @var int */
    protected $sysLanguageUid = 0;

    /** @var int */
    protected $l10nParent = 0;

    /** @var \DateTime */
    protected $starttime;

    /** @var ?\DateTime */
    protected $endtime;

    /**
     * keep it as string as it should be only used during imports
     *
     * @var string
     */
    protected $feGroup = '';

    /** @var bool */
    protected $hidden = false;

    /** @var bool */
    protected $deleted = false;

    /** @var string */
    protected $title = '';

    /** @var string */
    protected $alternativeTitle = '';

    /** @var string */
    protected $teaser = '';

    /** @var string */
    protected $bodytext = '';

    /** @var \DateTime */
    protected $datetime;

    /** @var \DateTime */
    protected $archive;

    /** @var string */
    protected $author = '';

    /** @var string */
    protected $authorEmail = '';

    /** @var ObjectStorage<Category> */
    #[Lazy]
    protected ObjectStorage $categories;

    /** @var ObjectStorage<\GeorgRinger\News\Domain\Model\News> */
    #[Lazy]
    protected $related;

    /** @var ObjectStorage<\GeorgRinger\News\Domain\Model\News> */
    #[Lazy]
    protected $relatedFrom;

    /**
     * Fal related files
     *
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $falRelatedFiles;

    /** @var ObjectStorage<Link> */
    #[Lazy]
    protected ObjectStorage $relatedLinks;

    /** @var array */
    protected $sortingForeign;

    /** @var string */
    protected $type = '';

    /** @var string */
    protected $keywords = '';

    /** @var string */
    protected $description = '';

    /**
     * Fal media items
     *
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected $falMedia;

    /**
     * Fal media items with showinpreview set
     *
     * @var array
     */
    #[Transient]
    protected $falMediaPreviews;

    /**
     * Fal media items with showinpreview not set
     *
     * @var array
     */
    #[Transient]
    protected $falMediaNonPreviews;

    /** @var string */
    protected $internalurl = '';

    /** @var string */
    protected $externalurl = '';

    /** @var bool */
    protected $istopnews = false;

    /** @var ObjectStorage<TtContent> */
    #[Lazy]
    protected ObjectStorage $contentElements;

    /** @var ObjectStorage<Tag> */
    #[Lazy]
    protected ObjectStorage $tags;

    /** @var string */
    protected $pathSegment = '';

    /** @var int */
    protected $editlock = 0;

    /** @var string */
    protected $importId = '';

    /** @var string */
    protected $importSource = '';

    /** @var int */
    protected $sorting = 0;

    /** @var string */
    protected $notes = '';

    /**
     * Initialize relation objects
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
     * Called again with initialize object, as fetching an entity from the DB does not use the constructor
     */
    public function initializeObject(): void
    {
        $this->categories = $this->categories ?? new ObjectStorage();
        $this->contentElements = $this->contentElements ?? new ObjectStorage();
        $this->relatedLinks = $this->relatedLinks ?? new ObjectStorage();
        $this->falMedia = $this->falMedia ?? new ObjectStorage();
        $this->falRelatedFiles = $this->falRelatedFiles ?? new ObjectStorage();
        $this->tags = $this->tags ?? new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * Get alternative title
     */
    public function getAlternativeTitle(): string
    {
        return $this->alternativeTitle;
    }

    /**
     * Set alternative title
     *
     * @param string $alternativeTitle
     */
    public function setAlternativeTitle($alternativeTitle): void
    {
        $this->alternativeTitle = $alternativeTitle;
    }

    /**
     * Get Teaser text
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * Set Teaser text
     *
     * @param string $teaser teaser text
     */
    public function setTeaser($teaser): void
    {
        $this->teaser = $teaser;
    }

    public function getBodytext(): string
    {
        return $this->bodytext;
    }

    /**
     * @param string $bodytext main content
     */
    public function setBodytext($bodytext): void
    {
        $this->bodytext = $bodytext;
    }

    public function getDatetime(): ?DateTime
    {
        return $this->datetime;
    }

    /**
     * Set date time
     *
     * @param DateTime $datetime datetime
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
     */
    public function getDayOfDatetime(): int
    {
        return (int)$this->getDatetime()->format('d');
    }

    /**
     * Get archive date
     */
    public function getArchive(): ?DateTime
    {
        return $this->archive;
    }

    /**
     * Set archive date
     *
     * @param DateTime $archive archive date
     */
    public function setArchive($archive): void
    {
        $this->archive = $archive;
    }

    /**
     * Get year of archive date
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
     */
    public function getDayOfArchive(): int
    {
        if ($this->archive) {
            return (int)$this->archive->format('d');
        }
        return 0;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * Get author's email
     */
    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }

    /**
     * Set author's email
     *
     * @param string $authorEmail author's email
     */
    public function setAuthorEmail($authorEmail): void
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * @return ObjectStorage<Category>|null
     */
    public function getCategories(): ?ObjectStorage
    {
        return $this->categories;
    }

    /**
     * Get first category
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
     * @param ObjectStorage<Category> $categories
     */
    public function setCategories($categories): void
    {
        $this->categories = $categories;
    }

    /**
     * Adds a category to this categories.
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
     * @param ObjectStorage<\GeorgRinger\News\Domain\Model\News> $relatedFrom
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
     */
    public function getRelatedFromSorted(): array
    {
        $items = $this->getRelatedFrom();
        if ($items) {
            $items = $items->toArray();
            usort($items, function ($a, $b): int {
                return $b->getDatetime() <=> $a->getDatetime();
            });
        }
        return $items;
    }

    /**
     * Return related from items sorted by datetime
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
            usort($all, function ($a, $b): int {
                return $b->getDatetime() <=> $a->getDatetime();
            });
        }
        return $all;
    }

    /**
     * Return related items sorted by datetime
     */
    public function getRelatedSorted(): array
    {
        $items = $this->getRelated();
        if ($items) {
            $items = $items->toArray();
            usort($items, function ($a, $b): int {
                return $b->getDatetime() <=> $a->getDatetime();
            });
        }
        return $items;
    }

    /**
     * Set related news
     *
     * @param ObjectStorage $related related news
     */
    public function setRelated($related): void
    {
        $this->related = $related;
    }

    /**
     * Get related links
     *
     * @return ObjectStorage<Link>|null
     */
    public function getRelatedLinks(): ?ObjectStorage
    {
        return $this->relatedLinks;
    }

    /**
     * Get FAL related files
     *
     * @return ObjectStorage<FileReference>|null
     */
    public function getFalRelatedFiles(): ?ObjectStorage
    {
        return $this->falRelatedFiles;
    }

    /**
     * Short method for getFalRelatedFiles
     *
     * @return ObjectStorage<FileReference>|null
     */
    public function getRelatedFiles(): ?ObjectStorage
    {
        return $this->getFalRelatedFiles();
    }

    /**
     * Set FAL related files
     *
     * @param ObjectStorage<FileReference> $falRelatedFiles FAL related files
     */
    public function setFalRelatedFiles($falRelatedFiles): void
    {
        $this->falRelatedFiles = $falRelatedFiles;
    }

    /**
     * Adds a file to this files.
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
     * @param ObjectStorage<Link> $relatedLinks related links relation
     */
    public function setRelatedLinks($relatedLinks): void
    {
        $this->relatedLinks = $relatedLinks;
    }

    /**
     * Get type of news
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set type of news
     *
     * @param string $type type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords keywords
     */
    public function setKeywords($keywords): void
    {
        $this->keywords = $keywords;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Adds a related link.
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
     * @return ObjectStorage<FileReference>|null
     */
    public function getFalMedia(): ?ObjectStorage
    {
        return $this->falMedia;
    }

    /**
     * Short method for getFalMedia()
     *
     * @return ObjectStorage<FileReference>|null
     */
    public function getMedia(): ?ObjectStorage
    {
        return $this->getFalMedia();
    }

    /**
     * Set Fal media relation
     *
     * @param ObjectStorage<FileReference> $falMedia
     */
    public function setFalMedia(ObjectStorage $falMedia): void
    {
        $this->falMedia = $falMedia;
    }

    /**
     * Add a Fal media file reference
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
     */
    public function getMediaPreviews(): array
    {
        $configuration = [FileReference::VIEW_LIST_AND_DETAIL, FileReference::VIEW_LIST_ONLY];
        return $this->getMediaItemsByConfiguration($configuration);
    }

    /**
     * Get all media elements which are allowed for detail views
     */
    public function getMediaNonPreviews(): array
    {
        $configuration = [FileReference::VIEW_LIST_AND_DETAIL, FileReference::VIEW_DETAIL_ONLY];
        return $this->getMediaItemsByConfiguration($configuration);
    }

    /**
     * Get all media elements which are only for list views
     */
    public function getMediaListOnly(): array
    {
        $configuration = [FileReference::VIEW_LIST_ONLY];
        return $this->getMediaItemsByConfiguration($configuration);
    }

    /**
     * Get all media elements which are only for detail views
     */
    public function getMediaDetailOnly(): array
    {
        $configuration = [FileReference::VIEW_DETAIL_ONLY];
        return $this->getMediaItemsByConfiguration($configuration);
    }

    /**
     * Get first preview
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
     */
    public function getFirstNonePreview(): ?\TYPO3\CMS\Extbase\Domain\Model\FileReference
    {
        foreach ($this->getMediaNonPreviews() as $mediaElement) {
            return $mediaElement;
        }
        return null;
    }

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
     */
    public function getInternalurl(): string
    {
        return $this->internalurl;
    }

    /**
     * Set internal url
     *
     * @param string $internalUrl internal url
     */
    public function setInternalurl($internalUrl): void
    {
        $this->internalurl = $internalUrl;
    }

    /**
     * Get external url
     */
    public function getExternalurl(): string
    {
        return $this->externalurl;
    }

    /**
     * Set external url
     *
     * @param string $externalUrl external url
     */
    public function setExternalurl($externalUrl): void
    {
        $this->externalurl = $externalUrl;
    }

    /**
     * Get top news flag
     */
    public function getIstopnews(): bool
    {
        return $this->istopnews;
    }

    /**
     * Set top news flag
     *
     * @param bool $istopnews top news flag
     */
    public function setIstopnews($istopnews): void
    {
        $this->istopnews = $istopnews;
    }

    /**
     * Get content elements
     *
     * @return ObjectStorage<TtContent>
     */
    public function getContentElements(): ?ObjectStorage
    {
        return $this->contentElements;
    }

    /**
     * Set content element list
     *
     * @param ObjectStorage<TtContent> $contentElements content elements
     */
    public function setContentElements($contentElements): void
    {
        $this->contentElements = $contentElements;
    }

    /**
     * Adds a content element to the record
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
     */
    public function getContentElementIdList(): string
    {
        return $this->getIdOfContentElements();
    }

    /**
     * Get translated id list of content elements
     */
    public function getTranslatedContentElementIdList(): string
    {
        return $this->getIdOfContentElements(false);
    }

    /**
     * Get id list of non-nested content elements
     */
    public function getNonNestedContentElementIdList(): string
    {
        return $this->getIdOfNonNestedContentElements();
    }

    /**
     * Get translated id list of non-nested content elements
     */
    public function getTranslatedNonNestedContentElementIdList(): string
    {
        return $this->getIdOfNonNestedContentElements(false);
    }

    /**
     * Collect id list
     *
     * @param bool $original
     */
    protected function getIdOfContentElements($original = true): string
    {
        $idList = [];
        $contentElements = $this->getContentElements();
        if ($contentElements) {
            foreach ($contentElements as $contentElement) {
                if ($contentElement->getColPos() >= 0 && $contentElement->getSysLanguageUid() === $this->getSysLanguageUid()) {
                    $idList[] = $original ? $contentElement->getUid() : $contentElement->_getProperty('_localizedUid');
                }
            }
        }
        return implode(',', $idList);
    }

    /**
     * Collect id list of non-nested content elements
     * Currently only supports container elements of EXT:container
     *
     * @param bool $original
     */
    protected function getIdOfNonNestedContentElements($original = true): string
    {
        $idList = [];
        $contentElements = $this->getContentElements();
        if ($contentElements) {
            foreach ($contentElements as $contentElement) {
                if ($contentElement->getColPos() >= 0 && $contentElement->getTxContainerParent() === 0 && $contentElement->getSysLanguageUid() === $this->getSysLanguageUid()) {
                    $idList[] = $original ? $contentElement->getUid() : $contentElement->_getProperty('_localizedUid');
                }
            }
        }

        return implode(',', $idList);
    }

    /**
     * @return ObjectStorage<Tag>|null
     */
    public function getTags(): ?ObjectStorage
    {
        return $this->tags;
    }

    /**
     * @param ObjectStorage<Tag> $tags tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    /**
     * Adds a tag
     */
    public function addTag(Tag $tag): void
    {
        $this->tags->attach($tag);
    }

    /**
     * Removes a tag
     */
    public function removeTag(Tag $tag): void
    {
        $this->tags->detach($tag);
    }

    /**
     * Get path segment
     */
    public function getPathSegment(): string
    {
        return $this->pathSegment;
    }

    /**
     * Set path segment
     *
     * @param string $pathSegment
     */
    public function setPathSegment($pathSegment): void
    {
        $this->pathSegment = $pathSegment;
    }

    /**
     * Get creation date
     */
    public function getCrdate(): DateTime
    {
        return $this->crdate;
    }

    /**
     * Set creation date
     *
     * @param DateTime $crdate
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
     */
    public function getDayOfCrdate(): int
    {
        return (int)$this->crdate->format('d');
    }

    public function getTstamp(): DateTime
    {
        return $this->tstamp;
    }

    /**
     * Set time stamp
     *
     * @param DateTime $tstamp time stamp
     */
    public function setTstamp($tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Set sys language
     *
     * @param int $sysLanguageUid
     */
    public function setSysLanguageUid($sysLanguageUid): void
    {
        $this->_languageUid = $sysLanguageUid;
    }

    /**
     * Get sys language
     */
    public function getSysLanguageUid(): int
    {
        return $this->_languageUid;
    }

    /**
     * Set l10n parent
     *
     * @param int $l10nParent
     */
    public function setL10nParent($l10nParent): void
    {
        $this->l10nParent = $l10nParent;
    }

    /**
     * Get l10n parent
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
     */
    public function getDayOfTimestamp(): int
    {
        return (int)$this->tstamp->format('d');
    }

    /**
     * Get editlock flag
     */
    public function getEditlock(): int
    {
        return $this->editlock;
    }

    /**
     * Set edit lock flag
     *
     * @param int $editlock editlock flag
     */
    public function setEditlock($editlock): void
    {
        $this->editlock = $editlock;
    }

    /**
     * Get hidden flag
     */
    public function getHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * Set hidden flag
     *
     * @param bool $hidden hidden flag
     */
    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    /**
     * Get deleted flag
     */
    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Set deleted flag
     *
     * @param bool $deleted deleted flag
     */
    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * Get start time
     */
    public function getStarttime(): ?DateTime
    {
        return $this->starttime;
    }

    /**
     * Set start time
     *
     * @param DateTime $starttime start time
     */
    public function setStarttime($starttime): void
    {
        $this->starttime = $starttime;
    }

    /**
     * Get year of starttime
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
     */
    public function getDayOfStarttime(): int
    {
        if ($this->starttime) {
            return (int)$this->starttime->format('d');
        }
        return 0;
    }

    public function getEndtime(): ?DateTime
    {
        return $this->endtime;
    }

    /**
     * Set end time
     *
     * @param DateTime $endtime end time
     */
    public function setEndtime($endtime): void
    {
        $this->endtime = $endtime;
    }

    /**
     * Get year of endtime
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
     */
    public function getFeGroup(): string
    {
        return $this->feGroup;
    }

    /**
     * Set fe group
     *
     * @param string $feGroup comma separated list
     */
    public function setFeGroup($feGroup): void
    {
        $this->feGroup = $feGroup;
    }

    /**
     * Get import id
     */
    public function getImportId(): string
    {
        return $this->importId;
    }

    /**
     * Set import id
     *
     * @param string $importId import id
     */
    public function setImportId($importId): void
    {
        $this->importId = $importId;
    }

    public function getSorting(): int
    {
        return $this->sorting;
    }

    /**
     * @param int $sorting sorting
     */
    public function setSorting($sorting): void
    {
        $this->sorting = $sorting;
    }

    /**
     * @param string $importSource
     */
    public function setImportSource($importSource): void
    {
        $this->importSource = $importSource;
    }

    public function getImportSource(): string
    {
        return $this->importSource;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

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
            usort($items, function ($a, $b): int {
                return $b->getSortingForeign() <=> $a->getSortingForeign();
            });
        }
        return $items;
    }
}
