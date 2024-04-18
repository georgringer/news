<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Domain\Model;

/**
 * File Reference
 */
class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference
{
    public const VIEW_DETAIL_ONLY = 0;
    public const VIEW_LIST_AND_DETAIL = 1;
    public const VIEW_LIST_ONLY = 2;

    /** @var string */
    protected $title = '';

    /** @var string */
    protected $description = '';

    /** @var string */
    protected $alternative = '';

    /** @var string */
    protected $link = '';

    /** @var int */
    protected $showinpreview = 0;

    /**
     * This empty constructor is necessary so class is fully
     * extensible by other extensions that might want to define
     * an own __construct() method
     */
    public function __construct() {}

    /**
     * Set File uid
     *
     * @param int $fileUid
     */
    public function setFileUid(?int $fileUid): void
    {
        $this->uidLocal = $fileUid;
    }

    /**
     * Get File UID
     */
    public function getFileUid(): int
    {
        return $this->uidLocal;
    }

    /**
     * @param string $alternative
     */
    public function setAlternative($alternative): void
    {
        $this->alternative = $alternative;
    }

    public function getAlternative(): string
    {
        return (string)($this->alternative !== '' ? $this->alternative : $this->getOriginalResource()->getAlternative());
    }

    /**
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return (string)($this->description !== '' ? $this->description : $this->getOriginalResource()->getDescription());
    }

    /**
     * @param string $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    public function getLink(): string
    {
        return (string)($this->link !== '' ? $this->link : $this->getOriginalResource()->getLink());
    }

    /**
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return (string)($this->title !== '' ? $this->title : $this->getOriginalResource()->getTitle());
    }

    /**
     * @param int $showinpreview
     */
    public function setShowinpreview($showinpreview): void
    {
        $this->showinpreview = $showinpreview;
    }

    public function getShowinpreview(): int
    {
        return $this->showinpreview;
    }
}
