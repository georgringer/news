<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\CategoryController;
use TYPO3\CMS\Extbase\Mvc\Request;

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

    /** @var Request */
    private $request;

    public function __construct(CategoryController $categoryController, array $assignedValues, Request $request)
    {
        $this->categoryController = $categoryController;
        $this->assignedValues = $assignedValues;
        $this->request = $request;
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

    public function getRequest(): Request
    {
        return $this->request;
    }
}
