<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
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
 * Controller of news records
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Controller_NewsController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_News2_Domain_Repository_NewsRepository
	 */
	protected $newsRepository;

	/**
	 *
	 * @param Tx_News2_Domain_Repository_NewsRepository $newsRepository
	 */
	public function injectNewsRepository(Tx_News2_Domain_Repository_NewsRepository $newsRepository) {
		$this->newsRepository = $newsRepository;
	}

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	public function initializeAction() {
//		$this->newsRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_NewsRepository');

		$this->newsRepository->setCategories($this->settings['category']);
		$this->newsRepository->setCategorySettings($this->settings['categoryMode']);
		$this->newsRepository->setTopNewsRestriction($this->settings['topNews']);
		$this->newsRepository->setArchiveSettings($this->settings['archive']);
		$this->newsRepository->setOrder($this->settings['orderBy'] . ' ' . $this->settings['orderAscDesc']);
		$this->newsRepository->setOrderRespectTopNews($this->settings['orderByRespectTopNews']);
		$this->newsRepository->setLimit($this->settings['limit']);
		$this->newsRepository->setOffset($this->settings['offset']);
		$this->newsRepository->setSearchFields($this->settings['search']['fields']);
		$this->newsRepository->setStoragePage(Tx_News2_Service_RecursivePidListService::find($this->settings['startingpoint'], $this->settings['recursive']));

		if (isset($this->settings['format'])) {
			$this->request->setFormat($this->settings['format']);
		}
		$this->requestOverrule();
//		t3lib_div::print_array($this->settings);
	}


	/**
	 * Output a list view of news
	 *
	 * return Tx_News2_Domain_Repository_NewsRepository news
	 */
	public function listAction() {
		$newsRecords = $this->newsRepository->findList();

		$this->view->assign('news', $newsRecords);
	}

	/**
	 *
	 * Search for news
	 *
	 * @param Tx_News2_Domain_Model_Search $search
	 */
	public function searchAction(Tx_News2_Domain_Model_Search $search = NULL) {
		if ($search === NULL) {
			$search = new Tx_News2_Domain_Model_Search();
		} else {
			var_dump($search);
		}
		
		/** @var Tx_News2_Domain_Repository_CategoryRepository */
		$categoryRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_CategoryRepository');
		$categoryRepository->setUidList($this->settings['category']);
		$categories = $categoryRepository->findByIdList();
		$search->setCategory($categories);
		
//		var_dump($search->getCategory());
		
		$this->view->assign('search', $search);
	}

	/**
	 * Search Result
	 *
	 * @param Tx_News2_Domain_Model_Search $search
	 */
	public function searchResultAction(Tx_News2_Domain_Model_Search $search = NULL) {
		$this->view->assign('search', $search);

			// if a search is submitted
		if($search !== NULL) {
			var_dump($search->getCategory());
			
			$newsRecords = $this->newsRepository->findBySearch($search);
			$this->view->assign('news', $newsRecords);


		}
	}

	/**
	 * Single view of a news record
	 *
	 * @param Tx_News2_Domain_Model_News $news
	 */
	public function detailAction(Tx_News2_Domain_Model_News $news = NULL) {
		$this->view->assign('newsItem', $news);

		if ($this->settings['detail']['titleInMetaTags'] == 1) {
			$this->renderTitle($news->getTitle());
		}
	}

	/***************************************************************************
	 * helper
	 **********************/

	/**
	 * Set the meta title
	 *
	 * @param string $title
	 */
	private function renderTitle($title) {
		$GLOBALS['TSFE']->page['title'] = $title;
		$GLOBALS['TSFE']->indexedDocTitle = $title;
		$GLOBALS['TSFE']->pSetup['meta.']['DESCRIPTION.'] = NULL;
		$GLOBALS['TSFE']->pSetup['meta.']['DESCRIPTION'] = $title;
	}

	/**
	 * Localizes the given key by forwarding it to the Tx_Extbase_Utility_Localization::translate method.
	 *
	 * @param string $key
	 * @param array $arguments
	 * @return string
	 */
	protected function localize($key, array $arguments = NULL) {
		return Tx_Extbase_Utility_Localization::translate($key, 'news2', $arguments);
	}

	/**
	 * Allow overruling of settings by get request
	 */
	protected function requestOverrule() {
		$requests = $this->request->getArguments();

			// category restriction
		if (isset($requests['category']) && $this->accessCheck('allowCategoryRestrictionFromGetParams')) {
			$this->newsRepository->setAdditionalCategories($requests['category']);
		}

			// ordering
		if (isset($requests['order']) && $this->accessCheck('allowOrderFromGetParams')) {
			$order = $requests['order'];
			if (isset($requests['orderDirection'])) {
				$order .= ' ' . $requests['orderDirection'];
			}

			$this->newsRepository->setOrder($order);

		}

	}

	/**
	 * Check access which can be set for each action by using <action>.<setting> = 1
	 *
	 * @param  string $setting name of the setting
	 * @return bool
	 */
	protected function accessCheck($setting) {
		$access = FALSE;
			// remove the Action from the method: listAction > list
		$actionName = str_replace('Action', '', $this->actionMethodName);

		if ($this->settings[$actionName][$setting] == 1) {
			$access = TRUE;
		}

		return $access;
	}


}

?>