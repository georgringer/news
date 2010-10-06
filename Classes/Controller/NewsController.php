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
 *
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
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
		$this->newsRepository->setArchiveSettings($this->settings['archive']);
		$this->newsRepository->setOrder($this->settings['orderBy'] . ' ' . $this->settings['orderAscDesc']);
		$this->newsRepository->setOrderRespectTopNews($this->settings['orderByRespectTopNews']);
		$this->newsRepository->setLimit($this->settings['limit']);
		$this->newsRepository->setOffset($this->settings['offset']);
		$this->newsRepository->setSearchFields($this->settings['search']['fields']);


		$requests = $this->request->getArguments();
		if (isset($requests['category']) && $this->accessCheck('allowCategoryRestrictionFromGetParams')) {
			$this->newsRepository->setAdditionalCategories($requests['category']);
		}

		t3lib_div::print_array($this->settings);

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
	 * Output a latest view of news
	 *
	 * return Tx_News2_Domain_Repository_NewsRepository news
	 */
	public function latestAction() {
		$this->newsRepository->setLatestTimeLimit($this->settings['latest']['timeLimit']);

		$newsRecords = $this->newsRepository->findLatest();
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
		$this->renderTitle($news->getTitle());
		$this->view->assign('news', $news);

		$this->view->assign('media', $news->getMedia((boolean)$this->settings['firstMediaIsPreview']));
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
	protected function localize($key, array $arguments=NULL) {
		return Tx_Extbase_Utility_Localization::translate($key, 'news2', $arguments);
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