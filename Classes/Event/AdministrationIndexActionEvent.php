<?php

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\AdministrationController;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
final class AdministrationIndexActionEvent
{
    /**
     * @var AdministrationController
     */
    private $administrationController;

    /**
     * @var array
     */
    private $assignedValues;

    public function __construct(AdministrationController $administrationController, array $assignedValues)
    {
        $this->administrationController = $administrationController;
        $this->assignedValues = $assignedValues;
    }

    /**
     * Get the administration controller
     */
    public function getAdministrationController(): AdministrationController
    {
        return $this->administrationController;
    }

    /**
     * Set the administration controller
     */
    public function setAdministrationController(AdministrationController $administrationController): self
    {
        $this->administrationController = $administrationController;

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
