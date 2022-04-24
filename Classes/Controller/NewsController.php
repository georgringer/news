<?php

namespace GeorgRinger\News\Controller;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Domain\Model\Dto\Search;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Repository\CategoryRepository;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use GeorgRinger\News\Domain\Repository\TagRepository;
use GeorgRinger\News\Event\NewsCheckPidOfNewsRecordFailedInDetailActionEvent;
use GeorgRinger\News\Event\NewsDateMenuActionEvent;
use GeorgRinger\News\Event\NewsDetailActionEvent;
use GeorgRinger\News\Event\NewsListActionEvent;
use GeorgRinger\News\Event\NewsListSelectedActionEvent;
use GeorgRinger\News\Event\NewsSearchFormActionEvent;
use GeorgRinger\News\Event\NewsSearchResultActionEvent;
use GeorgRinger\News\Pagination\QueryResultPaginator;
use GeorgRinger\News\Seo\NewsTitleProvider;
use GeorgRinger\News\Utility\Cache;
use GeorgRinger\News\Utility\ClassCacheManager;
use GeorgRinger\News\Utility\Page;
use GeorgRinger\News\Utility\TypoScript;
use GeorgRinger\NumberedPagination\NumberedPagination;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Fluid\View\TemplateView;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Controller of news records
 *
 */
class NewsController extends NewsBaseController
{
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
     * NewsController constructor.
     * @param NewsRepository $newsRepository
     * @param CategoryRepository $categoryRepository
     * @param TagRepository $tagRepository
     * @param ConfigurationManagerInterface $configurationManager
     */
    public function __construct(
        NewsRepository $newsRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        ConfigurationManagerInterface $configurationManager
    ) {
        $this->newsRepository = $newsRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->configurationManager = $configurationManager;
    }

    /**
     * Initializes the current action
     *
     * @return void
     */
    public function initializeAction()
    {
        GeneralUtility::makeInstance(ClassCacheManager::class)->reBuildSimple();
        $this->buildSettings();
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
        array $settings,
        $class = NewsDemand::class
    ): \GeorgRinger\News\Domain\Model\Dto\NewsDemand {
        $class = isset($settings['demandClass']) && !empty($settings['demandClass']) ? $settings['demandClass'] : $class;

        /* @var $demand NewsDemand */
        $demand = GeneralUtility::makeInstance($class, $settings);
        if (!$demand instanceof NewsDemand) {
            throw new \UnexpectedValueException(
                sprintf(
                    'The demand object must be an instance of %s, but %s given!',
                    NewsDemand::class,
                    $class
                ),
                1423157953
            );
        }

        $demand->setCategories(GeneralUtility::trimExplode(',', $settings['categories'] ?? '', true));
        $demand->setCategoryConjunction((string)($settings['categoryConjunction'] ?? ''));
        $demand->setIncludeSubCategories((bool)($settings['includeSubCategories'] ?? false));
        $demand->setTags((string)($settings['tags'] ?? ''));

        $demand->setTopNewsRestriction((int)($settings['topNewsRestriction'] ?? 0));
        $demand->setTimeRestriction($settings['timeRestriction'] ?? '');
        $demand->setTimeRestrictionHigh($settings['timeRestrictionHigh'] ?? '');
        $demand->setArchiveRestriction((string)($settings['archiveRestriction'] ?? ''));
        $demand->setExcludeAlreadyDisplayedNews((bool)($settings['excludeAlreadyDisplayedNews'] ?? false));
        $demand->setHideIdList((string)($settings['hideIdList'] ?? ''));

        if ($settings['orderBy'] ?? '') {
            $demand->setOrder($settings['orderBy'] . ' ' . $settings['orderDirection']);
        }
        $demand->setOrderByAllowed((string)($settings['orderByAllowed'] ?? ''));

        $demand->setTopNewsFirst((bool)($settings['topNewsFirst'] ?? false));

        $demand->setLimit((int)($settings['limit'] ?? 0));
        $demand->setOffset((int)($settings['offset'] ?? 0));

        $demand->setSearchFields((string)($settings['search']['fields'] ?? ''));
        $demand->setDateField((string)($settings['dateField'] ?? ''));
        $demand->setMonth((int)($settings['month'] ?? 0));
        $demand->setYear((int)($settings['year'] ?? 0));

        $demand->setStoragePage(Page::extendPidListByChildren(
            (string)($settings['startingpoint'] ?? ''),
            (int)($settings['recursive'] ?? 0)
        ));

        if ($hooks = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Controller/NewsController.php']['createDemandObjectFromSettings'] ?? []) {
            $params = [
                'demand' => $demand,
                'settings' => $settings,
                'class' => $class,
            ];
            foreach ($hooks as $reference) {
                GeneralUtility::callUserFunction($reference, $params, $this);
            }
        }
        return $demand;
    }

