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

    /**
     * Initialize images
     */
    public function __construct()
    {
        $this->images = new ObjectStorage();
    }

    /**
     * Get sorting
     */
    public function getSorting(): int
    {
        return $this->sorting;
    }

    /**
     * Set sorting
     *
     * @param int $sorting
     */
    public function setSorting($sorting): void
    {
        $this->sorting = (int)$sorting;
    }

    /**
     * Get creation date
     */
    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }

    /**
     * Set creation date
     *
     * @param \DateTime $crdate
     */
    public function setCrdate($crdate): void
    {
        if ($crdate instanceof \DateTime) {
            $this->crdate = $crdate;
        }
    }

    /**
     * Get tstamp
     */
    public function getTstamp(): ?\DateTime
    {
        return $this->tstamp;
    }

    /**
     * Set tstamp
     *
     * @param \DateTime $tstamp
     */
    public function setTstamp($tstamp): void
    {
        if ($tstamp instanceof \DateTime) {
            $this->tstamp = $tstamp;
        }
    }

    /**
     * Get starttime
     */
    public function getStarttime(): ?\DateTime
    {
        return $this->starttime;
    }

    /**
     * Set starttime
     *
     * @param \DateTime $starttime
     */
    public function setStarttime($starttime): void
    {
        if ($starttime instanceof \DateTime) {
            $this->starttime = $starttime;
        }
    }

    /**
     * Get endtime
     */
    public function getEndtime(): ?\DateTime
    {
        return $this->endtime;
    }

    /**
     * Set endtime
     *
     * @param \DateTime $endtime
     */
    public function setEndtime($endtime): void
    {
        if ($endtime instanceof \DateTime) {
            $this->endtime = $endtime;
        }
    }

    /**
     * Get Hidden
     */
    public function getHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * Set Hidden
     *
     * @param bool $hidden
     */
    public function setHidden($hidden): void
    {
        $this->hidden = (bool)$hidden;
    }

    /**
     * Get sys_language_uid
     */
    public function getSysLanguageUid(): int
    {
        // int cast is needed as $this->_languageUid is null by default
        return (int)$this->_languageUid;
    }

    /**
     * Set sys_language_uid
     *
     * @param int $sysLanguageUid
     */
    public function setSysLanguageUid($sysLanguageUid): void
    {
        $this->_languageUid = (int)$sysLanguageUid;
    }

    /**
     * Get language parent
     */
    public function getL10nParent(): int
    {
        return $this->l10nParent;
    }

    /**
     * Set language parent
     *
     * @param int $l10nParent
     */
    public function setL10nParent($l10nParent): void
    {
        $this->l10nParent = $l10nParent;
    }

    /**
     * Get category title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set category title
     *
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * Get description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Get parent category
     */
    public function getParentcategory(): ?\GeorgRinger\News\Domain\Model\Category
    {
        return $this->parentcategory instanceof LazyLoadingProxy
            ? $this->parentcategory->_loadRealInstance()
            : $this->parentcategory;
    }

    /**
     * Set parent category
     *
     * @param \GeorgRinger\News\Domain\Model\Category $category
     */
    public function setParentcategory($category): void
    {
        $this->parentcategory = $category;
    }

    /**
     * Get images
     *
     * @psalm-return ObjectStorage<FileReference>
     */
    public function getImages(): ObjectStorage
    {
        return $this->images;
    }

    /**
     * Set images
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     */
    public function setImages($images): void
    {
        $this->images = $images;
    }

    /**
     * Add FileReference to images
     *
     * @param FileReference $image
     */
    public function addImage(FileReference $image): void
    {
        $this->images->attach($image);
    }

    /**
     * Remove FileReference from images
     */
    public function removeImage(FileReference $image): void
    {
        $this->images->detach($image);
    }

    /**
     * Get the first image
     */
    public function getFirstImage(): ?FileReference
    {
        $images = $this->getImages();
        $images->rewind();

        return $images->valid() ? $images->current() : null;
    }

    /**
     * Get shortcut
     */
    public function getShortcut(): int
    {
        return $this->shortcut;
    }

    /**
     * Set shortcut
     *
     * @param int $shortcut
     */
    public function setShortcut($shortcut): void
    {
        $this->shortcut = (int)$shortcut;
    }

    /**
     * Get single pid of category
     */
    public function getSinglePid(): int
    {
        return $this->singlePid;
    }

    /**
     * Set single pid
     *
     * @param int $singlePid
     */
    public function setSinglePid($singlePid): void
    {
        $this->singlePid = (int)$singlePid;
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
     * @param string $importId
     */
    public function setImportId($importId): void
    {
        $this->importId = $importId;
    }

    /**
     * Get import source
     */
    public function getImportSource(): string
    {
        return $this->importSource;
    }

    /**
     * Set import source
     *
     * @param string $importSource
     */
    public function setImportSource($importSource): void
    {
        $this->importSource = $importSource;
    }

    /**
     * Get frontend group
     */
    public function getFeGroup(): string
    {
        return $this->feGroup;
    }

    /**
     * Set frontend group
     *
     * @param string $feGroup
     */
    public function setFeGroup($feGroup): void
    {
        $this->feGroup = (string)$feGroup;
    }

    /**
     * Get SEO title
     */
    public function getSeoTitle(): string
    {
        return $this->seoTitle;
    }

    /**
     * Set SEO title
     *
     * @param string $seoTitle
     */
    public function setSeoTitle($seoTitle): void
    {
        $this->seoTitle = (string)$seoTitle;
    }

    /**
     * Get SEO description
     */
    public function getSeoDescription(): string
    {
        return $this->seoDescription;
    }

    /**
     * Set SEO description
     *
     * @param string $seoDescription
     */
    public function setSeoDescription($seoDescription): void
    {
        $this->seoDescription = (string)$seoDescription;
    }

    /**
     * Get SEO headline
     */
    public function getSeoHeadline(): string
    {
        return $this->seoHeadline;
    }

    /**
     * Set SEO headline
     *
     * @param string $seoHeadline
     */
    public function setSeoHeadline($seoHeadline): void
    {
        $this->seoHeadline = (string)$seoHeadline;
    }

    /**
     * Get SEO text
     */
    public function getSeoText(): string
    {
        return $this->seoText;
    }

    /**
     * Set SEO text
     *
     * @param string $seoText
     */
    public function setSeoText($seoText): void
    {
        $this->seoText = (string)$seoText;
    }

    /**
     * Get slug
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = (string)$slug;
    }
}
