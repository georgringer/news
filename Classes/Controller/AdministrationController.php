<?php

namespace GeorgRinger\News\Controller;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Backend\RecordList\NewsDatabaseRecordList;
use GeorgRinger\News\Domain\Model\Dto\AdministrationDemand;
use GeorgRinger\News\Domain\Repository\AdministrationRepository;
use GeorgRinger\News\Utility\Page;
use TYPO3\CMS\Backend\Clipboard\Clipboard;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Administration controller
 */
class AdministrationController extends NewsController
{
    const SIGNAL_ADMINISTRATION_INDEX_ACTION = 'indexAction';
    const SIGNAL_ADMINISTRATION_NEWSPIDLISTING_ACTION = 'newsPidListingAction';

    /**
     * Page uid
     *
     * @var int
     */
    protected $pageUid = 0;

    /**
     * TsConfig configuration
     *
     * @var array
     */
    protected $tsConfiguration = [];

    /**
     * @var \GeorgRinger\News\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    /** @var AdministrationRepository */
    protected $administrationRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;
    /**
     * @var array
     */
    protected $pageInformation = [];

    /**
     * @var array
     */
    protected $allowedNewTables = [];

    /**
     * @var array
     */
    protected $deniedNewTables = [];

    /**
     * Function will be called before every other action
     *
     */
    public function initializeAction()
    {
        $this->pageUid = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('id');
        $this->pageInformation = BackendUtilityCore::readPageAccess($this->pageUid, '');
        $this->setTsConfig();
        $this->administrationRepository = GeneralUtility::makeInstance(AdministrationRepository::class);
        parent::initializeAction();
    }

    /**
     * BackendTemplateContainer
     *
     * @var BackendTemplateView
     */
    protected $view;

    /**
     * @var IconFactory
     */
    protected $iconFactory;

    /**
     * Backend Template Container
     *
     * @var BackendTemplateView
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * Set up the doc header properly here
     *
     * @param ViewInterface $view
     */
    protected function initializeView(ViewInterface $view)
    {
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        /** @var BackendTemplateView $view */
        parent::initializeView($view);
        $view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation([]);

        $pageRenderer = $this->view->getModuleTemplate()->getPageRenderer();
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/DateTimePicker');
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/ContextMenu');
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/AjaxDataHandler');
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/News/AdministrationModule');
        $pageRenderer->addInlineLanguageLabelFile('EXT:lang/Resources/Private/Language/locallang_core.xlf');
        $dateFormat = ($GLOBALS['TYPO3_CONF_VARS']['SYS']['USdateFormat'] ? ['MM-DD-YYYY', 'HH:mm MM-DD-YYYY'] : ['DD-MM-YYYY', 'HH:mm DD-MM-YYYY']);
        $pageRenderer->addInlineSetting('DateTimePicker', 'DateFormat', $dateFormat);

        $web_list_modTSconfig = BackendUtilityCore::getModTSconfig($this->pageUid, 'mod.web_list');
        $this->allowedNewTables = GeneralUtility::trimExplode(
            ',',
            $web_list_modTSconfig['properties']['allowedNewTables'],
            true
        );
        $this->deniedNewTables = GeneralUtility::trimExplode(
            ',',
            $web_list_modTSconfig['properties']['deniedNewTables'],
            true
        );

        $this->createMenu();
        $this->createButtons();

        $view->assign('is9up', self::is9up());
        $view->assign('showSupportArea', $this->showSupportArea());
    }

