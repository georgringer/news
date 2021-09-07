<?php

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\CategoryController;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
final class CategoryListActionEvent
{
    /**
     * @var CategoryController
     */
    private $categoryController;

    /**
     * @var array
     */
    private $assignedValues;

    public function __construct(CategoryController $categoryController, array $assignedValues)
    {
        $this->categoryController = $categoryController;
        $this->assignedValues = $assignedValues;
    }

    /**
     * Get the category controller
     */
    public function getCategoryController(): CategoryController
    {
        return $this->categoryController;
    }

    /**
     * Set the category controller
     */
    public function setCategoryController(CategoryController $categoryController): self
    {
        $this->categoryController = $categoryController;

        return $this;
    }

    /**
     * Get the assignedValues
     */
    public function getAssignedValues(): array
    {
        return $this->assignedValues;
    }

    /**
     * Set the assignedValues
     */
    public function setAssignedValues(array $assignedValues): self
    {
        $this->assignedValues = $assignedValues;

        return $this;
    }
}