    /**
     * Overwrites a given demand object by an propertyName =>  $propertyValue array
     *
     * @param \GeorgRinger\News\Domain\Model\Dto\NewsDemand $demand
     * @param array $overwriteDemand
     * @return \GeorgRinger\News\Domain\Model\Dto\NewsDemand
     */
    protected function overwriteDemandObject(NewsDemand $demand, array $overwriteDemand): \GeorgRinger\News\Domain\Model\Dto\NewsDemand
    {
        foreach ($this->ignoredSettingsForOverride as $property) {
            unset($overwriteDemand[$property]);
        }

        foreach ($overwriteDemand as $propertyName => $propertyValue) {
            if (in_array(strtolower($propertyName), $this->ignoredSettingsForOverride, true)) {
                continue;
            }
            if ($propertyValue !== '' || $this->settings['allowEmptyStringsForOverwriteDemand']) {
                if (in_array($propertyName, ['categories'], true)) {
                    $propertyValue = GeneralUtility::trimExplode(',', $propertyValue, true);
                }
                ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
            }
        }
        return $demand;
    }

    /**
     * Output a list view of news
     *
     * @param array|null $overwriteDemand
     *
     * @return void
     */
    public function listAction(array $overwriteDemand = null)
    {
        $this->forwardToDetailActionWhenRequested();

        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ((int)($this->settings['disableOverrideDemand'] ?? 1) !== 1 && $overwriteDemand !== null) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }
        $newsRecords = $this->newsRepository->findDemanded($demand);

        // pagination
        $paginationConfiguration = $this->settings['list']['paginate'] ?? [];
        $itemsPerPage = (int)(($paginationConfiguration['itemsPerPage'] ?? '') ?: 10);
        $maximumNumberOfLinks = (int)($paginationConfiguration['maximumNumberOfLinks'] ?? 0);

        $currentPage = $this->request->hasArgument('currentPage') ? (int)$this->request->getArgument('currentPage') : 1;
        $paginator = GeneralUtility::makeInstance(QueryResultPaginator::class, $newsRecords, $currentPage, $itemsPerPage, (int)($this->settings['limit'] ?? 0), (int)($this->settings['offset'] ?? 0));
        $paginationClass = $paginationConfiguration['class'] ?? SimplePagination::class;
        if (class_exists(NumberedPagination::class) && $paginationClass === NumberedPagination::class && $maximumNumberOfLinks) {
            $pagination = GeneralUtility::makeInstance(NumberedPagination::class, $paginator, $maximumNumberOfLinks);
        } elseif (class_exists($paginationClass)) {
            $pagination = GeneralUtility::makeInstance($paginationClass, $paginator);
        } else {
            $pagination = GeneralUtility::makeInstance(SimplePagination::class, $paginator);
        }

        $assignedValues = [
            'news' => $newsRecords,
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
            'categories' => null,
            'tags' => null,
            'settings' => $this->settings,
            'pagination' => [
                'currentPage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
            ]
        ];

