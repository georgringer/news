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
 * @subpackage tx_news
 */
class Tx_News_Controller_NewsController extends Tx_News_Controller_NewsBaseController {
	/**
	 * @var Tx_News_Domain_Repository_NewsRepository
	 */
	protected $newsRepository;

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
	 * Initializes the current action
	 *
	 * @return void
	 */
	public function initializeAction() {
		if (isset($this->settings['format'])) {
			$this->request->setFormat($this->settings['format']);
		}
	}

	/**
	 * Create the demand object which define which records will get shown
	 *
	 * @param array $settings
	 * @return Tx_News_Domain_Model_NewsDemand
	 */
	protected function createDemandObjectFromSettings($settings) {
		/**
		 * @var $demand Tx_News_Domain_Model_NewsDemand
		 */
		$demand = $this->objectManager->get('Tx_News_Domain_Model_NewsDemand');

		$demand->setCategories(t3lib_div::trimExplode(',', $settings['categories'], TRUE));
		$demand->setCategoryConjunction($settings['categoryConjunction']);

		$demand->setTopNewsRestriction($settings['topNewsRestriction']);
		$demand->setTimeRestriction($settings['timeRestriction']);
		$demand->setArchiveRestriction($settings['archiveRestriction']);

		if ($settings['orderBy']) {
			$demand->setOrder($settings['orderBy'] . ' ' . $settings['orderDirection']);
		}

		$demand->setTopNewsFirst($settings['topNewsFirst']);

		$demand->setLimit($settings['limit']);
		$demand->setOffset($settings['offset']);

		$demand->setSearchFields($settings['search']['fields']);
		$demand->setDateField($settings['dateField']);

		$demand->setStoragePage(Tx_News_Utility_Page::extendPidListByChildren($settings['startingpoint'],
			$settings['recursive']));
		return $demand;
	}

	/**
	 * Overwrites a given demand object by an propertyName =>  $propertyValue array
	 *
	 * @param Tx_News_Domain_Model_NewsDemand $demand
	 * @param array $overwriteDemand
	 * @return Tx_News_Domain_Model_NewsDemand
	 */
	protected function overwriteDemandObject($demand, $overwriteDemand) {
		foreach ($overwriteDemand as $propertyName => $propertyValue) {
			Tx_Extbase_Reflection_ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
		}
		return $demand;
	}

	/**
	 * Output a list view of news
	 *
	 * @param array $overwriteDemand
	 * @return return string the Rendered view
	 */
	public function listAction(array $overwriteDemand = NULL) {
		$demand = $this->createDemandObjectFromSettings($this->settings);

		if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== NULL) {
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}

		$newsRecords = $this->newsRepository->findDemanded($demand);

		$this->view->assignMultiple(array(
			'news' => $newsRecords,
			'overwriteDemand' => $overwriteDemand,
		));
	}

	/**
	 * Single view of a news record
	 *
	 * @param Tx_News_Domain_Model_News $news
	 * @return void
	 */
	public function detailAction(Tx_News_Domain_Model_News $news = NULL) {
		if (isset($this->settings['singleNews']) && (int)$this->settings['singleNews'] > 0) {
			$news = $this->newsRepository->findByUid($this->settings['singleNews']);
		} elseif($this->settings['previewHiddenRecords']) {
			$news = $this->newsRepository->findByUid($this->request->getArgument('news'), FALSE);
		}
		$this->view->assignMultiple(array(
			'newsItem' => $news,
		));
	}

	/**
	 * Render a menu by dates, e.g. years, months or dates
	 *
	 * @param array|null $overwriteDemand
	 * @return void
	 */
	public function dateMenuAction(array $overwriteDemand = NULL) {
		$demand = $this->createDemandObjectFromSettings($this->settings);
			// @todo: find a better way to do this related to #13856
		if (!$dateField = $this->settings['dateField']) {
			$dateField = 'datetime';
		}
		$demand->setOrder($dateField . ' ' . $this->settings['orderDirection']);

		$newsRecords = $this->newsRepository->findDemanded($demand);
		$this->view->assignMultiple(array(
			'listPid' => ($this->settings['listPid'] ? $this->settings['listPid'] : $GLOBALS['TSFE']->id),
			'dateField' => $dateField,
			'news' => $newsRecords,
			'overwriteDemand' => $overwriteDemand,
		));
	}



	/**
	 * Display the search form
	 *
	 * @param Tx_News_Domain_Model_Dto_Search $search
	 * @return void
	 */
	public function searchFormAction(Tx_News_Domain_Model_Dto_Search $search = NULL) {
	    if (is_null($search)) {
			$search = $this->objectManager->get('Tx_News_Domain_Model_Dto_Search');
	    }

	   $this->view->assign('search', $search);
	}

	/**
	 * Displays the search result
	 *
	 * @param Tx_News_Domain_Model_Dto_Search $search
	 * @param array $overwriteDemand
	 * @return void
	 */
	public function searchResultAction(Tx_News_Domain_Model_Dto_Search $search = NULL, array $overwriteDemand = array()) {
		$demand = $this->createDemandObjectFromSettings($this->settings);
		if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== NULL) {
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}

		if (!is_null($search)) {
			$search->setFields($this->settings['search']['fields']);
		}
		$demand->setSearch($search);

		$this->view->assignMultiple(array(
			'news' => $this->newsRepository->findDemanded($demand),
			'overwriteDemand' => $overwriteDemand,
			'search' => $search,
		));
	}



	/***************************************************************************
	 * helper
	 **********************/

	/**
	 * Injects the Configuration Manager and is initializing the framework settings
	 *
	 * @param Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager An instance of the Configuration Manager
	 * @return void
	 */
	public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;

		$tsSettings = $this->configurationManager->getConfiguration(
				Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
				'news',
				'news_pi1'
			);
		$originalSettings = $this->configurationManager->getConfiguration(
				Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
			);

			// start override
		if (isset($tsSettings['settings']['overrideFlexformSettingsIfEmpty'])) {
			$overrideIfEmpty = t3lib_div::trimExplode(',', $tsSettings['settings']['overrideFlexformSettingsIfEmpty'], TRUE);
			foreach ($overrideIfEmpty as $key) {
					// if flexform setting is empty and value is available in TS
				if ((!isset($originalSettings[$key]) || empty($originalSettings[$key]))
						&& isset($tsSettings['settings'][$key])) {
					$originalSettings[$key] = $tsSettings['settings'][$key];
				}
			}
		}

		$this->settings = $originalSettings;
	}

	/**
	 * Injects a view.
	 * This function is for testing purposes only.
	 *
	 * @param Tx_Fluid_View_TemplateView $view the view to inject
	 * @return void
	 */
	public function setView(Tx_Fluid_View_TemplateView $view) {
		$this->view = $view;
	}
}
?>