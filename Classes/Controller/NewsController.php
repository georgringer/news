<?php
namespace GeorgRinger\News\Controller;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Utility\Cache;
use GeorgRinger\News\Utility\Page;
use GeorgRinger\News\Utility\TypoScript;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller of news records
 *
 */
class NewsController extends NewsBaseController
{
    const SIGNAL_NEWS_LIST_ACTION = 'listAction';
    const SIGNAL_NEWS_LIST_SELECTED_ACTION = 'selectedListAction';
    const SIGNAL_NEWS_DETAIL_ACTION = 'detailAction';
    const SIGNAL_NEWS_DATEMENU_ACTION = 'dateMenuAction';
    const SIGNAL_NEWS_SEARCHFORM_ACTION = 'searchFormAction';
    const SIGNAL_NEWS_SEARCHRESULT_ACTION = 'searchResultAction';

    /**
     * @var \GeorgRinger\News\Domain\Repository\NewsRepository
     */
    protected $newsRepository;

    /**
     * @var \GeorgRinger\News\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var \GeorgRinger\News\Domain\Repository\TagRepository
     */
    protected $tagRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /** @var array */
    protected $ignoredSettingsForOverride = ['demandclass', 'orderbyallowed', 'selectedList'];

    /**
     * Original settings without any magic done by stdWrap and skipping empty values
     *
     * @var array
     */
    protected $originalSettings = [];

