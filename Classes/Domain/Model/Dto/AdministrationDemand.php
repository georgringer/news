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
     * @var int
     */
    protected $hidden;

    /**
     * @var int
     */
    protected $archived;

    /**
     * @return string
     */
    public function getRecursive()
    {
        return $this->recursive;
    }

    /**
     * @param $recursive
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

    /**
     * @return int
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param int $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * @return int
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @param int $archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }
}
