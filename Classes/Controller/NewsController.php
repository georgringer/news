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

	protected function createDemandObjectFromSettings($settings) {
		$demandObject = $this->objectManager->get('Tx_News2_Domain_Model_NewsDemand');

		$demandObject->setCategories($this->settings['category']);
		$demandObject->setCategorySetting($this->settings['categoryMode']);
		$demandObject->setTopNewsSetting($this->settings['topNews']);
		$demandObject->setArchiveSetting($this->settings['archive']);
		$demandObject->setOrder($this->settings['orderBy'] . ' ' . $this->settings['orderAscDesc']);
		$demandObject->setOrderRespectTopNews($this->settings['orderByRespectTopNews']);
		$demandObject->setLimit($this->settings['limit']);
		$demandObject->setOffset($this->settings['offset']);
		$demandObject->setSearchFields($this->settings['search']['fields']);
		$demandObject->setStoragePage(Tx_News2_Service_RecursivePidListService::find($this->settings['startingpoint'],
			$this->settings['recursive']));

		return $demandObject;
	}

	/**
	 * Output a list view of news
	 *
	 * return string the Rendered view
	 */
	public function listAction() {
			// If the TypoScript config is not set return an error
		if (!$this->settings['list']) {
			$this->flashMessages->add($this->localize('list.settings.notfound'), t3lib_FlashMessage::ERROR);
		} else {
			$demand = $this->createDemandObjectFromSettings($this->settings);
			$newsRecords = $this->newsRepository->findDemanded($demand);
			$this->view->assign('news', $newsRecords);
		}

	}

	/**
	 * Displays the news search form
	 *
	 * @return string the Rendered view
	 * @return void
	 */
	public function searchAction() {
		$demand = $this->createDemandObjectFromSettings($this->settings);
		$this->view->assign('demand', $demand);
	}

	/**
	 * Displays the news search result
	 *
	 * @param Tx_News2_Domain_Model_NewsDemand $demand
	 * @return string the Rendered view
	 * @return void
	 */
	public function searchResultAction(Tx_News2_Domain_Model_NewsDemand $demand) {
		$this->view->assignMultiple(array(
			'demand' => $demand,
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
		if (!is_null($news)) {
			$this->view->assign('newsItem', $news);
			if ($this->settings['detail']['titleInMetaTags'] == 1) {
				$this->renderTitle($news->getTitle());
			}
		}
	}

	/**
	 * Render a menu by dates, e.g. years, months or dates
	 *
	 * @return void
	 */
	public function dateMenuAction() {
		$demand = $this->createDemandObjectFromSettings($this->settings);
		$newsRecords = $this->newsRepository->findDemanded($demand);
		$this->view->assign('news', $newsRecords);
	}

	/***************************************************************************
	 * helper
	 **********************/

	/**
	 * Set the meta title
	 *
	 * @param string $title
	 * @return void
	 */
	protected function renderTitle($title) {
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