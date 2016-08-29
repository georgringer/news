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
use GeorgRinger\News\Domain\Model\DemandInterface;

/**
 * News Demand object which holds all information to get the correct
 * news records.
 *
 */
class NewsDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity implements DemandInterface
{

    /**
     * @var array
     */
    protected $categories;

    /**
     * @var string
     */
    protected $categoryConjunction;

    /**
     * @var bool
     */
    protected $includeSubCategories = false;

    /**
     * @var string
     */
    protected $author;

    /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage */
    protected $tags;

    /**
     * @var string
     */
    protected $archiveRestriction;

    /**
     * @var string
     */
    protected $timeRestriction = null;

    /** @var string */
    protected $timeRestrictionHigh = null;

    /** @var int */
    protected $topNewsRestriction;

    /** @var string */
    protected $dateField;

    /** @var int */
    protected $month;

    /** @var int */
    protected $year;

    /** @var int */
    protected $day;

    /** @var string */
    protected $searchFields;

    /** @var \GeorgRinger\News\Domain\Model\Dto\Search */
    protected $search;

    /** @var string */
    protected $order;

    /** @var string */
    protected $orderByAllowed;

    /** @var bool */
    protected $topNewsFirst;

    /** @var int */
    protected $storagePage;

    /** @var int */
    protected $limit;

    /** @var int */
    protected $offset;

    /** @var bool */
    protected $excludeAlreadyDisplayedNews;

    /** @var string */
    protected $hideIdList;

    /** @var string */
    protected $action;

    /** @var string */
    protected $class;

    /**
     * Set archive settings
     *
     * @param string $archiveRestriction archive setting
     * @return this
     */
    public function setArchiveRestriction($archiveRestriction)
    {
        $this->archiveRestriction = $archiveRestriction;
        return $this;
    }

    /**
     * Get archive setting
     *
     * @return string
     */
    public function getArchiveRestriction()
    {
        return $this->archiveRestriction;
    }

    /**
     * List of allowed categories
     *
     * @param array $categories categories
     * @return this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Get allowed categories
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set category mode
     *
     * @param string $categoryConjunction
     * @return this
     */
    public function setCategoryConjunction($categoryConjunction)
    {
        $this->categoryConjunction = $categoryConjunction;
        return $this;
    }

    /**
     * Get category mode
     *
     * @return string
     */
    public function getCategoryConjunction()
    {
        return $this->categoryConjunction;
    }

    /**
     * Get include sub categories
     * @return bool
     */
    public function getIncludeSubCategories()
    {
        return (boolean)$this->includeSubCategories;
    }

