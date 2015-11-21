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
 * Media model
 *
 */
class Media extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    const MEDIA_TYPE_IMAGE = 0;
    const MEDIA_TYPE_MULTIMEDIA = 1;
    const MEDIA_TYPE_HTML = 2;

    /**
     * @var \DateTime
     */
    protected $crdate;

    /**
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * @var bool
     */
    protected $hidden;

    /**
     * @var bool
     */
    protected $deleted;

    /**
     * @var int
     */
    protected $cruserId;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $caption;

    /**
     * @var string
     */
    protected $alt;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var int
     */
    protected $showinpreview;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var int
     */
    protected $sorting;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $multimedia;

    /**
     * @var string
     */
    protected $html;

    /**
     * @var string
     */
    protected $copyright;

    /**
     * @var string
     */
    protected $description;

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set caption
     *
     * @param string $caption caption
     * @return void
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * Get alt text
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set alt text
     *
     * @param string $alt alt text
     * @return void
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param int $type type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get flag if shown in preview
     *
     * @return int
     */
    public function getShowinpreview()
    {
        return $this->showinpreview;
    }

    /**
     * Set show in preview flag
     *
     * @param int $showinpreview flag if shown in preview
     * @return void
     */
    public function setShowinpreview($showinpreview)
    {
        $this->showinpreview = $showinpreview;
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
     * Set creation date
     *
     * @param \DateTime $crdate creation date
     * @return void
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $tstamp timestamp
     * @return void
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Get hidden flag
     *
     * @return int
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set hidden flag
     *
     * @param int $hidden hidden flag
     * @return void
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Get deleted flag
     *
     * @return int
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deleted flag
     *
     * @param int $deleted deleted flag
     * @return void
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * Get creators user id
     *
     * @return int
     */
    public function getCruserId()
    {
        return $this->cruserId;
    }

    /**
     * Set creators user id
     *
     * @param int $cruserId creators user id
     * @return void
     */
    public function setCruserId($cruserId)
    {
        $this->cruserId = $cruserId;
    }

    /**
     * Get width of element
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set with of element
     *
     * @param int $width integer
     * @return void
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get height of element
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set height of element
     *
     * @param int $height height
     * @return void
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Get sorting
     *
     * @return int
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Set sorting
     *
     * @param int $sorting sorting
     * @return void
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param string $image image
     * @return void
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Get multimedia url
     *
     * @return string
     */
    public function getMultimedia()
    {
        return $this->multimedia;
    }

    /**
     * Set multimedia
     *
     * @param string $multimedia multimedia url
     * @return void
     */
    public function setMultimedia($multimedia)
    {
        $this->multimedia = $multimedia;
    }

    /**
     * Get html
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Set html
     *
     * @param string $html html
     * @return void
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * Set copyright text
     *
     * @param string $copyright
     * @return void
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    }

    /**
     * Get copyright text
     *
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * Get content of media element depending on its type
     *
     * @return mixed content of media element
     */
    public function getContent()
    {
        switch ($this->getType()) {
            case self::MEDIA_TYPE_MULTIMEDIA:
                $content = $this->getMultimedia();
                break;
            case self::MEDIA_TYPE_HTML:
                $content = $this->getHtml();
                break;
            case self::MEDIA_TYPE_IMAGE:
            default:
                $content = $this->getImage();
                break;
        }

        return $content;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
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

}
