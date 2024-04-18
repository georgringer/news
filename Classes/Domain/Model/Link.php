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
 * Link model
 */
class Link extends AbstractValueObject
{
    /** @var \DateTime */
    protected $crdate;

    /** @var \DateTime */
    protected $tstamp;

    /** @var string */
    protected $title = '';

    /** @var string */
    protected $description = '';

    /** @var string */
    protected $uri = '';

    /** @var int */
    protected $l10nParent = 0;

    /**
     * This empty constructor is necessary so class is fully
     * extensible by other extensions that might want to define
     * an own __construct() method
     */
    public function __construct() {}

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
     * @param \DateTime $crdate creation date
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
     * @param \DateTime $tstamp timestamp
     */
    public function setTstamp($tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri uri
     */
    public function setUri($uri): void
    {
        $this->uri = $uri;
    }

    /**
     * Set sys language
     *
     * @param int $sysLanguageUid
     */
    public function setSysLanguageUid(?int $sysLanguageUid): void
    {
        $this->_languageUid = $sysLanguageUid;
    }

    /**
     * Get sys language
     */
    public function getSysLanguageUid(): int
    {
        return $this->_languageUid;
    }

    /**
     * Set l10n parent
     *
     * @param int $l10nParent
     */
    public function setL10nParent($l10nParent): void
    {
        $this->l10nParent = $l10nParent;
    }

    /**
     * Get l10n parent
     */
    public function getL10nParent(): int
    {
        return $this->l10nParent;
    }
}