    /**
     * Inject a news repository to enable DI
     *
     * @param \GeorgRinger\News\Domain\Repository\NewsRepository $newsRepository
     */
    public function injectNewsRepository(\GeorgRinger\News\Domain\Repository\NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Inject a category repository to enable DI
     *
     * @param \GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(\GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Inject a tag repository to enable DI
     *
     * @param \GeorgRinger\News\Domain\Repository\TagRepository $tagRepository
     */
    public function injectTagRepository(\GeorgRinger\News\Domain\Repository\TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Initializes the current action
     *
     */
    public function initializeAction()
    {
        if (isset($this->settings['format'])) {
            $this->request->setFormat($this->settings['format']);
        }
        // Only do this in Frontend Context
        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            // We only want to set the tag once in one request, so we have to cache that statically if it has been done
            static $cacheTagsSet = false;

            /** @var $typoScriptFrontendController \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
            $typoScriptFrontendController = $GLOBALS['TSFE'];
            if (!$cacheTagsSet) {
                $typoScriptFrontendController->addCacheTags(['tx_news']);
                $cacheTagsSet = true;
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
    protected function createDemandObjectFromSettings(
        $settings,
        $class = 'GeorgRinger\\News\\Domain\\Model\\Dto\\NewsDemand'
    ) {
        $class = isset($settings['demandClass']) && !empty($settings['demandClass']) ? $settings['demandClass'] : $class;

        /* @var $demand \GeorgRinger\News\Domain\Model\Dto\NewsDemand */
        $demand = $this->objectManager->get($class, $settings);
        if (!$demand instanceof \GeorgRinger\News\Domain\Model\Dto\NewsDemand) {
            throw new \UnexpectedValueException(
                sprintf('The demand object must be an instance of \GeorgRinger\\News\\Domain\\Model\\Dto\\NewsDemand, but %s given!',
                    $class),
                1423157953);
        }

        $demand->setCategories(GeneralUtility::trimExplode(',', $settings['categories'], true));
        $demand->setCategoryConjunction($settings['categoryConjunction']);
        $demand->setIncludeSubCategories($settings['includeSubCategories']);
        $demand->setTags($settings['tags']);

        $demand->setTopNewsRestriction($settings['topNewsRestriction']);
        $demand->setTimeRestriction($settings['timeRestriction']);
        $demand->setTimeRestrictionHigh($settings['timeRestrictionHigh']);
        $demand->setArchiveRestriction($settings['archiveRestriction']);
        $demand->setExcludeAlreadyDisplayedNews($settings['excludeAlreadyDisplayedNews']);
        $demand->setHideIdList($settings['hideIdList']);

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
    protected function overwriteDemandObject($demand, $overwriteDemand)
    {
        foreach ($this->ignoredSettingsForOverride as $property) {
            unset($overwriteDemand[$property]);
        }

        foreach ($overwriteDemand as $propertyName => $propertyValue) {
            if (in_array(strtolower($propertyName), $this->ignoredSettingsForOverride, true)) {
                continue;
            }
            if ($propertyValue !== '' || $this->settings['allowEmptyStringsForOverwriteDemand']) {
                \TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
            }
        }
        return $demand;
    }

    /**
     * Output a list view of news
     *
     * @param array $overwriteDemand
     */
    public function listAction(array $overwriteDemand = null)
    {
        $this->forwardToDetailActionWhenRequested();

        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== null) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }
        $newsRecords = $this->newsRepository->findDemanded($demand);

        $assignedValues = [
            'news' => $newsRecords,
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
            'categories' => null,
            'tags' => null,
            'settings' => $this->settings,
        ];

        if ($demand->getCategories() !== '') {
            $categoriesList = $demand->getCategories();
            if (!is_array($categoriesList)) {
                $categoriesList = GeneralUtility::trimExplode(',', $categoriesList);
            }
            if (!empty($categoriesList)) {
                $assignedValues['categories'] = $this->categoryRepository->findByIdList($categoriesList);
            }
        }

        if ($demand->getTags() !== '') {
            $tagList = $demand->getTags();
            if (!is_array($tagList)) {
                $tagList = GeneralUtility::trimExplode(',', $tagList);
            }
            if (!empty($tagList)) {
                $assignedValues['tags'] = $this->tagRepository->findByIdList($tagList);
            }
        }
        $assignedValues = $this->emitActionSignal('NewsController', self::SIGNAL_NEWS_LIST_ACTION, $assignedValues);
        $this->view->assignMultiple($assignedValues);

        Cache::addPageCacheTagsByDemandObject($demand);
    }

    /**
     * When list action is called and skipControllerAndAction is set
     * along with a news argument, we forward to detail action.
     */
    protected function forwardToDetailActionWhenRequested()
    {
        if (empty($this->settings['link']['skipControllerAndAction'])
            || !$this->isActionAllowed('detail')
            || !$this->request->hasArgument('news')
        ) {
            return;
        }

        $this->forward('detail', null, null, ['news' => $this->request->getArgument('news')]);
    }

    /**
     * Checks whether an action is enabled in switchableControllerActions configuration
     *
     * @param string $action
     * @return bool
     */
    protected function isActionAllowed(string $action): bool
    {
        $frameworkConfiguration = $this->configurationManager->getConfiguration($this->configurationManager::CONFIGURATION_TYPE_FRAMEWORK);
        $allowedActions = $frameworkConfiguration['controllerConfiguration']['News']['actions'] ?? [];

        return \in_array($action, $allowedActions, true);
    }

    /**
     * Output a selected list view of news
     */
    public function selectedListAction()
    {
        $newsRecords = [];

        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if (empty($this->originalSettings['orderBy'])) {
            $idList = GeneralUtility::trimExplode(',', $this->settings['selectedList'], true);
            foreach ($idList as $id) {
                $news = $this->newsRepository->findByIdentifier($id);
                if ($news) {
                    $newsRecords[] = $news;
                }
            }
        } else {
            $demand->setIdList($this->settings['selectedList']);
            $newsRecords = $this->newsRepository->findDemanded($demand);
        }

        $assignedValues = [
            'news' => $newsRecords,
            'demand' => $demand,
            'settings' => $this->settings
        ];

        $assignedValues = $this->emitActionSignal('NewsController', self::SIGNAL_NEWS_LIST_SELECTED_ACTION, $assignedValues);
        $this->view->assignMultiple($assignedValues);
    }

    /**
     * Single view of a news record
     *
     * @param \GeorgRinger\News\Domain\Model\News $news news item
     * @param int $currentPage current page for optional pagination
     */
    public function detailAction(\GeorgRinger\News\Domain\Model\News $news = null, $currentPage = 1)
    {
        if ($news === null || $this->settings['isShortcut']) {
            $previewNewsId = ((int)$this->settings['singleNews'] > 0) ? $this->settings['singleNews'] : 0;
            if ($this->request->hasArgument('news_preview')) {
                $previewNewsId = (int)$this->request->getArgument('news_preview');
            }

            if ($previewNewsId > 0) {
                if ($this->isPreviewOfHiddenRecordsEnabled()) {
                    $GLOBALS['TSFE']->showHiddenRecords = true;
                    $news = $this->newsRepository->findByUid($previewNewsId, false);
                } else {
                    $news = $this->newsRepository->findByUid($previewNewsId);
                }
            }
        }

        if (is_a($news,
                'GeorgRinger\\News\\Domain\\Model\\News') && $this->settings['detail']['checkPidOfNewsRecord']
        ) {
            $news = $this->checkPidOfNewsRecord($news);
        }

        if (is_null($news) && isset($this->settings['detail']['errorHandling'])) {
            $errorContent = $this->handleNoNewsFoundError($this->settings['detail']['errorHandling']);
            if ($errorContent) {
                return $errorContent;
            }
        }

        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        $assignedValues = [
            'newsItem' => $news,
            'currentPage' => (int)$currentPage,
            'demand' => $demand,
            'settings' => $this->settings
        ];

        $assignedValues = $this->emitActionSignal('NewsController', self::SIGNAL_NEWS_DETAIL_ACTION, $assignedValues);
        $this->view->assignMultiple($assignedValues);

        Page::setRegisterProperties($this->settings['detail']['registerProperties'], $news);
        if (!is_null($news) && is_a($news, 'GeorgRinger\\News\\Domain\\Model\\News')) {
            Cache::addCacheTagsByNewsRecords([$news]);
        }
    }

    /**
     * Checks if the news pid could be found in the startingpoint settings of the detail plugin and
     * if the pid could not be found it return NULL instead of the news object.
     *
     * @param \GeorgRinger\News\Domain\Model\News $news
     * @return NULL|\GeorgRinger\News\Domain\Model\News
     */
    protected function checkPidOfNewsRecord(\GeorgRinger\News\Domain\Model\News $news)
    {
        $allowedStoragePages = GeneralUtility::trimExplode(
            ',',
            Page::extendPidListByChildren(
                $this->settings['startingpoint'],
                $this->settings['recursive']
            ),
            true
        );
        if (count($allowedStoragePages) > 0 && !in_array($news->getPid(), $allowedStoragePages)) {
            $this->signalSlotDispatcher->dispatch(
                __CLASS__,
                'checkPidOfNewsRecordFailedInDetailAction',
                [
                    'news' => $news,
                    'newsController' => $this
                ]
            );
            $news = null;
        }
        return $news;
    }

    /**
     * Checks if preview is enabled either in TS or FlexForm
     *
     * @return bool
     */
    protected function isPreviewOfHiddenRecordsEnabled()
    {
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
     */
    public function dateMenuAction(array $overwriteDemand = null)
    {
        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== null) {
            $overwriteDemandTemp = $overwriteDemand;
            unset($overwriteDemandTemp['year']);
            unset($overwriteDemandTemp['month']);
            $demand = $this->overwriteDemandObject($demand,
                $overwriteDemandTemp);
            unset($overwriteDemandTemp);
        }

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

        $assignedValues = [
            'listPid' => ($this->settings['listPid'] ? $this->settings['listPid'] : $GLOBALS['TSFE']->id),
            'dateField' => $dateField,
            'data' => $statistics,
            'news' => $newsRecords,
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
            'settings' => $this->settings
        ];

        $assignedValues = $this->emitActionSignal('NewsController', self::SIGNAL_NEWS_DATEMENU_ACTION, $assignedValues);
        $this->view->assignMultiple($assignedValues);
    }

    /**
     * Display the search form
     *
     * @param \GeorgRinger\News\Domain\Model\Dto\Search $search
     * @param array $overwriteDemand
     */
    public function searchFormAction(
        \GeorgRinger\News\Domain\Model\Dto\Search $search = null,
        array $overwriteDemand = []
    ) {
        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== null) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }

        if (is_null($search)) {
            $search = $this->objectManager->get(\GeorgRinger\News\Domain\Model\Dto\Search::class);
        }
        $demand->setSearch($search);

        $assignedValues = [
            'search' => $search,
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
            'settings' => $this->settings
        ];

        $assignedValues = $this->emitActionSignal('NewsController', self::SIGNAL_NEWS_SEARCHFORM_ACTION,
            $assignedValues);
        $this->view->assignMultiple($assignedValues);
    }

