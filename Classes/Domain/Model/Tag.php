<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

/**
 * Tag model
 */
class Tag extends AbstractValueObject
{
    /**
     * @var \DateTime
     */
    protected $crdate;

    /**
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * @var string
     */
    protected $title = '';

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
     * This empty constructor is necessary so class is fully
     * extensible by other extensions that might want to define
     * an own __construct() method
     */
    public function __construct() {}

    /**
     * Get crdate
     *
     * @return \DateTime|null
     */
    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }

    /**
     * Set crdate
     *
     * @param \DateTime $crdate crdate
     */
    public function setCrdate($crdate): void
    {
        $this->crdate = $crdate;
    }

    /**
     * Get Tstamp
     *
     * @return \DateTime|null
     */
    public function getTstamp(): ?\DateTime
    {
        return $this->tstamp;
    }

    /**
     * Set tstamp
     *
     * @param \DateTime $tstamp tstamp
     */
    public function setTstamp($tstamp): void
    {
        $this->tstamp = $tstamp;
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
     */
    public function setTitle($title): void
    {
        $this->title = $title;
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
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }
}
