<?php

namespace GeorgRinger\News\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * News Demand object which holds all information to get the correct
 * news records.
 */
class Search extends AbstractEntity
{

    /**
     * Basic search word
     *
     * @var string
     */
    protected $subject = '';

    /**
     * Search fields
     *
     * @var string
     */
    protected $fields = '';

    /**
     * Minimum date
     *
     * @var string
     */
    protected $minimumDate = '';

    /**
     * Maximum date
     *
     * @var string
     */
    protected $maximumDate = '';

    /**
     * Field using for date queries
     *
     * @var string
     */
    protected $dateField = '';

    /** @var bool */
    protected $splitSubjectWords = false;

    /**
     * Get the subject
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Set subject
     *
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * Get fields
     *
     * @return string
     */
    public function getFields(): string
    {
        return $this->fields;
    }

    /**
     * Set fields
     *
     * @param string $fields
     *
     * @return void
     */
    public function setFields(string $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @param string $maximumDate
     *
     * @return void
     */
    public function setMaximumDate($maximumDate): void
    {
        $this->maximumDate = $maximumDate;
    }

    /**
     * @return string
     */
    public function getMaximumDate(): string
    {
        return $this->maximumDate;
    }

    /**
     * @param string $minimumDate
     *
     * @return void
     */
    public function setMinimumDate(string $minimumDate): void
    {
        $this->minimumDate = $minimumDate;
    }

    /**
     * @return string
     */
    public function getMinimumDate(): string
    {
        return $this->minimumDate;
    }

    /**
     * @param string $dateField
     *
     * @return void
     */
    public function setDateField($dateField): void
    {
        $this->dateField = $dateField;
    }

    /**
     * @return string
     */
    public function getDateField(): string
    {
        return $this->dateField;
    }

    /**
     * @return bool
     */
    public function isSplitSubjectWords(): bool
    {
        return $this->splitSubjectWords;
    }

    /**
     * @param bool $splitSubjectWords
     *
     * @return void
     */
    public function setSplitSubjectWords($splitSubjectWords): void
    {
        $this->splitSubjectWords = $splitSubjectWords;
    }
}