        if ($demand->getCategories() !== '') {
            $categoriesList = $demand->getCategories();
            if (is_string($categoriesList)) {
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

        $event = $this->eventDispatcher->dispatch(new NewsListActionEvent($this, $assignedValues));
        $this->view->assignMultiple($event->getAssignedValues());

        Cache::addPageCacheTagsByDemandObject($demand);
    }

    /**
     * When list action is called along with a news argument, we forward to detail action.
     */
    protected function forwardToDetailActionWhenRequested()
    {
        if (!$this->isActionAllowed('detail')
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
    public function selectedListAction(): void
    {
        $newsRecords = [];

        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if (empty($this->originalSettings['orderBy'] ?? '')) {
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

        $event = $this->eventDispatcher->dispatch(new NewsListSelectedActionEvent($this, $assignedValues));
        $this->view->assignMultiple($event->getAssignedValues());

        if (!empty($newsRecords) && is_a($newsRecords[0], News::class)) {
            Cache::addCacheTagsByNewsRecords($newsRecords);
        }
    }

    /**
     * Single view of a news record
     *
     * @param \GeorgRinger\News\Domain\Model\News $news news item
     * @param int $currentPage current page for optional pagination
     *
     * @return null|string
     */
    public function detailAction(News $news = null, $currentPage = 1)
    {
        if ($news === null || ($this->settings['isShortcut'] ?? false)) {
            $previewNewsId = (int)($this->settings['singleNews'] ?? 0);
            if ($this->request->hasArgument('news_preview')) {
                $previewNewsId = (int)$this->request->getArgument('news_preview');
            }

            if ($previewNewsId > 0) {
                if ($this->isPreviewOfHiddenRecordsEnabled()) {
                    $news = $this->newsRepository->findByUid($previewNewsId, false);
                } else {
                    $news = $this->newsRepository->findByUid($previewNewsId);
                }
            }
        }

        if (is_a($news, News::class) && ($this->settings['detail']['checkPidOfNewsRecord'] ?? false)
        ) {
            $news = $this->checkPidOfNewsRecord($news);
        }

        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        $assignedValues = [
            'newsItem' => $news,
            'currentPage' => (int)$currentPage,
            'demand' => $demand,
            'settings' => $this->settings
        ];

        $event = $this->eventDispatcher->dispatch(new NewsDetailActionEvent($this, $assignedValues));
        $assignedValues = $event->getAssignedValues();

        $news = $assignedValues['newsItem'];
        $this->view->assignMultiple($assignedValues);

        // reset news if type is internal or external
        if ($news && !($this->settings['isShortcut'] ?? false) && ($news->getType() === '1' || $news->getType() === '2')) {
            $news = null;
        }

        if ($news !== null) {
            Page::setRegisterProperties($this->settings['detail']['registerProperties'] ?? false, $news);
            Cache::addCacheTagsByNewsRecords([$news]);

            if ($this->settings['detail']['pageTitle']['_typoScriptNodeValue'] ?? false) {
                $providerConfiguration = $this->settings['detail']['pageTitle'] ?? [];
                $providerClass = $providerConfiguration['provider'] ?? NewsTitleProvider::class;

                /** @var NewsTitleProvider $provider */
                $provider = GeneralUtility::makeInstance($providerClass);
                $provider->setTitleByNews($news, $providerConfiguration);
            }
        } elseif (isset($this->settings['detail']['errorHandling'])) {
            $errorContent = $this->handleNoNewsFoundError($this->settings['detail']['errorHandling']);
            if ($errorContent) {
                return $errorContent;
            }
        }
    }

    /**
     * Checks if the news pid could be found in the startingpoint settings of the detail plugin and
     * if the pid could not be found it return NULL instead of the news object.
     *
     * @param \GeorgRinger\News\Domain\Model\News $news
     * @return \GeorgRinger\News\Domain\Model\News|null
     */
    protected function checkPidOfNewsRecord(News $news): ?\GeorgRinger\News\Domain\Model\News
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
            $this->eventDispatcher->dispatch(new NewsCheckPidOfNewsRecordFailedInDetailActionEvent($this, $news));
            $news = null;
        }
        return $news;
    }

    /**
     * Checks if preview is enabled either in TS or FlexForm
     *
     * @return bool
     */
    protected function isPreviewOfHiddenRecordsEnabled(): bool
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
     * @param array|null $overwriteDemand
     *
     * @return void
     */
    public function dateMenuAction(array $overwriteDemand = null): void
    {
        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== null) {
            $overwriteDemandTemp = $overwriteDemand;
            unset($overwriteDemandTemp['year']);
            unset($overwriteDemandTemp['month']);
            $demand = $this->overwriteDemandObject(
                $demand,
                $overwriteDemandTemp
            );
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

        $event = $this->eventDispatcher->dispatch(new NewsDateMenuActionEvent($this, $assignedValues));

        $this->view->assignMultiple($event->getAssignedValues());
    }

    /**
     * Display the search form
     *
     * @param \GeorgRinger\News\Domain\Model\Dto\Search $search
     * @param array $overwriteDemand
     *
     * @return void
     */
    public function searchFormAction(
        Search $search = null,
        array $overwriteDemand = []
    ): void {
        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== null) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }

        if (is_null($search)) {
            $search = GeneralUtility::makeInstance(Search::class);
        }
        $demand->setSearch($search);

        $assignedValues = [
            'search' => $search,
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
            'settings' => $this->settings
        ];

        $event = $this->eventDispatcher->dispatch(new NewsSearchFormActionEvent($this, $assignedValues));

        $this->view->assignMultiple($event->getAssignedValues());
    }

