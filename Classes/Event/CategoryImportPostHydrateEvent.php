<?php

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Service\CategoryImportService;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
final class CategoryImportPostHydrateEvent
{
    /**
     * @var CategoryImportService
     */
    private $categoryImportService;

    /**
     * @var array
     */
    private $importItem;

    /**
     * @var Category
     */
    private $category;

    public function __construct(CategoryImportService $categoryImportService, array $importItem, Category $category)
    {
        $this->categoryImportService = $categoryImportService;
        $this->importItem = $importItem;
        $this->category = $category;
    }

    /**
     * Get the importer service
     */
    public function getCategoryImportService(): CategoryImportService
    {
        return $this->categoryImportService;
    }

    /**
     * Set the importer Service
     */
    public function setCategoryImportService(CategoryImportService $categoryImportService): self
    {
        $this->categoryImportService = $categoryImportService;

        return $this;
    }

    /**
     * Get the importItem
     */
    public function getImportItem(): array
    {
        return $this->importItem;
    }

    /**
     * Set the importItem
     */
    public function setImportItem(array $importItem): self
    {
        $this->importItem = $importItem;

        return $this;
    }

    /**
     * Get the category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * Set the category
     */
    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
