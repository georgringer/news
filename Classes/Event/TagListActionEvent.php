<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\TagController;
use TYPO3\CMS\Extbase\Mvc\Request;

final class TagListActionEvent
{
    /**
     * @var TagController
     */
    private $tagController;

    /**
     * @var array
     */
    private $assignedValues;

    /** @var Request */
    private $request;

    public function __construct(TagController $tagController, array $assignedValues, Request $request)
    {
        $this->tagController = $tagController;
        $this->assignedValues = $assignedValues;
        $this->request = $request;
    }

    /**
     * Get the tag controller
     */
    public function getTagController(): TagController
    {
        return $this->tagController;
    }

    /**
     * Set the tag controller
     */
    public function setTagController(TagController $tagController): self
    {
        $this->tagController = $tagController;

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
