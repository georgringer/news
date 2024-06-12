<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Controller;

use GeorgRinger\News\Event\CategoryListActionEvent;
use Psr\Http\Message\ResponseInterface;

/**
 * Category controller
 */
class CategoryController extends NewsController
{
    /**
     * List categories
     */
    public function listAction(array $overwriteDemand = null): ResponseInterface
    {
        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($overwriteDemand !== null && ($this->settings['disableOverrideDemand'] ?? 1) != 1) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }

        $idList = explode(',', $this->settings['categories'] ?? '');

        $startingPoint = null;
        if (!empty($this->settings['startingpoint'] ?? '')) {
            $startingPoint = $this->settings['startingpoint'];
        }

        $assignedValues = [
            'categories' => $this->categoryRepository->findTree($idList, $startingPoint),
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
        ];

        $event = $this->eventDispatcher->dispatch(new CategoryListActionEvent($this, $assignedValues, $this->request));

        $this->view->assignMultiple($event->getAssignedValues());
        return $this->htmlResponse();
    }
}
