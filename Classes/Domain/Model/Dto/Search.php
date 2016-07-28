<?php
namespace GeorgRinger\News\Domain\Model\Dto;

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
 * News Demand object which holds all information to get the correct
 * news records.
 *
 */
class Search extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * Basic search word
     *
     * @var string
     */
    protected $subject;

    /**
     * Search fields
     *
     * @var string
     */
    protected $fields;

    /**
     * Minimum date
     *
     * @var string
     */
    protected $minimumDate;

    /**
     * Maximum date
     *
     * @var string
     */
    protected $maximumDate;

    /**
     * Field using for date queries
     *
     * @var string
     */
    protected $dateField;

    /**
     * Get the subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return void
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get fields
     *
     * @return string
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set fields
     *
     * @param $fields
     * @return void
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param string $maximumDate
     */
    public function setMaximumDate($maximumDate)
    {
        $this->maximumDate = $maximumDate;
    }

    /**
     * @return string
     */
    public function getMaximumDate()
    {
        return $this->maximumDate;
    }

    /**
     * @param string $minimumDate
     */
    public function setMinimumDate($minimumDate)
    {
        $this->minimumDate = $minimumDate;
    }

    /**
     * @return string
     */
    public function getMinimumDate()
    {
        return $this->minimumDate;
    }

    /**
     * @param string $dateField
     */
    public function setDateField($dateField)
    {
        $this->dateField = $dateField;
    }

    /**
     * @return string
     */
    public function getDateField()
    {
        return $this->dateField;
    }
}
