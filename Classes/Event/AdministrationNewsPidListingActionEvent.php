<?php

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\AdministrationController;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
final class AdministrationNewsPidListingActionEvent
{
    /**
     * @var AdministrationController
     */
    private $administrationController;

    /**
     * @var array
     */
    private $rawTree;

    /**
     * @var int
     */
    private $treeLevel;

    public function __construct(AdministrationController $administrationController, array $rawTree, int $treeLevel)
    {
        $this->administrationController = $administrationController;
        $this->rawTree = $rawTree;
        $this->treeLevel = $treeLevel;
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
     * Get the rawTree
     */
    public function getRawTree(): array
    {
        return $this->rawTree;
    }

    /**
     * Set the rawTree
     */
    public function setRawTree(array $rawTree): self
    {
        $this->rawTree = $rawTree;

        return $this;
    }

    /**
     * Get the treeLevel
     */
    public function getTreeLevel(): int
    {
        return $this->treeLevel;
    }

    /**
     * Set the treeLevel
     */
    public function setTreeLevel(int $treeLevel): self
    {
        $this->treeLevel = $treeLevel;

        return $this;
    }
}
