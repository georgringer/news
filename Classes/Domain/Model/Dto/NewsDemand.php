<?php

namespace GeorgRinger\News\Domain\Model\Dto;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\DemandInterface;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * News Demand object which holds all information to get the correct news records.
 */
class NewsDemand extends AbstractEntity implements DemandInterface
{

    /**
     * @var array
     */
    protected $categories = [];

    /**
     * @var string
     */
    protected $categoryConjunction = '';

    /**
     * @var bool
     */
    protected $includeSubCategories = false;

    /**
     * @var string
     */
    protected $author = '';

    /** @var string */
    protected $tags = '';

    /**
     * @var string
     */
    protected $archiveRestriction = '';

    /**
     * @var string
     */
    protected $timeRestriction = '';

    /** @var string */
    protected $timeRestrictionHigh = '';

    /** @var int */
    protected $topNewsRestriction = '';

    /** @var string */
    protected $dateField = '';

    /** @var int */
    protected $month = 0;

    /** @var int */
    protected $year = 0;

    /** @var int */
    protected $day = 0;

    /** @var string */
    protected $searchFields = '';

    /** @var \GeorgRinger\News\Domain\Model\Dto\Search */
    protected $search;

    /** @var string */
    protected $order = '';

    /** @var string */
    protected $orderByAllowed = '';

    /** @var bool */
    protected $topNewsFirst = false;

    /** @var int */
    protected $storagePage = 0;

    /** @var int */
    protected $limit = 0;

    /** @var int */
    protected $offset = 0;

    /** @var bool */
    protected $excludeAlreadyDisplayedNews = false;

    /** @var string */
    protected $hideIdList = '';

    /** @var string */
    protected $idList = '';

    /** @var string */
    protected $action = '';

    /** @var string */
    protected $class = '';

    /**
     * List of allowed types
     *
     * @var array
     */
    protected $types = [];

    /**
     * Holding custom data, use e.g. your ext key as array key
     *
     * @var array
     */
    protected $_customSettings = [];

    /**
     * Set archive settings
     *
     * @param string $archiveRestriction archive setting
     * @return NewsDemand
     */
    public function setArchiveRestriction($archiveRestriction): NewsDemand
    {
        $this->archiveRestriction = $archiveRestriction;
        return $this;
    }

    /**
     * Get archive setting
     *
     * @return string
     */
    public function getArchiveRestriction(): string
    {
        return $this->archiveRestriction;
    }

    /**
     * List of allowed categories
     *
     * @param array $categories categories
     * @return NewsDemand
     */
    public function setCategories($categories): NewsDemand
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Get allowed categories
     *
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Set category mode
     *
     * @param string $categoryConjunction
     * @return NewsDemand
     */
    public function setCategoryConjunction($categoryConjunction): NewsDemand
    {
        $this->categoryConjunction = $categoryConjunction;
        return $this;
    }

    /**
     * Get category mode
     *
     * @return string
     */
    public function getCategoryConjunction(): string
    {
        return $this->categoryConjunction;
    }

    /**
     * Get include sub categories
     * @return bool
     */
    public function getIncludeSubCategories(): bool
    {
        return (boolean)$this->includeSubCategories;
    }

