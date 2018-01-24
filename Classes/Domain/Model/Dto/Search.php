<?php
namespace GeorgRinger\News\Domain\Model\Dto;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
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

    /** @var bool */
    protected $splitSubjectWords = false;

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

    /**
     * @return bool
     */
    public function isSplitSubjectWords()
    {
        return $this->splitSubjectWords;
    }

    /**
     * @param bool $splitSubjectWords
     */
    public function setSplitSubjectWords($splitSubjectWords)
    {
        $this->splitSubjectWords = $splitSubjectWords;
    }
}
