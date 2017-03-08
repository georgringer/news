<?php
namespace GeorgRinger\News\Controller;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Tag controller
 */
class TagController extends NewsController
{
    const SIGNAL_TAG_LIST_ACTION = 'listAction';

    /**
     * List tags
     *
     * @param array $overwriteDemand
     */
    public function listAction(array $overwriteDemand = null)
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

        $assignedValues = $this->emitActionSignal('TagController', self::SIGNAL_TAG_LIST_ACTION, $assignedValues);
        $this->view->assignMultiple($assignedValues);
    }
}
