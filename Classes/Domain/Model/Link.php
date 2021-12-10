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
 * Link model
 */
class Link extends AbstractValueObject
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
    protected $description = '';

    /**
     * @var string
     */
    protected $uri = '';

    /**
     * @var int
     */
    protected $l10nParent = 0;

    /**
     * Get creation date
     *
     * @return null|\DateTime
     */
    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }

    /**
     * Set creation date
     *
     * @param \DateTime $crdate creation date
     *
     * @return void
     */
    public function setCrdate($crdate): void
    {
        $this->crdate = $crdate;
    }

    /**
     * Get timestamp
     *
     * @return null|\DateTime
     */
    public function getTstamp(): ?\DateTime
    {
        return $this->tstamp;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $tstamp timestamp
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
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description description
     *
     * @return void
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Set uri
     *
     * @param string $uri uri
     *
     * @return void
     */
    public function setUri($uri): void
    {
        $this->uri = $uri;
    }

    /**
     * Set sys language
     *
     * @param int $sysLanguageUid
     *
     * @return void
     */
    public function setSysLanguageUid($sysLanguageUid): void
    {
        $this->_languageUid = $sysLanguageUid;
    }

    /**
     * Get sys language
     *
     * @return int
     */
    public function getSysLanguageUid(): int
    {
        return $this->_languageUid;
    }

    /**
     * Set l10n parent
     *
     * @param int $l10nParent
     *
     * @return void
     */
    public function setL10nParent($l10nParent): void
    {
        $this->l10nParent = $l10nParent;
    }

    /**
     * Get l10n parent
     *
     * @return int
     */
    public function getL10nParent(): int
    {
        return $this->l10nParent;
    }
}