    /**
     * Create menu
     *
     */
    protected function createMenu()
    {
        $uriBuilder = $this->objectManager->get(UriBuilder::class);
        $uriBuilder->setRequest($this->request);

        $menu = $this->view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $menu->setIdentifier('news');

        $actions = [
            ['action' => 'index', 'label' => 'newsListing'],
            ['action' => 'newsPidListing', 'label' => 'newsPidListing'],
            ['action' => 'donate', 'label' => 'donate']
        ];

        foreach ($actions as $action) {
            $item = $menu->makeMenuItem()
                ->setTitle($this->getLanguageService()->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:module.' . $action['label']))
                ->setHref($uriBuilder->reset()->uriFor($action['action'], [], 'Administration'))
                ->setActive($this->request->getControllerActionName() === $action['action']);
            $menu->addMenuItem($item);
        }

        $this->view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->addMenu($menu);
        if (is_array($this->pageInformation)) {
            $this->view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation($this->pageInformation);
        }
    }

    /**
     * Create the panel of buttons
     *
     */
    protected function createButtons()
    {
        $buttonBar = $this->view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();

        $uriBuilder = $this->objectManager->get(UriBuilder::class);
        $uriBuilder->setRequest($this->request);

        if ($this->request->getControllerActionName() === 'index' && $this->isFilteringEnabled()) {
            $toggleButton = $buttonBar->makeLinkButton()
                ->setHref('#')
                ->setDataAttributes([
                    'togglelink' => '1',
                    'toggle' => 'tooltip',
                    'placement' => 'bottom',
                ])
                ->setTitle($this->getLanguageService()->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:administration.toggleForm'))
                ->setIcon($this->iconFactory->getIcon('actions-filter', Icon::SIZE_SMALL));
            $buttonBar->addButton($toggleButton, ButtonBar::BUTTON_POSITION_LEFT, 1);
        }

        $buttons = [
            [
                'table' => 'tx_news_domain_model_news',
                'label' => 'module.createNewNewsRecord',
                'action' => 'newNews',
                'icon' => 'ext-news-type-default'
            ],
            [
                'table' => 'tx_news_domain_model_tag',
                'label' => 'module.createNewTag',
                'action' => 'newTag',
                'icon' => 'ext-news-tag'
            ],
            [
                'table' => 'sys_category',
                'label' => 'module.createNewCategory',
                'action' => 'newCategory',
                'icon' => 'mimetypes-x-sys_category'
            ]
        ];
        foreach ($buttons as $key => $tableConfiguration) {
            if ($this->showButton($tableConfiguration['table'])) {
                $title = $this->getLanguageService()->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:' . $tableConfiguration['label']);
                $viewButton = $buttonBar->makeLinkButton()
                    ->setHref($uriBuilder->reset()->setRequest($this->request)->uriFor($tableConfiguration['action'],
                        [], 'Administration'))
                    ->setDataAttributes([
                        'toggle' => 'tooltip',
                        'placement' => 'bottom',
                        'title' => $title])
                    ->setTitle($title)
                    ->setIcon($this->iconFactory->getIcon($tableConfiguration['icon'], Icon::SIZE_SMALL, 'overlay-new'));
                $buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_LEFT, 2);
            }
        }

        $clipBoard = GeneralUtility::makeInstance(Clipboard::class);
        $clipBoard->initializeClipboard();
        $elFromTable = $clipBoard->elFromTable('tx_news_domain_model_news');
        if (!empty($elFromTable)) {
            $viewButton = $buttonBar->makeLinkButton()
                ->setHref($clipBoard->pasteUrl('', $this->pageUid))
                ->setOnClick('return ' . $clipBoard->confirmMsg('pages',
                        BackendUtilityCore::getRecord('pages', $this->pageUid), 'into',
                        $elFromTable))
                ->setTitle($this->getLanguageService()->sL('LLL:EXT:lang/Resources/Private/Language/locallang_mod_web_list.xlf:clip_pasteInto'))
                ->setIcon($this->iconFactory->getIcon('actions-document-paste-into', Icon::SIZE_SMALL));
            $buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_LEFT, 4);
        }

        // Donation
        $donationButton = $buttonBar->makeLinkButton()
            ->setHref($uriBuilder->reset()->setRequest($this->request)->uriFor('donate',
                [], 'Administration'))
            ->setTitle($this->getLanguageService()->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:administration.donation.title'))
            ->setIcon($this->iconFactory->getIcon('ext-news-donation', Icon::SIZE_SMALL));
        $buttonBar->addButton($donationButton, ButtonBar::BUTTON_POSITION_RIGHT);

        // Refresh
        $refreshButton = $buttonBar->makeLinkButton()
            ->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
            ->setTitle($this->getLanguageService()->sL('LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:labels.reload'))
            ->setIcon($this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($refreshButton, ButtonBar::BUTTON_POSITION_RIGHT);

        // Shortcut
        if ($this->getBackendUser()->mayMakeShortcut()) {
            $shortcutButton = $buttonBar->makeShortcutButton()
                ->setModuleName('web_NewsTxNewsM2')
                ->setGetVariables(['route', 'module', 'id'])
                ->setDisplayName('Shortcut');
            $buttonBar->addButton($shortcutButton, ButtonBar::BUTTON_POSITION_RIGHT);
        }
    }

    /**
     * @param string $table
     * @return bool
     */
    protected function showButton($table)
    {
        if (!$this->getBackendUser()->check('tables_modify', $table)) {
            return false;
        }

        // No deny/allow tables are set:
        if (empty($this->allowedNewTables) && empty($this->deniedNewTables)) {
            return true;
        }

        $showButton = !in_array($table, $this->deniedNewTables, true) &&
            (empty($this->allowedNewTables) || in_array($table, $this->allowedNewTables, true));

        return $showButton;
    }

    /**
     * Inject a news repository to enable DI
     *
     * @param \GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(\GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Main action for administration
     */
    public function indexAction()
    {
        $this->redirectToPageOnStart();

        $demandVars = GeneralUtility::_GET('tx_news_web_newstxnewsm2');
        $demand = $this->objectManager->get(AdministrationDemand::class);
        $autoSubmitForm = 0;
        if (is_array($demandVars['demand'])) {
            foreach ($demandVars['demand'] as $key => $value) {
                if (property_exists(AdministrationDemand::class, $key)) {
                    $getter = 'set' . ucfirst($key);
                    $demand->$getter($value);
                }
            }
        } else {
            // Preselect by TsConfig (e.g. tx_news.module.preselect.topNewsRestriction = 1)
            if (isset($this->tsConfiguration['preselect.'])
                && is_array($this->tsConfiguration['preselect.'])
            ) {
                $anyPropertySet = false;
                unset($this->tsConfiguration['preselect.']['orderByAllowed']);

                foreach ($this->tsConfiguration['preselect.'] as $propertyName => $propertyValue) {
                    $propertySet = ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
                    if ($propertySet) {
                        $anyPropertySet = true;
                    }
                }

                if($anyPropertySet && !GeneralUtility::_GET('formSubmitted')) {
                    $autoSubmitForm = 1;
                }
            }
            if (!(bool)$this->tsConfiguration['alwaysShowFilter'] || !$this->isFilteringEnabled()) {
                $this->view->assign('hideForm', true);
            }
        }
        $this->view->assign('autoSubmitForm',$autoSubmitForm);

        $categories = $this->categoryRepository->findParentCategoriesByPid($this->pageUid);
        $idList = [];
        foreach ($categories as $c) {
            $idList[] = $c->getUid();
        }
        if (empty($idList) && !$this->getBackendUser()->isAdmin()) {
            $idList = $this->getBackendUser()->getCategoryMountPoints();
        }

        if (!empty($this->tsConfiguration['allowedCategoryRootIds'])) {
            $allowedList = GeneralUtility::intExplode(',', $this->tsConfiguration['allowedCategoryRootIds'], true);
            if (!empty($allowedList)) {
                $idList = array_intersect($idList, $allowedList);
            }
        }

        // Initialize the dblist object:
        $dblist = GeneralUtility::makeInstance(NewsDatabaseRecordList::class);
        $dblist->script = GeneralUtility::getIndpEnv('REQUEST_URI');
        $dblist->thumbs = $this->getBackendUser()->uc['thumbnailsByDefault'];
        $dblist->allFields = 1;
        $dblist->localizationView = $this->tsConfiguration['localizationView'] ? MathUtility::forceIntegerInRange($this->tsConfiguration['localizationView'], 0) : 1;
        $dblist->clickTitleMode = 'edit';
        $dblist->calcPerms = $this->getBackendUser()->calcPerms($this->pageInformation);
        $dblist->showClipboard = 0;
        $dblist->disableSingleTableView = 1;
        $dblist->pageRow = $this->pageInformation;
        $dblist->displayFields = false;
        $dblist->dontShowClipControlPanels = true;
        $dblist->counter++;
        $dblist->MOD_MENU = ['bigControlPanel' => '', 'clipBoard' => '', 'localization' => ''];
        $pointer = MathUtility::forceIntegerInRange(GeneralUtility::_GP('pointer'), 0);
        $limit = isset($this->settings['list']['paginate']['itemsPerPage']) ? (int)$this->settings['list']['paginate']['itemsPerPage'] : 20;
        $dblist->start($this->pageUid, 'tx_news_domain_model_news', $pointer, '',
            $demand->getRecursive(), $limit);
        $dblist->setDispFields();
        $dblist->noControlPanels = !(bool)$this->tsConfiguration['controlPanels'];
        $dblist->setFields = [
            'tx_news_domain_model_news' => GeneralUtility::trimExplode(',', $this->tsConfiguration['columns'] ?: 'teaser,istopnews,datetime,categories', true)
        ];

        $dblist->script = $_SERVER['REQUEST_URI'];
        $dblist->generateList();

        $assignedValues = [
            'moduleToken' => $this->getToken(true),
            'page' => $this->pageUid,
            'demand' => $demand,
            'news' => $dblist->HTMLcode,
            'newsCount' => $dblist->counter,
            'showSearchForm' => (!is_null($demand) || $dblist->counter > 0),
            'requestUri' => GeneralUtility::quoteJSvalue(rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'))),
            'categories' => $this->categoryRepository->findTree($idList),
            'filters' => $this->tsConfiguration['filters.'],
            'enableFiltering' => $this->isFilteringEnabled(),
            'dateformat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy']
        ];

        $assignedValues = $this->emitActionSignal('AdministrationController', self::SIGNAL_ADMINISTRATION_INDEX_ACTION,
            $assignedValues);
        $this->view->assignMultiple($assignedValues);
    }

    /**
     * Shows a page tree including count of news + category records
     *
     * @param int $treeLevel
     */
    public function newsPidListingAction($treeLevel = 2)
    {
        $tree = Page::pageTree($this->pageUid, $treeLevel);

        $rawTree = [];
        foreach ($tree->tree as $row) {
            $this->countRecordsOnPage($row);
            $rawTree[] = $row;
        }

        $assignedValues = [
            'tree' => $rawTree,
            'treeLevel' => $treeLevel,
        ];

        $assignedValues = $this->emitActionSignal('AdministrationController',
            self::SIGNAL_ADMINISTRATION_NEWSPIDLISTING_ACTION, $assignedValues);
        $this->view->assignMultiple($assignedValues);
    }

    public function donateAction()
    {
        $this->view->assignMultiple([
            'counts' => $this->administrationRepository->getTotalCounts()
        ]);
    }

    /**
     * Redirect to form to create a news record
     */
    public function newNewsAction()
    {
        $this->redirectToCreateNewRecord('tx_news_domain_model_news');
    }

    /**
     * Redirect to form to create a category record
     */
    public function newCategoryAction()
    {
        $this->redirectToCreateNewRecord('sys_category');
    }

    /**
     * Redirect to form to create a tag record
     */
    public function newTagAction()
    {
        $this->redirectToCreateNewRecord('tx_news_domain_model_tag');
    }

    /**
     * Update page record array with count of news & category records
     *
     * @param array $row page record
     */
    private function countRecordsOnPage(array &$row)
    {
        $pageUid = (int)$row['row']['uid'];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');
        $row['countNews'] = $queryBuilder->count('*')
            ->from('tx_news_domain_model_news')
            ->where($queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pageUid, \PDO::PARAM_INT)))
            ->execute()
            ->fetchColumn(0);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        $row['countCategories'] = $queryBuilder->count('*')
            ->from('sys_category')
            ->where($queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pageUid, \PDO::PARAM_INT)))
            ->execute()
            ->fetchColumn(0);

        $row['countNewsAndCategories'] = ($row['countNews'] + $row['countCategories']);
    }

    /**
     * Redirect to tceform creating a new record
     *
     * @param string $table table name
     */
    private function redirectToCreateNewRecord($table)
    {
        $pid = $this->pageUid;
        if ($pid === 0 && isset($this->tsConfiguration['defaultPid.'])
            && is_array($this->tsConfiguration['defaultPid.'])
            && isset($this->tsConfiguration['defaultPid.'][$table])
        ) {
            $pid = (int)$this->tsConfiguration['defaultPid.'][$table];
        }

        $returnUrl = 'index.php?M=web_NewsTxNewsM2&id=' . $this->pageUid . $this->getToken();
        $url = BackendUtilityCore::getModuleUrl('record_edit', [
            'edit[' . $table . '][' . $pid . ']' => 'new',
            'returnUrl' => $returnUrl
        ]);
        HttpUtility::redirect($url);
    }

    /**
     * Set the TsConfig configuration for the extension
     *
     */
    protected function setTsConfig()
    {
        $tsConfig = BackendUtilityCore::getPagesTSconfig($this->pageUid);
        if (isset($tsConfig['tx_news.']['module.']) && is_array($tsConfig['tx_news.']['module.'])) {
            $this->tsConfiguration = $tsConfig['tx_news.']['module.'];
        }
    }

    /**
     * Check if at least one filter is enabled
     *
     * @return bool
     */
    protected function isFilteringEnabled()
    {
        if (isset($this->tsConfiguration['filters.'])) {
            foreach ($this->tsConfiguration['filters.'] as $filter => $enabled) {
                if ($enabled == 1) {
                    // Check dependencies on other filter
                    if (($filter === 'categoryConjunction' || $filter === 'includeSubCategories')
                        && $this->tsConfiguration['filters.']['categories'] == 0) {
                        continue;
                    }
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * If defined in TsConfig with tx_news.module.redirectToPageOnStart = 123
     * and the current page id is 0, a redirect to the given page will be done
     *
     */
    protected function redirectToPageOnStart()
    {
        if ((int)$this->tsConfiguration['allowedPage'] > 0 && $this->pageUid !== (int)$this->tsConfiguration['allowedPage']) {
            $url = 'index.php?M=web_NewsTxNewsM2&id=' . (int)$this->tsConfiguration['allowedPage'] . $this->getToken();
            HttpUtility::redirect($url);
        } elseif ($this->pageUid === 0 && (int)$this->tsConfiguration['redirectToPageOnStart'] > 0) {
            $url = 'index.php?M=web_NewsTxNewsM2&id=' . (int)$this->tsConfiguration['redirectToPageOnStart'] . $this->getToken();
            HttpUtility::redirect($url);
        }
    }

    /**
     * Get a CSRF token
     *
     * @param bool $tokenOnly Set it to TRUE to get only the token, otherwise including the &moduleToken= as prefix
     * @return string
     */
    protected function getToken(bool $tokenOnly = false): string
    {
        if (self::is9up()) {
            $token = FormProtectionFactory::get('backend')->generateToken('route', 'web_NewsTxNewsM2');
        } else {
            $token = FormProtectionFactory::get()->generateToken('moduleCall', 'web_NewsTxNewsM2');
        }

        if ($tokenOnly) {
            return $token;
        }

        return '&moduleToken=' . $token;
    }

    /**
     * @return bool
     */
    private static function is9up(): bool
    {
        return VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 9000000;
    }

    /**
     * Show support area only for admins in given percent of time
     *
     * @param int $probabilityInPercent
     * @return bool
     */
    private function showSupportArea(int $probabilityInPercent = 10): bool
    {
        if (!$this->getBackendUser()->isAdmin()) {
            return false;
        }

        if (mt_rand() % 100 <= $probabilityInPercent) {
            return true;
        }

        return false;
    }

    /**
     * Returns the LanguageService
     *
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Get backend user
     *
     * @return BackendUserAuthentication
     */
    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
