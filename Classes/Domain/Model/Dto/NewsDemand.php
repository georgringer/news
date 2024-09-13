<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Domain\Model\Dto;

use GeorgRinger\News\Domain\Model\DemandInterface;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * News Demand object which holds all information to get the correct news records.
 */
class NewsDemand extends AbstractEntity implements DemandInterface
{
    /** @var array */
    protected $categories = [];

    /** @var string */
    protected $categoryConjunction = '';

    /** @var bool */
    protected $includeSubCategories = false;

    /** @var string */
    protected $author = '';

    /** @var string */
    protected $tags = '';

    /** @var string */
    protected $archiveRestriction = '';

    /** @var string */
    protected $timeRestriction = '';

    /** @var string */
    protected $timeRestrictionHigh = '';

    /** @var int */
    protected $topNewsRestriction = 0;

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

    /** @var Search */
    protected $search;

    /** @var string */
    protected $order = '';

    /** @var string */
    protected $orderByAllowed = '';

    /** @var bool */
    protected $topNewsFirst = false;

    /** @var string */
    protected $storagePage = '';

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
     */
    public function setArchiveRestriction(string $archiveRestriction): NewsDemand
    {
        $this->archiveRestriction = $archiveRestriction;
        return $this;
    }

    /**
     * Get archive setting
     */
    public function getArchiveRestriction(): string
    {
        return $this->archiveRestriction;
    }

    /**
     * List of allowed categories
     *
     * @param array $categories categories
     */
    public function setCategories(array $categories): NewsDemand
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Get allowed categories
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Set category mode
     */
    public function setCategoryConjunction(string $categoryConjunction): NewsDemand
    {
        $this->categoryConjunction = $categoryConjunction;
        return $this;
    }

    /**
     * Get category mode
     */
    public function getCategoryConjunction(): string
    {
        return $this->categoryConjunction;
    }

    /**
     * Get include sub categories
     */
    public function getIncludeSubCategories(): bool
    {
        return (bool)$this->includeSubCategories;
    }

    public function setIncludeSubCategories(bool $includeSubCategories): NewsDemand
    {
        $this->includeSubCategories = $includeSubCategories;
        return $this;
    }

