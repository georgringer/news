<?php
namespace GeorgRinger\News\Controller;

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

use GeorgRinger\News\Utility\Cache;
use GeorgRinger\News\Utility\Page;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller of news records
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class NewsController extends NewsBaseController {

	const SIGNAL_NEWS_LIST_ACTION = 'listAction';
	const SIGNAL_NEWS_DETAIL_ACTION = 'detailAction';
	const SIGNAL_NEWS_DATEMENU_ACTION = 'dateMenuAction';
	const SIGNAL_NEWS_SEARCHFORM_ACTION = 'searchFormAction';
	const SIGNAL_NEWS_SEARCHRESULT_ACTION = 'searchResultAction';

	/**
	 * @var \GeorgRinger\News\Domain\Repository\NewsRepository
	 */
	protected $newsRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/** @var array */
	protected $ignoredSettingsForOverride = array('demandClass', 'orderByAllowed');

	/**
	 * Inject a news repository to enable DI
	 *
	 * @param \GeorgRinger\News\Domain\Repository\NewsRepository $newsRepository
	 * @return void
	 */
	public function injectNewsRepository(\GeorgRinger\News\Domain\Repository\NewsRepository $newsRepository) {
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
		// Only do this in Frontend Context
		if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
			// We only want to set the tag once in one request, so we have to cache that statically if it has been done
			static $cacheTagsSet = FALSE;

			/** @var $typoScriptFrontendController \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController  */
			$typoScriptFrontendController = $GLOBALS['TSFE'];
			if (!$cacheTagsSet) {
				$typoScriptFrontendController->addCacheTags(array('tx_news'));
				$cacheTagsSet = TRUE;
			}
		}

	}

	/**
	 * Create the demand object which define which records will get shown
	 *
	 * @param array $settings
	 * @param string $class optional class which must be an instance of \GeorgRinger\News\Domain\Model\Dto\NewsDemand
	 * @return \GeorgRinger\News\Domain\Model\Dto\NewsDemand
	 */
	protected function createDemandObjectFromSettings($settings, $class = 'GeorgRinger\\News\\Domain\\Model\\Dto\\NewsDemand') {
		$class = isset($settings['demandClass']) && !empty($settings['demandClass']) ? $settings['demandClass'] : $class;

		/* @var $demand \GeorgRinger\News\Domain\Model\Dto\NewsDemand */
		$demand = $this->objectManager->get($class, $settings);
		if (!$demand instanceof \GeorgRinger\News\Domain\Model\Dto\NewsDemand) {
			throw new \UnexpectedValueException(
				sprintf('The demand object must be an instance of \GeorgRinger\\News\\Domain\\Model\\Dto\\NewsDemand, but %s given!', $class),
				1423157953);
		}

		$demand->setCategories(GeneralUtility::trimExplode(',', $settings['categories'], TRUE));
		$demand->setCategoryConjunction($settings['categoryConjunction']);
		$demand->setIncludeSubCategories($settings['includeSubCategories']);
		$demand->setTags($settings['tags']);

		$demand->setTopNewsRestriction($settings['topNewsRestriction']);
		$demand->setTimeRestriction($settings['timeRestriction']);
		$demand->setTimeRestrictionHigh($settings['timeRestrictionHigh']);
		$demand->setArchiveRestriction($settings['archiveRestriction']);
		$demand->setExcludeAlreadyDisplayedNews($settings['excludeAlreadyDisplayedNews']);

		if ($settings['orderBy']) {
			$demand->setOrder($settings['orderBy'] . ' ' . $settings['orderDirection']);
		}
		$demand->setOrderByAllowed($settings['orderByAllowed']);

		$demand->setTopNewsFirst($settings['topNewsFirst']);

		$demand->setLimit($settings['limit']);
		$demand->setOffset($settings['offset']);

		$demand->setSearchFields($settings['search']['fields']);
		$demand->setDateField($settings['dateField']);
		$demand->setMonth($settings['month']);
		$demand->setYear($settings['year']);


		$demand->setStoragePage(Page::extendPidListByChildren($settings['startingpoint'],
			$settings['recursive']));
		return $demand;
	}

	/**
	 * Overwrites a given demand object by an propertyName =>  $propertyValue array
	 *
	 * @param \GeorgRinger\News\Domain\Model\Dto\NewsDemand $demand
	 * @param array $overwriteDemand
	 * @return \GeorgRinger\News\Domain\Model\Dto\NewsDemand
	 */
	protected function overwriteDemandObject($demand, $overwriteDemand) {

		foreach($this->ignoredSettingsForOverride as $property) {
			unset($overwriteDemand[$property]);
		}

		foreach ($overwriteDemand as $propertyName => $propertyValue) {
			\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
		}
		return $demand;
	}

	/**
	 * Output a list view of news
	 *
	 * @param array $overwriteDemand
	 * @return void
	 */
	public function listAction(array $overwriteDemand = NULL) {
		$demand = $this->createDemandObjectFromSettings($this->settings);

		if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== NULL) {
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}
		$newsRecords = $this->newsRepository->findDemanded($demand);

		$assignedValues = array(
			'news' => $newsRecords,
			'overwriteDemand' => $overwriteDemand,
			'demand' => $demand,
		);

		$this->emitActionSignal('NewsController', self::SIGNAL_NEWS_LIST_ACTION, $assignedValues);
		$this->view->assignMultiple($assignedValues);

		Cache::addPageCacheTagsByDemandObject($demand);
	}

	/**
	 * Single view of a news record
	 *
	 * @param \GeorgRinger\News\Domain\Model\News $news news item
	 * @param integer $currentPage current page for optional pagination
	 * @return void
	 */
	public function detailAction(\GeorgRinger\News\Domain\Model\News $news = NULL, $currentPage = 1) {
		if (is_null($news)) {
			$previewNewsId = ((int)$this->settings['singleNews'] > 0) ? $this->settings['singleNews'] : 0;
			if ($this->request->hasArgument('news_preview')) {
				$previewNewsId = (int)$this->request->getArgument('news_preview');
			}

			if ($previewNewsId > 0) {
				if ($this->isPreviewOfHiddenRecordsEnabled()) {
					$news = $this->newsRepository->findByUid($previewNewsId, FALSE);
				} else {
					$news = $this->newsRepository->findByUid($previewNewsId);
				}
			}
		}

		if (is_a($news, 'GeorgRinger\\News\\Domain\\Model\\News') && $this->settings['detail']['checkPidOfNewsRecord']) {
			$news = $this->checkPidOfNewsRecord($news);
		}

		if (is_null($news) && isset($this->settings['detail']['errorHandling'])) {
			$this->handleNoNewsFoundError($this->settings['detail']['errorHandling']);
		}

		$assignedValues = array(
			'newsItem' => $news,
			'currentPage' => (int)$currentPage,
			'demand' => $this->createDemandObjectFromSettings($this->settings),
		);

		$this->emitActionSignal('NewsController', self::SIGNAL_NEWS_DETAIL_ACTION, $assignedValues);
		$this->view->assignMultiple($assignedValues);

		Page::setRegisterProperties($this->settings['detail']['registerProperties'], $news);
		if (!is_null($news) && is_a($news, 'GeorgRinger\\News\\Domain\\Model\\News')) {
			Cache::addCacheTagsByNewsRecords(array($news));
		}
	}

	/**
	 * Checks if the news pid could be found in the startingpoint settings of the detail plugin and
	 * if the pid could not be found it return NULL instead of the news object.
	 *
	 * @param \GeorgRinger\News\Domain\Model\News $news
	 * @return NULL|\GeorgRinger\News\Domain\Model\News
	 */
	protected function checkPidOfNewsRecord(\GeorgRinger\News\Domain\Model\News $news) {
		$allowedStoragePages = GeneralUtility::trimExplode(
			',',
			Page::extendPidListByChildren(
				$this->settings['startingpoint'],
				$this->settings['recursive']
			),
			TRUE
		);
		if (count($allowedStoragePages) > 0 && !in_array($news->getPid(), $allowedStoragePages)) {
			$this->signalSlotDispatcher->dispatch(
				__CLASS__,
				'checkPidOfNewsRecordFailedInDetailAction',
				array(
					'news' => $news,
					'newsController' => $this
				)
			);
			$news = NULL;
		}
		return $news;
	}

	/**
	 * Checks if preview is enabled either in TS or FlexForm
	 *
	 * @return bool
	 */
	protected function isPreviewOfHiddenRecordsEnabled() {
		if (!empty($this->settings['previewHiddenRecords']) && $this->settings['previewHiddenRecords'] == 2) {
			$previewEnabled = !empty($this->settings['enablePreviewOfHiddenRecords']);
		} else {
			$previewEnabled = !empty($this->settings['previewHiddenRecords']);
		}
		return $previewEnabled;
	}

	/**
	 * Render a menu by dates, e.g. years, months or dates
	 *
	 * @param array $overwriteDemand
	 * @return void
	 */
	public function dateMenuAction(array $overwriteDemand = NULL) {
		$demand = $this->createDemandObjectFromSettings($this->settings);

		// It might be that those are set, @see http://forge.typo3.org/issues/44759
		$demand->setLimit(0);
		$demand->setOffset(0);
			// @todo: find a better way to do this related to #13856
		if (!$dateField = $this->settings['dateField']) {
			$dateField = 'datetime';
		}
		$demand->setOrder($dateField . ' ' . $this->settings['orderDirection']);
		$newsRecords = $this->newsRepository->findDemanded($demand);

		$demand->setOrder($this->settings['orderDirection']);
		$statistics = $this->newsRepository->countByDate($demand);

		$assignedValues = array(
			'listPid' => ($this->settings['listPid'] ? $this->settings['listPid'] : $GLOBALS['TSFE']->id),
			'dateField' => $dateField,
			'data' => $statistics,
			'news' => $newsRecords,
			'overwriteDemand' => $overwriteDemand,
			'demand' => $demand,
		);

		$this->emitActionSignal('NewsController', self::SIGNAL_NEWS_DATEMENU_ACTION, $assignedValues);
		$this->view->assignMultiple($assignedValues);
	}

	/**
	 * Display the search form
	 *
	 * @param \GeorgRinger\News\Domain\Model\Dto\Search $search
	 * @param array $overwriteDemand
	 * @return void
	 */
	public function searchFormAction(\GeorgRinger\News\Domain\Model\Dto\Search $search = NULL, array $overwriteDemand = array()) {
		$demand = $this->createDemandObjectFromSettings($this->settings);
		if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== NULL) {
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}

		if (is_null($search)) {
			$search = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\Dto\\Search');
		}
		$demand->setSearch($search);

		$assignedValues = array(
			'search' => $search,
			'overwriteDemand' => $overwriteDemand,
			'demand' => $demand,
		);

		$this->emitActionSignal('NewsController', self::SIGNAL_NEWS_SEARCHFORM_ACTION, $assignedValues);
		$this->view->assignMultiple($assignedValues);
	}

	/**
	 * Displays the search result
	 *
	 * @param \GeorgRinger\News\Domain\Model\Dto\Search $search
	 * @param array $overwriteDemand
	 * @return void
	 */
	public function searchResultAction(\GeorgRinger\News\Domain\Model\Dto\Search $search = NULL, array $overwriteDemand = array()) {
		$demand = $this->createDemandObjectFromSettings($this->settings);
		if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== NULL) {
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}

		if (!is_null($search)) {
			$search->setFields($this->settings['search']['fields']);
			$search->setDateField($this->settings['dateField']);
		}
		$demand->setSearch($search);

		$assignedValues = array(
			'news' => $this->newsRepository->findDemanded($demand),
			'overwriteDemand' => $overwriteDemand,
			'search' => $search,
			'demand' => $demand,
		);

		$this->emitActionSignal('NewsController', self::SIGNAL_NEWS_SEARCHRESULT_ACTION, $assignedValues);
		$this->view->assignMultiple($assignedValues);
	}

	/***************************************************************************
	 * helper
	 **********************/

	/**
	 * Injects the Configuration Manager and is initializing the framework settings
	 *
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager Instance of the Configuration Manager
	 * @return void
	 */
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;

		$tsSettings = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
				'news',
				'news_pi1'
			);
		$originalSettings = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
			);

		$propertiesNotAllowedViaFlexForms = array('orderByAllowed');
		foreach($propertiesNotAllowedViaFlexForms as $property) {
			$originalSettings[$property] = $tsSettings['settings'][$property];
		}


		// Use stdWrap for given defined settings
		if (isset($originalSettings['useStdWrap']) && !empty($originalSettings['useStdWrap'])) {
			/** @var  \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService */
			$typoScriptService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\TypoScriptService');
			$typoScriptArray = $typoScriptService->convertPlainArrayToTypoScriptArray($originalSettings);
			$stdWrapProperties = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $originalSettings['useStdWrap'], TRUE);
			foreach ($stdWrapProperties as $key) {
				if (is_array($typoScriptArray[$key . '.'])) {
					$originalSettings[$key] = $this->configurationManager->getContentObject()->stdWrap(
							$originalSettings[$key],
							$typoScriptArray[$key . '.']
					);
				}
			}
		}

					// start override
		if (isset($tsSettings['settings']['overrideFlexformSettingsIfEmpty'])) {
			/** @var \GeorgRinger\News\Utility\TypoScript $typoScriptUtility */
			$typoScriptUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('GeorgRinger\\News\\Utility\\TypoScript');
			$originalSettings = $typoScriptUtility->override($originalSettings, $tsSettings);
		}

		$this->settings = $originalSettings;
	}

	/**
	 * Injects a view.
	 * This function is for testing purposes only.
	 *
	 * @param \TYPO3\CMS\Fluid\View\TemplateView $view the view to inject
	 * @return void
	 */
	public function setView(\TYPO3\CMS\Fluid\View\TemplateView $view) {
		$this->view = $view;
	}

}
