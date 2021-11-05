<?php

namespace GeorgRinger\News\Controller;

use GeorgRinger\News\Event\CategoryListActionEvent;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
/**
 * Category controller
 */
class CategoryController extends NewsController
{
    /**
     * List categories
     *
     * @param array $overwriteDemand
     *
     * @return void
     */
    public function listAction(array $overwriteDemand = null)
    {
        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($overwriteDemand !== null && $this->settings['disableOverrideDemand'] != 1) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }

        $idList = is_array($demand->getCategories()) ? $demand->getCategories() : explode(',', $demand->getCategories());

        $startingPoint = null;
        if (!empty($this->settings['startingpoint'])) {
            $startingPoint = $this->settings['startingpoint'];
        }

        $assignedValues = [
            'categories' => $this->categoryRepository->findTree($idList, $startingPoint),
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
        ];

        $event = $this->eventDispatcher->dispatch(new CategoryListActionEvent($this, $assignedValues));

        $this->view->assignMultiple($event->getAssignedValues());
    }
}