    public function setAuthor(string $author): NewsDemand
    {
        $this->author = $author;
        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getTags(): string
    {
        return $this->tags;
    }

    /**
     * @param string $tags tags
     */
    public function setTags(string $tags): NewsDemand
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Set time limit low, either integer or string
     *
     * @param string|int $timeRestriction
     */
    public function setTimeRestriction($timeRestriction): NewsDemand
    {
        $this->timeRestriction = $timeRestriction;
        return $this;
    }

    /**
     * Get time limit low
     *
     * @return string|int
     */
    public function getTimeRestriction()
    {
        return $this->timeRestriction;
    }

    /**
     * Get time limit high
     *
     * @return string|int
     */
    public function getTimeRestrictionHigh()
    {
        return $this->timeRestrictionHigh;
    }

    /**
     * Set time limit high
     *
     * @param string|int $timeRestrictionHigh
     */
    public function setTimeRestrictionHigh($timeRestrictionHigh): NewsDemand
    {
        $this->timeRestrictionHigh = $timeRestrictionHigh;
        return $this;
    }

    /**
     * @param string $order order
     */
    public function setOrder(string $order): NewsDemand
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * Set order allowed
     *
     * @param string $orderByAllowed allowed fields for ordering
     */
    public function setOrderByAllowed(string $orderByAllowed): NewsDemand
    {
        $this->orderByAllowed = $orderByAllowed;
        return $this;
    }

    /**
     * Get allowed order fields
     */
    public function getOrderByAllowed(): string
    {
        return $this->orderByAllowed;
    }

    /**
     * Set order respect top news flag
     *
     * @param bool $topNewsFirst respect top news flag
     */
    public function setTopNewsFirst(bool $topNewsFirst): NewsDemand
    {
        $this->topNewsFirst = $topNewsFirst;
        return $this;
    }

    /**
     * Get order respect top news flag
     */
    public function getTopNewsFirst(): bool
    {
        return $this->topNewsFirst;
    }

    /**
     * Set search fields
     *
     * @param string $searchFields search fields
     */
    public function setSearchFields(string $searchFields): NewsDemand
    {
        $this->searchFields = $searchFields;
        return $this;
    }

    /**
     * Get search fields
     */
    public function getSearchFields(): string
    {
        return $this->searchFields;
    }

    /**
     * Set top news setting
     *
     * @param int $topNewsRestriction top news settings
     */
    public function setTopNewsRestriction(int $topNewsRestriction): NewsDemand
    {
        $this->topNewsRestriction = $topNewsRestriction;
        return $this;
    }

    /**
     * Get top news setting
     */
    public function getTopNewsRestriction(): int
    {
        return $this->topNewsRestriction;
    }

    /**
     * Set list of storage pages
     *
     * @param string $storagePage storage page list
     */
    public function setStoragePage(string $storagePage): NewsDemand
    {
        $this->storagePage = $storagePage;
        return $this;
    }

    /**
     * Get list of storage pages
     */
    public function getStoragePage(): string
    {
        return $this->storagePage;
    }

    /**
     * Get day restriction
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * Set day restriction
     */
    public function setDay(int $day): NewsDemand
    {
        $this->day = $day;
        return $this;
    }

    /**
     * Get month restriction
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * Set month restriction
     *
     * @param int $month month
     */
    public function setMonth(int $month): NewsDemand
    {
        $this->month = $month;
        return $this;
    }

    /**
     * Get year restriction
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * Set year restriction
     *
     * @param int $year year
     */
    public function setYear(int $year): NewsDemand
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @param int $limit limit
     */
    public function setLimit(int $limit): NewsDemand
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $offset offset
     */
    public function setOffset(int $offset): NewsDemand
    {
        $this->offset = $offset;
        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Set date field which is used for datemenu
     *
     * @param string $dateField datefield
     */
    public function setDateField(string $dateField): NewsDemand
    {
        $this->dateField = $dateField;
        return $this;
    }

    /**
     * Get date field which is used for datemenu
     */
    public function getDateField(): string
    {
        if (in_array($this->dateField, ['datetime', 'archive'], true)
            || isset($GLOBALS['TCA']['tx_news_domain_model_news']['columns'][$this->dateField])) {
            return $this->dateField;
        }

        return '';
    }

    /**
     * Get search object
     */
    public function getSearch(): ?Search
    {
        return $this->search;
    }

    /**
     * Set search object
     *
     * @param Search|null $search search object
     */
    public function setSearch(?Search $search = null): NewsDemand
    {
        $this->search = $search;
        return $this;
    }

    /**
     * Set flag if displayed news records should be excluded
     */
    public function setExcludeAlreadyDisplayedNews(bool $excludeAlreadyDisplayedNews): NewsDemand
    {
        $this->excludeAlreadyDisplayedNews = $excludeAlreadyDisplayedNews;
        return $this;
    }

    /**
     * Get flag if displayed news records should be excluded
     */
    public function getExcludeAlreadyDisplayedNews(): bool
    {
        return $this->excludeAlreadyDisplayedNews;
    }

    public function getHideIdList(): string
    {
        return $this->hideIdList;
    }

    public function setHideIdList(string $hideIdList): NewsDemand
    {
        $this->hideIdList = $hideIdList;
        return $this;
    }

    public function getIdList(): string
    {
        return $this->idList;
    }

    public function setIdList(string $idList): NewsDemand
    {
        $this->idList = $idList;
        return $this;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): NewsDemand
    {
        $this->action = $action;
        return $this;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): NewsDemand
    {
        $this->class = $class;
        return $this;
    }

    public function setActionAndClass(string $action, string $controller): NewsDemand
    {
        $this->action = $action;
        $this->class = $controller;
        return $this;
    }

    /**
     * Get allowed types
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * Set allowed types
     */
    public function setTypes(array $types): void
    {
        $this->types = $types;
    }

    public function getCustomSettings(): array
    {
        return $this->_customSettings;
    }

    public function setCustomSettings(array $customSettings): void
    {
        $this->_customSettings = $customSettings;
    }
}
