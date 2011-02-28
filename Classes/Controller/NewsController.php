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
	 * @var Tx_Extbase_Configuration_ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * Inject a news repository to enable DI
	 *
	 * @param Tx_News2_Domain_Repository_NewsRepository $newsRepository
	 * @return void
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
		if (isset($this->settings['format'])) {
			$this->request->setFormat($this->settings['format']);
		}
	}

	/**
	 * Create the demand object which define which records will get shown
	 *
	 * @param array $settings
	 * @return Tx_News2_Domain_Model_NewsDemand
	 */
	protected function createDemandObjectFromSettings($settings) {
		/** @var $demand Tx_News2_Domain_Model_NewsDemand */
		$demand = $this->objectManager->get('Tx_News2_Domain_Model_NewsDemand');

		$demand->setCategories(t3lib_div::trimExplode(',', $settings['category'], TRUE));
		$demand->setCategorySetting($settings['categoryMode']);
		$demand->setTopNewsSetting($settings['topNews']);
		$demand->setLatestTimeLimit($settings['timeLimit']);
		$demand->setArchiveSetting($settings['archive']);
		$demand->setOrder($settings['orderBy'] . ' ' . $settings['orderAscDesc']);
		$demand->setOrderRespectTopNews($settings['orderByRespectTopNews']);
		$demand->setLimit($settings['limit']);
		$demand->setOffset($settings['offset']);
		$demand->setSearchFields($settings['search']['fields']);
		$demand->setStoragePage(Tx_News2_Utility_Page::extendPidListByChildren($settings['startingpoint'],
			$settings['recursive']));

		return $demand;
	}

	/**
	 * Overwrites a given demand object by an propertyName =>  $propertyValue array
	 *
	 * @param  $demand
	 * @param  $overwriteDemand
	 * @return Tx_News2_Domain_Model_NewsDemand
	 */
	protected function overwriteDemandObject($demand, $overwriteDemand) {
		foreach ($overwriteDemand as $propertyName => $propertyValue) {
			// @todo: consider adding an per mode access check
			$setterMethod = 'set' . ucfirst($propertyName);
			if (method_exists(Tx_News2_Domain_Model_NewsDemand, $setterMethod)) {
				$demand->{$setterMethod}($propertyValue);
			}
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

			// If the TypoScript config is not set return an error
		if (!$this->settings['list']) {
			$this->flashMessageContainer->add(
				Tx_Extbase_Utility_Localization::translate('list.settings.notfound', $this->extensionName),
				 t3lib_FlashMessage::ERROR
			);
		} else {
			$demand = $this->createDemandObjectFromSettings($this->settings);

			if ($overwriteDemand !== NULL) {
				$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
			}

			$newsRecords = $this->newsRepository->findDemanded($demand);
			$this->view->assignMultiple(array(
				'news' => $newsRecords,
				'overwriteDemand' => $overwriteDemand
			));
		}
	}

	/**
	 * Displays the news search form
	 *
	 * @param $search Tx_News2_Domain_Model_Dto_Search
	 * @return void
	 */
	public function searchFormAction(Tx_News2_Domain_Model_Dto_Search $search = NULL) {
		$demand = $this->createDemandObjectFromSettings($this->settings);
		$this->view->assignMultiple(array(
			'demand' => $demand,
			'search' => $search
		));
	}

	/**
	 * Displays the news search result
	 *
	 * @param $search Tx_News2_Domain_Model_Dto_Search
	 * @return void
	 */
	public function searchResultAction(Tx_News2_Domain_Model_Dto_Search $search = NULL) {

		$demand = $this->createDemandObjectFromSettings($this->settings);
		$demand->setSearch($search);
		$this->view->assignMultiple(array(
			'demand' => $demand,
			'search' => $search,
			'news' => $this->newsRepository->findDemanded($demand)
		));
	}

	/**
	 * Single view of a news record
	 *	
	 * @param Tx_News2_Domain_Model_News $news
	 * @return void
	 */
	public function detailAction(Tx_News2_Domain_Model_News $news = NULL) {
		if (isset($this->settings['singleNews']) && (int)$this->settings['singleNews'] > 0) {
			$news = $this->newsRepository->findByUid($this->settings['singleNews']);
		}
		$this->view->assign('newsItem', $news);
	}

	/**
	 * Render a menu by dates, e.g. years, months or dates
	 *
	 * @param array|null $overwriteDemand
	 * @return void
	 */
	public function dateMenuAction(array $overwriteDemand = NULL) {
		$demand = $this->createDemandObjectFromSettings($this->settings);
		// @todo: should be covert by createDemandObjectFromSettings
		$demand->setOrder($this->settings['dateField'] . ' DESC');

		$newsRecords = $this->newsRepository->findDemanded($demand);
		$this->view->assignMultiple(array(
			'listPid' => ($this->settings['listPid'] ? $this->settings['listPid'] : $GLOBALS['TSFE']->id),
			'dateField' => $this->settings['dateField'],
			'news' => $newsRecords,
			'overwriteDemand' => $overwriteDemand
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
				'news2',
				'news2_pi1'
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
}
?>