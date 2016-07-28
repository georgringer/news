<?php
namespace GeorgRinger\News\Controller;

/**
     * This file is part of the TYPO3 CMS project.
     *
     * It is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License, either version 2
     * of the License, or any later version.
     *
     * For the full copyright and license information, please read the
     * LICENSE.txt file that was distributed with this source code.
     *
     * The TYPO3 project - inspiring people to share!
     */

/**
 * Category controller
 *
 */
class CategoryController extends NewsController
{

    const SIGNAL_CATEGORY_LIST_ACTION = 'listAction';

    /**
     * Page uid
     *
     * @var int
     */
    protected $pageUid = 0;

    /**
     * @var \GeorgRinger\News\Domain\Repository\NewsRepository
     */
    protected $newsRepository;

    /**
     * @var \GeorgRinger\News\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Inject a category repository to enable DI
     *
     * @param \GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository
     * @return void
     */
    public function injectCategoryRepository(\GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * List categories
     *
     * @param array $overwriteDemand
     * @return void
     */
    public function listAction(array $overwriteDemand = null)
    {
        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== null) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }

        $idList = explode(',', $this->settings['categories']);

        $startingPoint = null;
        if (!empty($this->settings['startingpoint'])) {
            $startingPoint = $this->settings['startingpoint'];
        }

        $assignedValues = [
            'categories' => $this->categoryRepository->findTree($idList, $startingPoint),
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
        ];

        $assignedValues = $this->emitActionSignal('CategoryController', self::SIGNAL_CATEGORY_LIST_ACTION,
            $assignedValues);
        $this->view->assignMultiple($assignedValues);
    }
}
