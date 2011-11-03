<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Administration controller
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Controller_AdministrationController extends Tx_News_Controller_NewsController {

	/**
	 * @var Tx_News_Domain_Repository_NewsRepository
	 */
	protected $newsRepository;

	/**
	 * @var Tx_News_Domain_Repository_CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var Tx_Extbase_Configuration_ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * Inject a news repository to enable DI
	 *
	 * @param Tx_News_Domain_Repository_NewsRepository $newsRepository
	 * @return void
	 */
	public function injectNewsRepository(Tx_News_Domain_Repository_NewsRepository $newsRepository) {
		$this->newsRepository = $newsRepository;
	}

	/**
	 * Inject a news repository to enable DI
	 *
	 * @param Tx_News_Domain_Repository_NewsRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(Tx_News_Domain_Repository_CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 *
	 * @param Tx_News_Domain_Model_AdministrationDemand $demand
	 * @dontvalidate  $demand
	 * @return void
	 */
	public function indexAction(Tx_News_Domain_Model_AdministrationDemand $demand = NULL) {
		if (is_null($demand)) {
			$demand = $this->objectManager->get('Tx_News_Domain_Model_AdministrationDemand');
		}

		$demand = $this->createDemandObjectFromSettings($demand);

		$this->view->assignMultiple(array(
			'demand' => $demand,
			'news' => $this->newsRepository->findDemanded($demand),
			'categories' => $this->categoryRepository->findByPid(t3lib_div::_GET('id')),
		));
	}

	/**
	 * Redirect to form to create new news record which is
	 * all done by tceforms.
	 *
	 * @return void
	 */
	public function newAction() {
		$pageId = (int)t3lib_div::_GET('id');

		$returnUrl = 'mod.php?M=web_NewsTxNewsM2&id=' . $pageId;
		$url = 'alt_doc.php?edit[tx_news_domain_model_news][' . $pageId . ']=new&returnUrl=' . urlencode($returnUrl);

		t3lib_utility_Http::redirect($url);
	}

	/**
	 * Create the demand object which define which records will get shown
	 *
	 * @param Tx_News_Domain_Model_AdministrationDemand $demand
	 * @return Tx_News_Domain_Model_NewsDemand
	 */
	protected function createDemandObjectFromSettings(Tx_News_Domain_Model_AdministrationDemand $demand) {

		/**
		 * @var $demand Tx_News_Domain_Model_NewsDemand
		 */
//		$demand = $this->objectManager->get('Tx_News_Domain_Model_NewsDemand');
//
		$demand->setCategories($demand->getSelectedCategories());
//		$demand->setCategoryConjunction('AND');
//
//		$demand->setTopNewsRestriction($settings['topNewsRestriction']);
//		$demand->setTimeRestriction($settings['timeRestriction']);
//		$demand->setArchiveRestriction($settings['archiveRestriction']);
//
//		if ($settings['orderBy']) {
//			$demand->setOrder($settings['orderBy'] . ' ' . $settings['orderDirection']);
//		}
//
//		$demand->setTopNewsFirst($settings['topNewsFirst']);
//
//		$demand->setLimit($settings['limit']);
//		$demand->setOffset($settings['offset']);
//
//		$demand->setSearchFields($settings['search']['fields']);
//		$demand->setDateField($settings['dateField']);
//
		$demand->setStoragePage(Tx_News_Utility_Page::extendPidListByChildren(t3lib_div::_GET('id'), (int)$demand->getRecursive()));
		return $demand;
	}
}

?>