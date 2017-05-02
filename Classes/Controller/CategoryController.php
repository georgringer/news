<?php
namespace GeorgRinger\News\Controller;

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
    const SIGNAL_CATEGORY_LIST_ACTION = 'listAction';

    /**
     * @var \GeorgRinger\News\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Inject a category repository to enable DI
     *
     * @param \GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(\GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * List categories
     *
     * @param array $overwriteDemand
     */
    public function listAction(array $overwriteDemand = null)
    {
        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($overwriteDemand !== null && $this->settings['disableOverrideDemand'] != 1) {
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