    /**
     * Displays the search result
     *
     * @param \GeorgRinger\News\Domain\Model\Dto\Search $search
     * @param array $overwriteDemand
     */
    public function searchResultAction(
        \GeorgRinger\News\Domain\Model\Dto\Search $search = null,
        array $overwriteDemand = []
    ) {
        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== null) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }

        if (!is_null($search)) {
            $search->setFields($this->settings['search']['fields']);
            $search->setDateField($this->settings['dateField']);
            $search->setSplitSubjectWords((bool)$this->settings['search']['splitSearchWord']);
        }

        $demand->setSearch($search);

        $assignedValues = [
            'news' => $this->newsRepository->findDemanded($demand),
            'overwriteDemand' => $overwriteDemand,
            'search' => $search,
            'demand' => $demand,
            'settings' => $this->settings
        ];

        $assignedValues = $this->emitActionSignal('NewsController', self::SIGNAL_NEWS_SEARCHRESULT_ACTION,
            $assignedValues);
        $this->view->assignMultiple($assignedValues);
    }

    /**
     * initialize search result action
     */
    public function initializeSearchResultAction()
    {
        $this->initializeSearchActions();
    }

    /**
     * Initialize search form action
     */
    public function initializeSearchFormAction()
    {
        $this->initializeSearchActions();
    }

    /**
     * Initialize searchForm and searchResult actions
     */
    protected function initializeSearchActions()
    {
        if ($this->arguments->hasArgument('search')) {
            $propertyMappingConfiguration = $this->arguments['search']->getPropertyMappingConfiguration();
            $propertyMappingConfiguration->allowAllProperties();
            $propertyMappingConfiguration->setTypeConverterOption('TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter', \TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, true);
        }
    }

    /***************************************************************************
     * helper
     **********************/

    /**
     * Injects the Configuration Manager and is initializing the framework settings
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager Instance of the Configuration Manager
     */
    public function injectConfigurationManager(
        \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
    ) {
        $this->configurationManager = $configurationManager;

        $tsSettings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'news',
            'news_pi1'
        );
        $originalSettings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );

        $propertiesNotAllowedViaFlexForms = ['orderByAllowed'];
        foreach ($propertiesNotAllowedViaFlexForms as $property) {
            $originalSettings[$property] = $tsSettings['settings'][$property];
        }
        $this->originalSettings = $originalSettings;

        // Use stdWrap for given defined settings
        if (isset($originalSettings['useStdWrap']) && !empty($originalSettings['useStdWrap'])) {
            $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
            $typoScriptArray = $typoScriptService->convertPlainArrayToTypoScriptArray($originalSettings);
            $stdWrapProperties = GeneralUtility::trimExplode(',', $originalSettings['useStdWrap'], true);
            foreach ($stdWrapProperties as $key) {
                if (is_array($typoScriptArray[$key . '.'])) {
                    $originalSettings[$key] = $this->configurationManager->getContentObject()->stdWrap(
                        $typoScriptArray[$key],
                        $typoScriptArray[$key . '.']
                    );
                }
            }
        }

        // start override
        if (isset($tsSettings['settings']['overrideFlexformSettingsIfEmpty'])) {
            $typoScriptUtility = GeneralUtility::makeInstance(TypoScript::class);
            $originalSettings = $typoScriptUtility->override($originalSettings, $tsSettings);
        }

        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Controller/NewsController.php']['overrideSettings'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Controller/NewsController.php']['overrideSettings'] as $_funcRef) {
                $_params = [
                    'originalSettings' => $originalSettings,
                    'tsSettings' => $tsSettings,
                ];
                $originalSettings = GeneralUtility::callUserFunction($_funcRef, $_params, $this);
            }
        }

        $this->settings = $originalSettings;
    }

    /**
     * Injects a view.
     * This function is for testing purposes only.
     *
     * @param \TYPO3\CMS\Fluid\View\TemplateView $view the view to inject
     */
    public function setView(\TYPO3\CMS\Fluid\View\TemplateView $view)
    {
        $this->view = $view;
    }
}