    /**
     * @param bool $includeSubCategories
     * @return NewsDemand
     */
    public function setIncludeSubCategories($includeSubCategories): NewsDemand
    {
        $this->includeSubCategories = $includeSubCategories;
        return $this;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return NewsDemand
     */
    public function setAuthor($author): NewsDemand
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Get Tags
     *
     * @return string
     */
    public function getTags(): string
    {
        return $this->tags;
    }

    /**
     * Set Tags
     *
     * @param string $tags tags
     * @return NewsDemand
     */
    public function setTags($tags): NewsDemand
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Set time limit low, either integer or string
     *
     * @param mixed $timeRestriction
     * @return NewsDemand
     */
    public function setTimeRestriction($timeRestriction): NewsDemand
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
     * @return NewsDemand
     */
    public function setTimeRestrictionHigh($timeRestrictionHigh): NewsDemand
    {
        $this->timeRestrictionHigh = $timeRestrictionHigh;
        return $this;
    }

    /**
     * Set order
     *
     * @param string $order order
     * @return NewsDemand
     */
    public function setOrder($order): NewsDemand
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * Set order allowed
     *
     * @param string $orderByAllowed allowed fields for ordering
     * @return NewsDemand
     */
    public function setOrderByAllowed($orderByAllowed): NewsDemand
    {
        $this->orderByAllowed = $orderByAllowed;
        return $this;
    }

    /**
     * Get allowed order fields
     *
     * @return string
     */
    public function getOrderByAllowed(): string
    {
        return $this->orderByAllowed;
    }

    /**
     * Set order respect top news flag
     *
     * @param bool $topNewsFirst respect top news flag
     * @return NewsDemand
     */
    public function setTopNewsFirst($topNewsFirst): NewsDemand
    {
        $this->topNewsFirst = $topNewsFirst;
        return $this;
    }

    /**
     * Get order respect top news flag
     *
     * @return int
     */
    public function getTopNewsFirst(): int
    {
        return $this->topNewsFirst;
    }

    /**
     * Set search fields
     *
     * @param string $searchFields search fields
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
    public function getSearchFields(): string
    {
        return $this->searchFields;
    }

    /**
     * Set top news setting
     *
     * @param string $topNewsRestriction top news settings
     * @return NewsDemand
     */
    public function setTopNewsRestriction($topNewsRestriction): NewsDemand
    {
        $this->topNewsRestriction = $topNewsRestriction;
        return $this;
    }

    /**
     * Get top news setting
     *
     * @return string
     */
    public function getTopNewsRestriction(): string
    {
        return $this->topNewsRestriction;
    }

    /**
     * Set list of storage pages
     *
     * @param string $storagePage storage page list
     * @return NewsDemand
     */
    public function setStoragePage($storagePage): NewsDemand
    {
        $this->storagePage = $storagePage;
        return $this;
    }

    /**
     * Get list of storage pages
     *
     * @return string
     */
    public function getStoragePage(): string
    {
        return $this->storagePage;
    }

    /**
     * Get day restriction
     *
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * Set day restriction
     *
     * @param int $day
     * @return NewsDemand
     */
    public function setDay($day): NewsDemand
    {
        $this->day = $day;
        return $this;
    }

    /**
     * Get month restriction
     *
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * Set month restriction
     *
     * @param int $month month
     * @return NewsDemand
     */
    public function setMonth($month): NewsDemand
    {
        $this->month = $month;
        return $this;
    }

    /**
     * Get year restriction
     *
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * Set year restriction
     *
     * @param int $year year
     * @return NewsDemand
     */
    public function setYear($year): NewsDemand
    {
        $this->year = $year;
        return $this;
    }

    /**
     * Set limit
     *
     * @param int $limit limit
     * @return NewsDemand
     */
    public function setLimit($limit): NewsDemand
    {
        $this->limit = (int)$limit;
        return $this;
    }

    /**
     * Get limit
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Set offset
     *
     * @param int $offset offset
     * @return NewsDemand
     */
    public function setOffset($offset): NewsDemand
    {
        $this->offset = (int)$offset;
        return $this;
    }

    /**
     * Get offset
     *
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Set date field which is used for datemenu
     *
     * @param string $dateField datefield
     * @return NewsDemand
     */
    public function setDateField($dateField): NewsDemand
    {
        $this->dateField = $dateField;
        return $this;
    }

    /**
     * Get date field which is used for datemenu
     *
     * @return string
     */
    public function getDateField(): string
    {
        if (in_array($this->dateField, ['datetime', 'archive'])
            || isset($GLOBALS['TCA']['tx_news_domain_model_news']['columns'][$this->dateField])) {
            return $this->dateField;
        }

        return '';
    }

    /**
     * Get search object
     *
     * @return \GeorgRinger\News\Domain\Model\Dto\Search
     */
    public function getSearch(): \GeorgRinger\News\Domain\Model\Dto\Search
    {
        return $this->search;
    }

    /**
     * Set search object
     *
     * @param \GeorgRinger\News\Domain\Model\Dto\Search $search search object
     * @return NewsDemand
     */
    public function setSearch($search = null): NewsDemand
    {
        $this->search = $search;
        return $this;
    }

    /**
     * Set flag if displayed news records should be excluded
     *
     * @param bool $excludeAlreadyDisplayedNews
     * @return NewsDemand
     */
    public function setExcludeAlreadyDisplayedNews($excludeAlreadyDisplayedNews): NewsDemand
    {
        $this->excludeAlreadyDisplayedNews = (bool)$excludeAlreadyDisplayedNews;
        return $this;
    }

    /**
     * Get flag if displayed news records should be excluded
     *
     * @return bool
     */
    public function getExcludeAlreadyDisplayedNews(): bool
    {
        return $this->excludeAlreadyDisplayedNews;
    }

    /**
     * @return string
     */
    public function getHideIdList(): string
    {
        return $this->hideIdList;
    }

    /**
     * @param string $hideIdList
     * @return NewsDemand
     */
    public function setHideIdList($hideIdList): NewsDemand
    {
        $this->hideIdList = $hideIdList;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdList(): string
    {
        return $this->idList;
    }

    /**
     * @param string $idList
     * @return NewsDemand
     */
    public function setIdList($idList): NewsDemand
    {
        $this->idList = $idList;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return NewsDemand
     */
    public function setAction($action): NewsDemand
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return NewsDemand
     */
    public function setClass($class): NewsDemand
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @param string $action
     * @param string $controller
     * @return NewsDemand
     */
    public function setActionAndClass($action, $controller): NewsDemand
    {
        $this->action = $action;
        $this->class = $controller;
        return $this;
    }

    /**
     * Get allowed types
     *
     * @return array
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * Set allowed types
     *
     * @param array $types
     */
    public function setTypes($types)
    {
        $this->types = $types;
    }

    /**
     * @return array
     */
    public function getCustomSettings(): array
    {
        return $this->_customSettings;
    }

    /**
     * @param array $customSettings
     */
    public function setCustomSettings(array $customSettings)
    {
        $this->_customSettings = $customSettings;
    }
}
