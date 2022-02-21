<?php

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\TagController;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
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

    public function __construct(TagController $tagController, array $assignedValues)
    {
        $this->tagController = $tagController;
        $this->assignedValues = $assignedValues;
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
}
