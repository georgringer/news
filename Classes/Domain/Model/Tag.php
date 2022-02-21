<?php

namespace GeorgRinger\News\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
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
     * Get crdate
     *
     * @return null|\DateTime
     */
    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }

    /**
     * Set crdate
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
     *
     * @return void
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
