<?php

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\AdministrationController;
use TYPO3\CMS\Backend\Template\Components\Menu\Menu;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
final class AdministrationExtendMenuEvent
{
    /**
     * @var AdministrationController
     */
    private $administrationController;

    /**
     * @var Menu
     */
    private $menu;

    public function __construct(AdministrationController $administrationController, Menu $menu)
    {
        $this->administrationController = $administrationController;
        $this->menu = $menu;
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
     * Get the menu
     */
    public function getMenu(): Menu
    {
        return $this->menu;
    }

    /**
     * Set the menu
     */
    public function setMenu(Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }
}
