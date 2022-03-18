<?php

namespace GeorgRinger\News\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Category Model
 */
class Category extends AbstractEntity
{

    /**
     * @var int
     */
    protected $sorting = 0;

    /**
     * @var \DateTime
     */
    protected $crdate;

    /**
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * @var \DateTime
     */
    protected $starttime;

    /**
     * @var \DateTime
     */
    protected $endtime;

    /**
     * @var bool
     */
    protected $hidden = false;

    /**
     * @var int
     */
    protected $sysLanguageUid = 0;

    /**
     * @var int
     */
    protected $l10nParent = 0;

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var \GeorgRinger\News\Domain\Model\Category
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $parentcategory;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $images;

    /**
     * @var int
     */
    protected $shortcut = 0;

    /**
     * @var int
     */
    protected $singlePid = 0;

    /**
     * @var string
     */
    protected $importId = '';

    /**
     * @var string
     */
    protected $importSource = '';

    /**
     * keep it as string as it should be only used during imports
     * @var string
     */
    protected $feGroup = '';

    /**
     * @var string
     */
    protected $seoTitle = '';

    /**
     * @var string
     */
    protected $seoDescription = '';

    /**
     * @var string
     */
    protected $seoHeadline = '';

    /**
     * @var string
     */
    protected $seoText = '';

    /**
     * @var string
     */
    protected $slug = '';

    public function __construct()
    {
        $this->images = new ObjectStorage();
    }

    public function getSorting(): int
    {
        return $this->sorting;
    }

    public function setSorting(int $sorting): void
    {
        $this->sorting = $sorting;
    }

    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }

    public function setCrdate(\DateTime $crdate): void
    {
        $this->crdate = $crdate;
    }

    public function getTstamp(): ?\DateTime
    {
        return $this->tstamp;
    }

    public function setTstamp(\DateTime $tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    public function getStarttime(): ?\DateTime
    {
        return $this->starttime;
    }

    public function setStarttime(\DateTime $starttime): void
    {
        $this->starttime = $starttime;
    }

    public function getEndtime(): ?\DateTime
    {
        return $this->endtime;
    }

    public function setEndtime(\DateTime $endtime): void
    {
        $this->endtime = $endtime;
    }

    public function getHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    public function getSysLanguageUid(): int
    {
        // int cast is needed as $this->_languageUid is null by default
        return (int)$this->_languageUid;
    }

    public function setSysLanguageUid(int $sysLanguageUid): void
    {
        $this->_languageUid = $sysLanguageUid;
    }

    public function getL10nParent(): int
    {
        return $this->l10nParent;
    }

    public function setL10nParent(int $l10nParent): void
    {
        $this->l10nParent = $l10nParent;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getParentcategory(): ?\GeorgRinger\News\Domain\Model\Category
    {
        return $this->parentcategory instanceof LazyLoadingProxy
            ? $this->parentcategory->_loadRealInstance()
            : $this->parentcategory;
    }

    public function setParentcategory(\GeorgRinger\News\Domain\Model\Category $category): void
    {
        $this->parentcategory = $category;
    }

    /**
     * @psalm-return ObjectStorage<FileReference>
     */
    public function getImages(): ObjectStorage
    {
        return $this->images;
    }

    public function setImages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $images): void
    {
        $this->images = $images;
    }

    public function addImage(FileReference $image): void
    {
        $this->images->attach($image);
    }

    public function removeImage(FileReference $image): void
    {
        $this->images->detach($image);
    }

    public function getFirstImage(): ?FileReference
    {
        $images = $this->getImages();
        $images->rewind();

        return $images->valid() ? $images->current() : null;
    }

    public function getShortcut(): int
    {
        return $this->shortcut;
    }

    public function setShortcut(int $shortcut): void
    {
        $this->shortcut = $shortcut;
    }

    public function getSinglePid(): int
    {
        return $this->singlePid;
    }

    public function setSinglePid(int $singlePid): void
    {
        $this->singlePid = $singlePid;
    }

    public function getImportId(): string
    {
        return $this->importId;
    }

    public function setImportId(string $importId): void
    {
        $this->importId = $importId;
    }

    public function getImportSource(): string
    {
        return $this->importSource;
    }

    public function setImportSource(string $importSource): void
    {
        $this->importSource = $importSource;
    }

    public function getFeGroup(): string
    {
        return $this->feGroup;
    }

    public function setFeGroup(string $feGroup): void
    {
        $this->feGroup = $feGroup;
    }

    public function getSeoTitle(): string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(string $seoTitle): void
    {
        $this->seoTitle = $seoTitle;
    }

    public function getSeoDescription(): string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(string $seoDescription): void
    {
        $this->seoDescription = $seoDescription;
    }

    public function getSeoHeadline(): string
    {
        return $this->seoHeadline;
    }

    public function setSeoHeadline(string $seoHeadline): void
    {
        $this->seoHeadline = $seoHeadline;
    }

    public function getSeoText(): string
    {
        return $this->seoText;
    }

    public function setSeoText(string $seoText): void
    {
        $this->seoText = $seoText;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
