<?php

namespace GeorgRinger\News\Domain\Model\Dto;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Administration Demand model
 */
class AdministrationDemand extends NewsDemand
{

    /**
     * @var string
     */
    protected $recursive = '';

    /**
     * @var array
     */
    protected $selectedCategories = [];

    /**
     * @var string
     */
    protected $sortingField = 'datetime';

    /**
     * @var string
     */
    protected $sortingDirection = 'desc';

    /**
     * @var string
     */
    protected $searchWord = '';

    /**
     * @var int
     */
    protected $hidden = 0;

    /**
     * @var int
     */
    protected $archived = 0;

    /**
     * @return string
     */
    public function getRecursive(): string
    {
        return $this->recursive;
    }

    /**
     * @param string $recursive
     *
     * @return void
     */
    public function setRecursive(string $recursive): void
    {
        $this->recursive = $recursive;
    }

    /**
     * @return array
     */
    public function getSelectedCategories(): array
    {
        return $this->selectedCategories;
    }

    /**
     * @param string|array $selectedCategories
     *
     * @return void
     */
    public function setSelectedCategories($selectedCategories)
    {
        if ($selectedCategories === '0' || $selectedCategories === ['0']) {
            return;
        }
        if (is_string($selectedCategories)) {
            $selectedCategories = explode(',', $selectedCategories);
        }
        $this->selectedCategories = $selectedCategories;
    }

    /**
     * @return string
     */
    public function getSortingField(): string
    {
        return $this->sortingField;
    }

    /**
     * @param string $sortingField
     * @return void
     */
    public function setSortingField(string $sortingField): void
    {
        $this->sortingField = $sortingField;
    }

    /**
     * @return string
     */
    public function getSortingDirection(): string
    {
        return $this->sortingDirection;
    }

    /**
     * @param string $sortingDirection
     * @return void
     */
    public function setSortingDirection(string $sortingDirection): void
    {
        $this->sortingDirection = $sortingDirection;
    }

    /**
     * @return string
     */
    public function getSearchWord(): string
    {
        return $this->searchWord;
    }

    /**
     * @param string $searchWord
     * @return void
     */
    public function setSearchWord(string $searchWord): void
    {
        $this->searchWord = $searchWord;
    }

    /**
     * @return int
     */
    public function getHidden(): int
    {
        return $this->hidden;
    }

    /**
     * @param int $hidden
     * @return void
     */
    public function setHidden(int $hidden): void
    {
        $this->hidden = $hidden;
    }

    /**
     * @return int
     */
    public function getArchived(): int
    {
        return $this->archived;
    }

    /**
     * @param int $archived
     *
     * @return void
     */
    public function setArchived(int $archived): void
    {
        $this->archived = $archived;
    }
}
