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
	 * @var Tx_News2_Domain_Model_NewsRepository
	 */
	protected $newsRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->newsRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_NewsRepository');

		$this->newsRepository->setCategories($this->settings['category']);
		$this->newsRepository->setCategorySettings($this->settings['categoryMode']);
		$this->newsRepository->setTopNewsRestriction($this->settings['topNews']);
		$this->newsRepository->setArchiveSettings($this->settings['archive']);
		$this->newsRepository->setOrder($this->settings['orderBy'] . ' ' . $this->settings['orderAscDesc']);
		$this->newsRepository->setOrderRespectTopNews($this->settings['orderByRespectTopNews']);
		$this->newsRepository->setLimit($this->settings['limit']);
		$this->newsRepository->setOffset($this->settings['offset']);
		$this->newsRepository->setSearchFields($this->settings['search']['fields']);
		$this->newsRepository->setStoragePage($this->recursivePidList($this->settings['startingpoint'], $this->settings['startingpoint']['recursive']));


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

		$this->splitNewsRecordsInPartials($newsRecords);
	}

	/**
	 *
	 * Search for news
	 *
	 * @param string $searchString
	 */
	public function searchAction($searchString = NULL) {
		$this->view->assign('searchString', $searchString);
	}

	/**
	 * Search for news
	 *
	 * @param string $searchString
	 */
	public function searchResultAction($searchString = NULL) {
		$this->view->assign('searchString', $searchString);

		if($searchString !== NULL && trim($searchString !== '')) {

			$newsRecords = $this->newsRepository->findBySearch($searchString);
			$this->splitNewsRecordsInPartials($newsRecords);


		} else {

			echo 'no';
//			$this->flashMessages->add(('Please Enter A ProductNumber Or Title'));
		}
	}

	/**
	 * Single view of a news record
	 *
	 * @param Tx_News2_Domain_Model_News $news
	 */
	public function detailAction(Tx_News2_Domain_Model_News $news) {
		$this->view->assign('newsItem', $news);

		if ($this->settings['detail']['titleInMetaTags'] == 1) {
			$this->renderTitle($news->getTitle());
		}
	}

	/***************************************************************************
	 * helper
	 **********************/

	/**
	 * Split news records in partials and assign those to custom views
	 * 
	 * @param array $newsRecords news records
	 */
	private function splitNewsRecordsInPartials(array $newsRecords) {
		$templateSwitch = t3lib_div::intExplode('|', $this->settings['templateSwitch'], TRUE);

			// no template switch used
		if (count($templateSwitch) == 0) {
			$this->view->assign('news', $newsRecords);
		} else {
			$countAll = count($templateSwitch);
			$countTemplateSuffix = 1;

			foreach($templateSwitch as $internalCount => $templatePartialItems) {
				$assignedNews = array();

					// walk through count in single templatePartial
				for($i = 0; $i < $templatePartialItems; $i++) {
						// attach news record if there is one to attach
					if (!empty($newsRecords)) {
						$assignedNews[] = array_shift($newsRecords);
					}
				}

					// check if last template partial is reached to fill all other news records
				if ($countAll == $internalCount + 1) {
					foreach($newsRecords as $newsRecord) {
						$assignedNews[] = $newsRecord;
					}
				}

					// assign news partials to custom view
				$this->view->assign('news_' . $countTemplateSuffix, $assignedNews);
				$countTemplateSuffix++;
			}
		}
	}



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

	protected function recursivePidList($pidlist = '', $recursive = 0) {
		if ($recursive == 0) {
			return $pidList;
		}

		$local_cObj = t3lib_div::makeInstance('tslib_cObj');

		$recursive = t3lib_div::intInRange($recursive, 0);

		$pid_list_arr = array_unique(t3lib_div::trimExplode(',', $pidlist, 1));
		$pid_list     = array();

		foreach($pid_list_arr as $val) {
			$val = t3lib_div::intInRange($val, 0);
			if ($val) {
				$_list = $local_cObj->getTreeList(-1 * $val, $recursive);
				if ($_list) {
					$pid_list[] = $_list;
				}
			}
		}

		$extendedPidList = implode(',', $pid_list);
		return $extendedPidList;
	}
}

?>