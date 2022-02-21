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
     * @var bool
     */
    protected $hidden = false;

    /**
     * @var \DateTime
     */
    protected $endtime;

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

    /** @var string */
    protected $slug = '';

    /**
     * Initialize images
     *
     * @return \GeorgRinger\News\Domain\Model\Category
     */
    public function __construct()
    {
        $this->images = new ObjectStorage();
    }

    /**
     * Get creation date
     *
     * @return null|\DateTime
     */
    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }

    /**
     * Set Creation Date
     *
     * @param \DateTime $crdate crdate
     *
     * @return void
     */
    public function setCrdate($crdate): void
    {
        $this->crdate = $crdate;
    }

    /**
     * Get Tstamp
     *
     * @return null|\DateTime
     */
    public function getTstamp(): ?\DateTime
    {
        return $this->tstamp;
    }

    /**
     * Set tstamp
     *
     * @param \DateTime $tstamp tstamp
     *
     * @return void
     */
    public function setTstamp($tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Get starttime
     *
     * @return \DateTime
     */
    public function getStarttime(): ?\DateTime
    {
        return $this->starttime;
    }

    /**
     * Set starttime
     *
     * @param \DateTime $starttime starttime
     *
     * @return void
     */
    public function setStarttime($starttime): void
    {
        $this->starttime = $starttime;
    }

    /**
     * Get Endtime
     *
     * @return null|\DateTime
     */
    public function getEndtime(): ?\DateTime
    {
        return $this->endtime;
    }

    /**
     * Set Endtime
     *
     * @param \DateTime $endtime endttime
     *
     * @return void
     */
    public function setEndtime($endtime): void
    {
        $this->endtime = $endtime;
    }

    /**
     * Get Hidden
     *
     * @return bool
     */
    public function getHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * Set Hidden
     *
     * @param bool $hidden
     *
     * @return void
     */
    public function setHidden($hidden): void
    {
        $this->hidden = $hidden;
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
     * Set sys language
     *
     * @param int $sysLanguageUid language uid
     *
     * @return void
     */
    public function setSysLanguageUid($sysLanguageUid): void
    {
        $this->_languageUid = $sysLanguageUid;
    }

    /**
     * Get language parent
     *
     * @return int
     */
    public function getL10nParent(): int
    {
        return $this->l10nParent;
    }

    /**
     * Set language parent
     *
     * @param int $l10nParent l10nParent
     *
     * @return void
     */
    public function setL10nParent($l10nParent): void
    {
        $this->l10nParent = $l10nParent;
    }

    /**
     * Get category title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set category title
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
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     *
     * @return void
     */
    public function setImages($images): void
    {
        $this->images = $images;
    }

    /**
     * @return ObjectStorage
     *
     * @psalm-return ObjectStorage<FileReference>
     */
    public function getImages(): ObjectStorage
    {
        return $this->images;
    }

    /**
     * Add image
     *
     * @param FileReference $image
     *
     * @return void
     */
    public function addImage(FileReference $image): void
    {
        $this->images->attach($image);
    }

    /**
     * Remove image
     *
     * @param FileReference $image
     *
     * @return void
     */
    public function removeImage(FileReference $image): void
    {
        $this->images->detach($image);
    }

    /**
     * Get the first image
     *
     * @return FileReference|null
     */
    public function getFirstImage(): ?FileReference
    {
        $images = $this->getImages();
        foreach ($images as $image) {
            return $image;
        }

        return null;
    }

    /**
     * Get parent category
     *
     * @return \GeorgRinger\News\Domain\Model\Category
     */
    public function getParentcategory(): ?\GeorgRinger\News\Domain\Model\Category
    {
        return $this->parentcategory instanceof LazyLoadingProxy ? $this->parentcategory->_loadRealInstance() : $this->parentcategory;
    }

    /**
     * Set parent category
     *
     * @param \GeorgRinger\News\Domain\Model\Category $category parent category
     *
     * @return void
     */
    public function setParentcategory($category): void
    {
        $this->parentcategory = $category;
    }

    /**
     * Get shortcut
     *
     * @return int
     */
    public function getShortcut(): int
    {
        return $this->shortcut;
    }

    /**
     * Set shortcut
     *
     * @param int $shortcut shortcut
     *
     * @return void
     */
    public function setShortcut($shortcut): void
    {
        $this->shortcut = $shortcut;
    }

    /**
     * Get single pid of category
     *
     * @return int
     */
    public function getSinglePid(): int
    {
        return $this->singlePid;
    }

    /**
     * Set single pid
     *
     * @param int $singlePid single pid
     *
     * @return void
     */
    public function setSinglePid($singlePid): void
    {
        $this->singlePid = $singlePid;
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
     * Get sorting id
     *
     * @return int sorting id
     */
    public function getSorting(): int
    {
        return $this->sorting;
    }

    /**
     * Set sorting id
     *
     * @param int $sorting sorting id
     *
     * @return void
     */
    public function setSorting($sorting): void
    {
        $this->sorting = $sorting;
    }

    /**
     * Get feGroup
     *
     * @return string
     */
    public function getFeGroup(): string
    {
        return $this->feGroup;
    }

    /**
     * Get feGroup
     *
     * @param string $feGroup feGroup
     *
     * @return void
     */
    public function setFeGroup($feGroup): void
    {
        $this->feGroup = $feGroup;
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
    public function getSeoTitle(): string
    {
        return $this->seoTitle;
    }

    /**
     * @param string $seoTitle
     *
     * @return void
     */
    public function setSeoTitle($seoTitle): void
    {
        $this->seoTitle = $seoTitle;
    }

    /**
     * @return string
     */
    public function getSeoDescription(): string
    {
        return $this->seoDescription;
    }

    /**
     * @param string $seoDescription
     *
     * @return void
     */
    public function setSeoDescription($seoDescription): void
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * @return string
     */
    public function getSeoHeadline(): string
    {
        return $this->seoHeadline;
    }

    /**
     * @param string $seoHeadline
     *
     * @return void
     */
    public function setSeoHeadline($seoHeadline): void
    {
        $this->seoHeadline = $seoHeadline;
    }

    /**
     * @return string
     */
    public function getSeoText(): string
    {
        return $this->seoText;
    }

    /**
     * @param string $seoText
     *
     * @return void
     */
    public function setSeoText($seoText): void
    {
        $this->seoText = $seoText;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return void
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }
}
