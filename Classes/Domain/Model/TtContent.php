<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Model of tt_content
 */
class TtContent extends AbstractEntity
{
    /** @var \DateTime */
    protected $crdate;

    /** @var \DateTime */
    protected $tstamp;

    /** @var string */
    protected $CType = '';

    /** @var string */
    protected $header = '';

    /** @var string */
    protected $headerPosition = '';

    /** @var string */
    protected $bodytext = '';

    /** @var int */
    protected $colPos = 0;

    /** @var ObjectStorage<FileReference> */
    #[Lazy]
    protected $image;

    /** @var int */
    protected $imagewidth = 0;

    /** @var int */
    protected $imageorient = 0;

    /** @var string */
    protected $imagecaption = '';

    /** @var int */
    protected $imagecols = 0;

    /** @var int */
    protected $imageborder = 0;

    /** @var ObjectStorage<FileReference> */
    #[Lazy]
    protected $media;

    /** @var string */
    protected $layout = '';

    /** @var int */
    protected $cols = 0;

    /** @var string */
    protected $subheader = '';

    /** @var string */
    protected $headerLink = '';

    /** @var string */
    protected $imageLink = '';

    /** @var string */
    protected $imageZoom = '';

    /** @var string */
    protected $altText = '';

    /** @var string */
    protected $titleText = '';

    /** @var string */
    protected $headerLayout = '';

    /** @var string */
    protected $listType = '';

    /** @var int */
    protected $txContainerParent = 0;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->image = $this->image ?? new ObjectStorage();
        $this->media = $this->media ?? new ObjectStorage();
    }

    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }

    /**
     * @param \DateTime $crdate
     */
    public function setCrdate($crdate): void
    {
        $this->crdate = $crdate;
    }

    public function getTstamp(): ?\DateTime
    {
        return $this->tstamp;
    }

    /**
     * @param \DateTime $tstamp
     */
    public function setTstamp($tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Get sys language
     */
    public function getSysLanguageUid(): int
    {
        return $this->_languageUid;
    }

    public function getCType(): string
    {
        return $this->CType;
    }

    /**
     * @param $ctype
     */
    public function setCType(string $ctype): void
    {
        $this->CType = $ctype;
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param $header
     */
    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    public function getHeaderPosition(): string
    {
        return $this->headerPosition;
    }

    /**
     * @param $headerPosition
     */
    public function setHeaderPosition(string $headerPosition): void
    {
        $this->headerPosition = $headerPosition;
    }

    public function getBodytext(): string
    {
        return $this->bodytext;
    }

    /**
     * @param $bodytext
     */
    public function setBodytext(string $bodytext): void
    {
        $this->bodytext = $bodytext;
    }

    /**
     * Get the colpos
     */
    public function getColPos(): int
    {
        return (int)$this->colPos;
    }

    /**
     * @param int $colPos
     */
    public function setColPos($colPos): void
    {
        $this->colPos = $colPos;
    }

    public function addImage(FileReference $image): void
    {
        $this->image = $this->getImage();
        $this->image->attach($image);
    }

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getImage(): ObjectStorage
    {
        if ($this->image instanceof LazyLoadingProxy) {
            $this->image->_loadRealInstance();
        }

        if ($this->image instanceof ObjectStorage) {
            return $this->image;
        }

        return $this->image = new ObjectStorage();
    }

    public function removeImage(FileReference $image): void
    {
        $this->image = $this->getImage();
        $this->image->detach($image);
    }

    /**
     * @param ObjectStorage<FileReference> $image
     */
    public function setImage(ObjectStorage $image): void
    {
        $this->image = $image;
    }

    public function getImagewidth(): int
    {
        return $this->imagewidth;
    }

    /**
     * @param $imagewidth
     */
    public function setImagewidth(int $imagewidth): void
    {
        $this->imagewidth = $imagewidth;
    }

    public function getImageorient(): int
    {
        return $this->imageorient;
    }

    /**
     * @param $imageorient
     */
    public function setImageorient(int $imageorient): void
    {
        $this->imageorient = $imageorient;
    }

    public function getImagecaption(): string
    {
        return $this->imagecaption;
    }

    /**
     * @param $imagecaption
     */
    public function setImagecaption(string $imagecaption): void
    {
        $this->imagecaption = $imagecaption;
    }

    public function getImagecols(): int
    {
        return $this->imagecols;
    }

    /**
     * @param $imagecols
     */
    public function setImagecols(int $imagecols): void
    {
        $this->imagecols = $imagecols;
    }

    public function getImageborder(): int
    {
        return $this->imageborder;
    }

    /**
     * @param $imageborder
     */
    public function setImageborder(int $imageborder): void
    {
        $this->imageborder = $imageborder;
    }

    public function addMedia(FileReference $media): void
    {
        $this->media = $this->getMedia();
        $this->media->attach($media);
    }

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getMedia(): ObjectStorage
    {
        if ($this->media instanceof LazyLoadingProxy) {
            $this->media->_loadRealInstance();
        }

        if ($this->media instanceof ObjectStorage) {
            return $this->media;
        }

        return $this->media = new ObjectStorage();
    }

    public function removeMedia(FileReference $media): void
    {
        $this->media = $this->getMedia();
        $this->media->detach($media);
    }

    /**
     * @param ObjectStorage<FileReference> $media
     */
    public function setMedia(ObjectStorage $media): void
    {
        $this->media = $media;
    }

    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * @param $layout
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function getCols(): int
    {
        return $this->cols;
    }

    /**
     * @param $cols
     */
    public function setCols(int $cols): void
    {
        $this->cols = $cols;
    }

    public function getSubheader(): string
    {
        return $this->subheader;
    }

    /**
     * @param $subheader
     */
    public function setSubheader(string $subheader): void
    {
        $this->subheader = $subheader;
    }

    public function getHeaderLink(): string
    {
        return $this->headerLink;
    }

    /**
     * @param $headerLink
     */
    public function setHeaderLink(string $headerLink): void
    {
        $this->headerLink = $headerLink;
    }

    public function getImageLink(): string
    {
        return $this->imageLink;
    }

    /**
     * @param $imageLink
     */
    public function setImageLink(string $imageLink): void
    {
        $this->imageLink = $imageLink;
    }

    public function getImageZoom(): string
    {
        return $this->imageZoom;
    }

    /**
     * @param $imageZoom
     */
    public function setImageZoom(string $imageZoom): void
    {
        $this->imageZoom = $imageZoom;
    }

    public function getAltText(): string
    {
        return $this->altText;
    }

    /**
     * @param $altText
     */
    public function setAltText(string $altText): void
    {
        $this->altText = $altText;
    }

    public function getTitleText(): string
    {
        return $this->titleText;
    }

    /**
     * @param $titleText
     */
    public function setTitleText(string $titleText): void
    {
        $this->titleText = $titleText;
    }

    public function getHeaderLayout(): string
    {
        return $this->headerLayout;
    }

    /**
     * @param $headerLayout
     */
    public function setHeaderLayout(string $headerLayout): void
    {
        $this->headerLayout = $headerLayout;
    }

    public function getListType(): string
    {
        return $this->listType;
    }

    public function setListType(string $listType): void
    {
        $this->listType = $listType;
    }

    public function getTxContainerParent(): int
    {
        return $this->txContainerParent;
    }

    public function setTxContainerParent(int $txContainerParent): void
    {
        $this->txContainerParent = $txContainerParent;
    }
}
