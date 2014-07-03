<?php
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
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Controller_CategoryController extends Tx_News_Controller_NewsController {

	const SIGNAL_CATEGORY_LIST_ACTION = 'listAction';

	/**
	 * Page uid
	 *
	 * @var integer
	 */
	protected $pageUid = 0;

	/**
	 * @var Tx_News_Domain_Repository_NewsRepository
	 */
	protected $newsRepository;

	/**
	 * @var Tx_News_Domain_Repository_CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Inject a category repository to enable DI
	 *
	 * @param Tx_News_Domain_Repository_CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(Tx_News_Domain_Repository_CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * List categories
	 *
	 * @param array $overwriteDemand
	 * @return void
	 */
	public function listAction(array $overwriteDemand = NULL) {
		$demand = $this->createDemandObjectFromSettings($this->settings);

		if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== NULL) {
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}

		$idList = explode(',', $this->settings['categories']);

		$assignedValues = array(
			'categories' => $this->categoryRepository->findTree($idList),
			'overwriteDemand' => $overwriteDemand,
			'demand' => $demand,
		);

		$this->emitActionSignal('CategoryController', self::SIGNAL_CATEGORY_LIST_ACTION, $assignedValues);
		$this->view->assignMultiple($assignedValues);
	}

}
