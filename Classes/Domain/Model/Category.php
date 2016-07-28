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

/**
 * Category Model
 *
 */
class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var int
     */
    protected $sorting;

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
    protected $hidden;

    /**
     * @var \DateTime
     */
    protected $endtime;

    /**
     * @var int
     */
    protected $sysLanguageUid;

    /**
     * @var int
     */
    protected $l10nParent;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \GeorgRinger\News\Domain\Model\Category
     * @lazy
     */
    protected $parentcategory;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\FileReference>
     * @lazy
     */
    protected $images;

    /**
     * @var int
     */
    protected $shortcut;

    /**
     * @var int
     */
    protected $singlePid;

    /**
     * @var string
     */
    protected $importId;

    /**
     * @var string
     */
    protected $importSource;

    /**
     * keep it as string as it should be only used during imports
     * @var string
     */
    protected $feGroup;

    /**
     * @var string
     */
    protected $seoTitle;

    /**
     * @var string
     */
    protected $seoDescription;

    /**
     * @var string
     */
    protected $seoHeadline;

    /**
     * @var string
     */
    protected $seoText;

    /**
     * Initialize images
     *
     * @return \GeorgRinger\News\Domain\Model\Category
     */
    public function __construct()
    {
        $this->images = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Get creation date
     *
     * @return \DateTime
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Set Creation Date
     *
     * @param \DateTime $crdate crdate
     * @return void
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * Get Tstamp
     *
     * @return \DateTime
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Set tstamp
     *
     * @param \DateTime $tstamp tstamp
     * @return void
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Get starttime
     *
     * @return \DateTime
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Set starttime
     *
     * @param \DateTime $starttime starttime
     * @return void
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;
    }

    /**
     * Get Endtime
     *
     * @return \DateTime
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Set Endtime
     *
     * @param \DateTime $endtime endttime
     * @return void
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;
    }

    /**
     * Get Hidden
     *
     * @return bool
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set Hidden
     *
     * @param bool $hidden
     * @return void
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Get sys language
     *
     * @return int
     */
    public function getSysLanguageUid()
    {
        return $this->_languageUid;
    }

    /**
     * Set sys language
     *
     * @param int $sysLanguageUid language uid
     * @return void
     */
    public function setSysLanguageUid($sysLanguageUid)
    {
        $this->_languageUid = $sysLanguageUid;
    }

    /**
     * Get language parent
     *
     * @return int
     */
    public function getL10nParent()
    {
        return $this->l10nParent;
    }

    /**
     * Set language parent
     *
     * @param int $l10nParent l10nParent
     * @return void
     */
    public function setL10nParent($l10nParent)
    {
        $this->l10nParent = $l10nParent;
    }

    /**
     * Get category title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set category title
     *
     * @param string $title title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\FileReference>
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add image
     *
     * @param FileReference $image
     */
    public function addImage(FileReference $image)
    {
        $this->images->attach($image);
    }

    /**
     * Remove image
     *
     * @param FileReference $image
     */
    public function removeImage(FileReference $image)
    {
        $this->images->detach($image);
    }

    /**
     * Get the first image
     *
     * @return FileReference|null
     */
    public function getFirstImage()
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
    public function getParentcategory()
    {
        return $this->parentcategory;
    }

    /**
     * Set parent category
     *
     * @param \GeorgRinger\News\Domain\Model\Category $category parent category
     * @return void
     */
    public function setParentcategory(Category $category)
    {
        $this->parentcategory = $category;
    }

    /**
     * Get shortcut
     *
     * @return int
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }

    /**
     * Set shortcut
     *
     * @param int $shortcut shortcut
     * @return void
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;
    }

    /**
     * Get single pid of category
     *
     * @return int
     */
    public function getSinglePid()
    {
        return $this->singlePid;
    }

    /**
     * Set single pid
     *
     * @param int $singlePid single pid
     * @return void
     */
    public function setSinglePid($singlePid)
    {
        $this->singlePid = $singlePid;
    }

    /**
     * Get import id
     *
     * @return string
     */
    public function getImportId()
    {
        return $this->importId;
    }

    /**
     * Set import id
     *
     * @param string $importId import id
     * @return void
     */
    public function setImportId($importId)
    {
        $this->importId = $importId;
    }

    /**
     * Get sorting id
     *
     * @return int sorting id
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Set sorting id
     *
     * @param int $sorting sorting id
     * @return void
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }

    /**
     * Get feGroup
     *
     * @return string
     */
    public function getFeGroup()
    {
        return $this->feGroup;
    }

    /**
     * Get feGroup
     *
     * @param string $feGroup feGroup
     * @return void
     */
    public function setFeGroup($feGroup)
    {
        $this->feGroup = $feGroup;
    }

    /**
     * Set importSource
     *
     * @param  string $importSource
     * @return void
     */
    public function setImportSource($importSource)
    {
        $this->importSource = $importSource;
    }

    /**
     * Get importSource
     *
     * @return string
     */
    public function getImportSource()
    {
        return $this->importSource;
    }

    /**
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * @param string $seoTitle
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }

    /**
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * @param string $seoDescription
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * @return string
     */
    public function getSeoHeadline()
    {
        return $this->seoHeadline;
    }

    /**
     * @param string $seoHeadline
     */
    public function setSeoHeadline($seoHeadline)
    {
        $this->seoHeadline = $seoHeadline;
    }

    /**
     * @return string
     */
    public function getSeoText()
    {
        return $this->seoText;
    }

    /**
     * @param string $seoText
     */
    public function setSeoText($seoText)
    {
        $this->seoText = $seoText;
    }
}
