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
 * Tag model
 *
 */
class Tag extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject
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
    protected $title;

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
     * Get crdate
     *
     * @return \DateTime
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Set crdate
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
