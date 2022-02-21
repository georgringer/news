<?php

namespace GeorgRinger\News\Domain\Model;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * File Reference
 */
class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference
{
    const VIEW_DETAIL_ONLY = 0;
    const VIEW_LIST_AND_DETAIL = 1;
    const VIEW_LIST_ONLY = 2;

    /**
     * Obsolete when foreign_selector is supported by ExtBase persistence layer
     *
     * @var int
     */
    protected $uidLocal = 0;

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $alternative = '';

    /**
     * @var string
     */
    protected $link = '';

    /**
     * @var int
     */
    protected $showinpreview = 0;

    /**
     * Set File uid
     *
     * @param int $fileUid
     *
     * @return void
     */
    public function setFileUid($fileUid): void
    {
        $this->uidLocal = $fileUid;
    }

    /**
     * Get File UID
     *
     * @return int
     */
    public function getFileUid(): int
    {
        return $this->uidLocal;
    }

    /**
     * Set alternative
     *
     * @param string $alternative
     *
     * @return void
     */
    public function setAlternative($alternative): void
    {
        $this->alternative = $alternative;
    }

    /**
     * Get alternative
     *
     * @return string
     */
    public function getAlternative(): string
    {
        return (string)($this->alternative !== '' ? $this->alternative : $this->getOriginalResource()->getAlternative());
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return void
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return (string)($this->description !== '' ? $this->description : $this->getOriginalResource()->getDescription());
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return void
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return mixed
     */
    public function getLink()
    {
        return (string)($this->link !== '' ? $this->link : $this->getOriginalResource()->getLink());
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return (string)($this->title !== '' ? $this->title : $this->getOriginalResource()->getTitle());
    }

    /**
     * Set showinpreview
     *
     * @param int $showinpreview
     *
     * @return void
     */
    public function setShowinpreview($showinpreview): void
    {
        $this->showinpreview = $showinpreview;
    }

    /**
     * Get showinpreview
     *
     * @return int
     */
    public function getShowinpreview(): int
    {
        return $this->showinpreview;
    }
}
