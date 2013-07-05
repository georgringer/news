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
	 * @var Tx_Extbase_Configuration_ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * Function will be called before every other action
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->pageUid = (int)t3lib_div::_GET('id');
		parent::initializeAction();
	}

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
	 * @param Tx_News_Domain_Repository_CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(Tx_News_Domain_Repository_CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * Main action for administration
	 *
	 * @param Tx_News_Domain_Model_Dto_AdministrationDemand $demand
	 * @dontvalidate  $demand
	 * @return void
	 */
	public function indexAction(Tx_News_Domain_Model_Dto_AdministrationDemand $demand = NULL) {
		if (is_null($demand)) {
			$demand = $this->objectManager->get('Tx_News_Domain_Model_Dto_AdministrationDemand');

				// Preselect by TsConfig (e.g. tx_news.module.preselect.topNewsRestriction = 1)
			$tsConfig = t3lib_BEfunc::getPagesTSconfig($this->pageUid);
			if (isset($tsConfig['tx_news.']['module.']['preselect.'])
					&& is_array($tsConfig['tx_news.']['module.']['preselect.'])) {
				unset($tsConfig['tx_news.']['module.']['preselect.']['orderByAllowed']);

				foreach ($tsConfig['tx_news.']['module.']['preselect.'] as $propertyName => $propertyValue) {
					Tx_Extbase_Reflection_ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
				}
			}
		}
		$demand = $this->createDemandObjectFromSettings($demand);

		$categories = $this->categoryRepository->findParentCategoriesByPid($this->pageUid);
		$idList = array();
		foreach ($categories as $c) {
			$idList[] = $c->getUid();
		}

		$this->view->assignMultiple(array(
			'page' => $this->pageUid,
			'demand' => $demand,
			'news' => $this->newsRepository->findDemanded($demand, FALSE),
			'categories' => $this->categoryRepository->findTree($idList),
			'dateformat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy']
		));
	}

	/**
	 * Shows a page tree including count of news + category records
	 *
	 * @param integer $treeLevel
	 * @return void
	 */
	public function newsPidListingAction($treeLevel = 2) {
		$tree = Tx_News_Utility_Page::pageTree($this->pageUid, $treeLevel);

		$rawTree = array();
		foreach ($tree->tree as $row) {
			$this->countRecordsOnPage($row);
			$rawTree[] = $row;
		}

		$this->view->assignMultiple(array(
			'tree' => $rawTree,
			'treeLevel' => $treeLevel,
		));
	}

	/**
	 * Redirect to form to create a news record
	 *
	 * @return void
	 */
	public function newNewsAction() {
		$this->redirectToCreateNewRecord('tx_news_domain_model_news');
	}

	/**
	 * Redirect to form to create a category record
	 *
	 * @return void
	 */
	public function newCategoryAction() {
		$this->redirectToCreateNewRecord('tx_news_domain_model_category');
	}

	/**
	 * Create the demand object which define which records will get shown
	 *
	 * @param Tx_News_Domain_Model_Dto_AdministrationDemand $demand
	 * @return Tx_News_Domain_Model_Dto_NewsDemand
	 */
	protected function createDemandObjectFromSettings(Tx_News_Domain_Model_Dto_AdministrationDemand $demand) {
		$demand->setCategories($demand->getSelectedCategories());
		$demand->setOrder($demand->getSortingField() . ' ' . $demand->getSortingDirection());
		$demand->setStoragePage(Tx_News_Utility_Page::extendPidListByChildren($this->pageUid, (int)$demand->getRecursive()));
		$demand->setOrderByAllowed($this->settings['orderByAllowed']);

		if ((int)$demand->getLimit() === 0) {
			$demand->setLimit(50);
		}

		// Ensure that always a storage page is set
		if ((int)$demand->getStoragePage() === 0) {
			$demand->setStoragePage('-3');
		}

		return $demand;
	}

	/**
	 * Update page record array with count of news & category records
	 *
	 * @param array $row page record
	 * @return void
	 */
	private function countRecordsOnPage(array &$row) {
		$pageUid = (int)$row['row']['uid'];

		/* @var $db t3lib_DB */
		$db = $GLOBALS['TYPO3_DB'];

		$row['countNews'] = $db->exec_SELECTcountRows(
			'*',
			'tx_news_domain_model_news',
			'pid=' . $pageUid . t3lib_BEfunc::BEenableFields('tx_news_domain_model_news'));
		$row['countCategories'] = $db->exec_SELECTcountRows(
			'*',
			'tx_news_domain_model_category',
			'pid=' . $pageUid . t3lib_BEfunc::BEenableFields('tx_news_domain_model_category'));

		$row['countNewsAndCategories'] = ($row['countNews'] + $row['countCategories']);
	}

	/**
	 * Redirect to tceform creating a new record
	 *
	 * @param string $table table name
	 * @return void
	 */
	private function redirectToCreateNewRecord($table) {
		$pid = $this->pageUid;
		if ($pid === 0) {
			$tsConfig = t3lib_BEfunc::getPagesTSconfig($this->pageUid);
			if (isset($tsConfig['tx_news.']['module.']['defaultPid.'])
				&& is_array($tsConfig['tx_news.']['module.']['defaultPid.'])
				&& isset($tsConfig['tx_news.']['module.']['defaultPid.'][$table])
			) {
				$pid = (int)$tsConfig['tx_news.']['module.']['defaultPid.'][$table];
			}
		}

		$returnUrl = 'mod.php?M=web_NewsTxNewsM2&id=' . $this->pageUid;
		$url = 'alt_doc.php?edit[' . $table . '][' . $pid . ']=new&returnUrl=' . urlencode($returnUrl);

		t3lib_utility_Http::redirect($url);
	}
}

?>