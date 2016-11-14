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
 * Administration Demand model
 *
 */
class AdministrationDemand extends \GeorgRinger\News\Domain\Model\Dto\NewsDemand
{

    /**
     * @var string
     */
    protected $recursive;

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
    protected $searchWord;

    /**
     * @return string
     */
    public function getRecursive()
    {
        return $this->recursive;
    }

    /**
     * @param $recursive
     * @return void
     */
    public function setRecursive($recursive)
    {
        $this->recursive = $recursive;
    }

    /**
     * @return array
     */
    public function getSelectedCategories()
    {
        return $this->selectedCategories;
    }

    /**
     * @param $selectedCategories
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
    public function getSortingField()
    {
        return $this->sortingField;
    }

    /**
     * @param $sortingField
     * @return void
     */
    public function setSortingField($sortingField)
    {
        $this->sortingField = $sortingField;
    }

    /**
     * @return string
     */
    public function getSortingDirection()
    {
        return $this->sortingDirection;
    }

    /**
     * @param $sortingDirection
     * @return void
     */
    public function setSortingDirection($sortingDirection)
    {
        $this->sortingDirection = $sortingDirection;
    }

    /**
     * @return string
     */
    public function getSearchWord()
    {
        return $this->searchWord;
    }

    /**
     * @param string $searchWord
     */
    public function setSearchWord($searchWord)
    {
        $this->searchWord = $searchWord;
    }
}
