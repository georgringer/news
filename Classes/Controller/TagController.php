<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Controller;

use GeorgRinger\News\Event\TagListActionEvent;
use Psr\Http\Message\ResponseInterface;

/**
 * Tag controller
 */
class TagController extends NewsController
{
    public function listAction(array $overwriteDemand = null): ResponseInterface
    {
        // Default value is wrong for tags
        if ($this->settings['orderBy'] === 'datetime') {
            unset($this->settings['orderBy']);
        }

        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($overwriteDemand !== null && $this->settings['disableOverrideDemand'] != 1) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }

        $assignedValues = [
            'tags' => $this->tagRepository->findDemanded($demand),
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
        ];

        $event = $this->eventDispatcher->dispatch(new TagListActionEvent($this, $assignedValues, $this->request));

        $this->view->assignMultiple($event->getAssignedValues());
        return $this->htmlResponse();
    }
}