    /**
     * Displays the search result
     *
     * @param \GeorgRinger\News\Domain\Model\Dto\Search $search
     * @param array $overwriteDemand
     *
     * @return void
     */
    public function searchResultAction(
        Search $search = null,
        array $overwriteDemand = []
    ): void {
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

        $event = $this->eventDispatcher->dispatch(new NewsSearchResultActionEvent($this, $assignedValues));

        $this->view->assignMultiple($event->getAssignedValues());
    }

    /**
     * initialize search result action
     *
     * @return void
     */
    public function initializeSearchResultAction(): void
    {
        $this->initializeSearchActions();
    }

    /**
     * Initialize search form action
     *
     * @return void
     */
    public function initializeSearchFormAction(): void
    {
        $this->initializeSearchActions();
    }

    /**
     * Initialize searchForm and searchResult actions
     *
     * @return void
     */
    protected function initializeSearchActions(): void
    {
        if ($this->arguments->hasArgument('search')) {
            $propertyMappingConfiguration = $this->arguments['search']->getPropertyMappingConfiguration();
            $propertyMappingConfiguration->allowAllProperties();
            $propertyMappingConfiguration->setTypeConverterOption('TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter', PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, true);
        }
    }

    /***************************************************************************
     * helper
     **********************/

    /**
     * Injects the Configuration Manager and is initializing the framework settings
     *
     * @param ConfigurationManagerInterface $configurationManager Instance of the Configuration Manager
     *
     * @return void
     */
    public function buildSettings()
    {
        $tsSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'news',
            'news_pi1'
        );
        $originalSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );

        $propertiesNotAllowedViaFlexForms = ['orderByAllowed'];
        foreach ($propertiesNotAllowedViaFlexForms as $property) {
            $originalSettings[$property] = ($tsSettings['settings'] ?? [])[$property] ?? ($originalSettings[$property] ?? '');
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

        foreach ($hooks = ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Controller/NewsController.php']['overrideSettings'] ?? []) as $_funcRef) {
            $_params = [
                'originalSettings' => $originalSettings,
                'tsSettings' => $tsSettings,
            ];
            $originalSettings = GeneralUtility::callUserFunction($_funcRef, $_params, $this);
        }

        $this->settings = $originalSettings;
    }

    /**
     * Injects a view.
     * This function is for testing purposes only.
     *
     * @param \TYPO3\CMS\Fluid\View\TemplateView $view the view to inject
     *
     * @return void
     */
    public function setView(TemplateView $view): void
    {
        $this->view = $view;
    }
}