    /**
     * @param bool $includeSubCategories
     * @return this
     */
    public function setIncludeSubCategories($includeSubCategories)
    {
        $this->includeSubCategories = $includeSubCategories;
        return $this;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return this
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get Tags
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set Tags
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags tags
     * @return this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Set time limit low, either integer or string
     *
     * @param mixed $timeRestriction
     * @return this
     */
    public function setTimeRestriction($timeRestriction)
    {
        $this->timeRestriction = $timeRestriction;
        return $this;
    }

    /**
     * Get time limit low
     *
     * @return mixed
     */
    public function getTimeRestriction()
    {
        return $this->timeRestriction;
    }

    /**
     * Get time limit high
     *
     * @return mixed
     */
    public function getTimeRestrictionHigh()
    {
        return $this->timeRestrictionHigh;
    }

    /**
     * Set time limit high
     *
     * @param mixed $timeRestrictionHigh
     * @return this
     */
    public function setTimeRestrictionHigh($timeRestrictionHigh)
    {
        $this->timeRestrictionHigh = $timeRestrictionHigh;
        return $this;
    }

    /**
     * Set order
     *
     * @param string $order order
     * @return this
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order allowed
     *
     * @param string $orderByAllowed allowed fields for ordering
     * @return this
     */
    public function setOrderByAllowed($orderByAllowed)
    {
        $this->orderByAllowed = $orderByAllowed;
        return $this;
    }

    /**
     * Get allowed order fields
     *
     * @return string
     */
    public function getOrderByAllowed()
    {
        return $this->orderByAllowed;
    }

    /**
     * Set order respect top news flag
     *
     * @param bool $topNewsFirst respect top news flag
     * @return this
     */
    public function setTopNewsFirst($topNewsFirst)
    {
        $this->topNewsFirst = $topNewsFirst;
        return $this;
    }

    /**
     * Get order respect top news flag
     *
     * @return int
     */
    public function getTopNewsFirst()
    {
        return $this->topNewsFirst;
    }

    /**
     * Set search fields
     *
     * @param string $searchFields search fields
     * @return void
     */
    public function setSearchFields($searchFields)
    {
        $this->searchFields = $searchFields;
        return $this;
    }

    /**
     * Get search fields
     *
     * @return string
     */
    public function getSearchFields()
    {
        return $this->searchFields;
    }

    /**
     * Set top news setting
     *
     * @param string $topNewsRestriction top news settings
     * @return this
     */
    public function setTopNewsRestriction($topNewsRestriction)
    {
        $this->topNewsRestriction = $topNewsRestriction;
        return $this;
    }

    /**
     * Get top news setting
     *
     * @return string
     */
    public function getTopNewsRestriction()
    {
        return $this->topNewsRestriction;
    }

    /**
     * Set list of storage pages
     *
     * @param string $storagePage storage page list
     * @return this
     */
    public function setStoragePage($storagePage)
    {
        $this->storagePage = $storagePage;
        return $this;
    }

    /**
     * Get list of storage pages
     *
     * @return string
     */
    public function getStoragePage()
    {
        return $this->storagePage;
    }

    /**
     * Get day restriction
     *
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set day restriction
     *
     * @param int $day
     * @return this
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

    /**
     * Get month restriction
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set month restriction
     *
     * @param int $month month
     * @return this
     */
    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * Get year restriction
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set year restriction
     *
     * @param int $year year
     * @return this
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * Set limit
     *
     * @param int $limit limit
     * @return this
     */
    public function setLimit($limit)
    {
        $this->limit = (int)$limit;
        return $this;
    }

    /**
     * Get limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set offset
     *
     * @param int $offset offset
     * @return this
     */
    public function setOffset($offset)
    {
        $this->offset = (int)$offset;
        return $this;
    }

    /**
     * Get offset
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set date field which is used for datemenu
     *
     * @param string $dateField datefield
     * @return this
     */
    public function setDateField($dateField)
    {
        $this->dateField = $dateField;
        return $this;
    }

    /**
     * Get datefield which is used for datemenu
     *
     * @return string
     */
    public function getDateField()
    {
        if (empty($this->dateField) || in_array($this->dateField, ['datetime', 'archive'])) {
            return $this->dateField;
        } else {
            return '';
        }
    }

    /**
     * Get search object
     *
     * @return \GeorgRinger\News\Domain\Model\Dto\Search
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set search object
     *
     * @param \GeorgRinger\News\Domain\Model\Dto\Search $search search object
     * @return this
     */
    public function setSearch($search = null)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * Set flag if displayed news records should be excluded
     *
     * @param bool $excludeAlreadyDisplayedNews
     * @return this
     */
    public function setExcludeAlreadyDisplayedNews($excludeAlreadyDisplayedNews)
    {
        $this->excludeAlreadyDisplayedNews = (bool)$excludeAlreadyDisplayedNews;
        return $this;
    }

    /**
     * Get flag if displayed news records should be excluded
     *
     * @return bool
     */
    public function getExcludeAlreadyDisplayedNews()
    {
        return $this->excludeAlreadyDisplayedNews;
    }

    /**
     * @return string
     */
    public function getHideIdList()
    {
        return $this->hideIdList;
    }

    /**
     * @param string $hideIdList
     * @return this
     */
    public function setHideIdList($hideIdList)
    {
        $this->hideIdList = $hideIdList;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return this
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @param string $action
     * @param string $controller
     * @return this
     */
    public function setActionAndClass($action, $controller)
    {
        $this->action = $action;
        $this->class = $controller;
        return $this;
    }
}
