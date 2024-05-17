<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Hooks;

use GeorgRinger\News\Utility\TemplateLayout;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Render selected options of plugin in Web>Page module
 */
class PluginPreviewRenderer extends StandardContentPreviewRenderer
{
    protected const LLPATH = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:';
    protected const SETTINGS_IN_PREVIEW = 7;

    /**
     * Table information
     */
    public array $tableData = [];

    /**
     * Flexform information
     */
    public array $flexformData = [];

    /**
     * @var IconFactory
     */
    protected $iconFactory;

    /** @var TemplateLayout $templateLayoutsUtility */
    protected $templateLayoutsUtility;

    public function __construct()
    {
        $this->templateLayoutsUtility = GeneralUtility::makeInstance(TemplateLayout::class);
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
    }

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $row = $item->getRecord();
        $actionTranslationKey = $result = '';
        $header = '<strong>' . htmlspecialchars($this->getLanguageService()->sL(self::LLPATH . 'pi1_title')) . '</strong>';

        $this->tableData = [];
        $flexforms = GeneralUtility::xml2array($row['pi_flexform']);
        if (is_string($flexforms)) {
            return 'ERROR: ' . htmlspecialchars($flexforms);
        }
        $this->flexformData = (array)$flexforms;

        if (!empty($this->flexformData)) {
            switch ($row['CType']) {
                case 'news_pi1':
                case 'news_newsliststicky':
                    $this->getStartingPoint();
                    $this->getCategorySettings();
                    $this->getDetailPidSetting();
                    $this->getTimeRestrictionSetting();
                    $this->getTemplateLayoutSettings($row['pid']);
                    $this->getArchiveSettings();
                    $this->getTopNewsRestrictionSetting();
                    $this->getOrderSettings();
                    $this->getOffsetLimitSettings();
                    $this->getListPidSetting();
                    $this->getTagRestrictionSetting();
                    break;
                case 'news_newsselectedlist':
                    $this->getSelectedListSetting();
                    $this->getOrderSettings();
                    break;
                case 'news_newsdetail':
                    $this->getSingleNewsSettings();
                    $this->getDetailPidSetting();
                    $this->getTemplateLayoutSettings($row['pid']);
                    break;
                case 'news_newsdatemenu':
                    $this->getStartingPoint();
                    $this->getTimeRestrictionSetting();
                    $this->getTopNewsRestrictionSetting();
                    $this->getArchiveSettings();
                    $this->getDateMenuSettings();
                    $this->getCategorySettings();
                    $this->getTemplateLayoutSettings($row['pid']);
                    break;
                case 'news_categorylist':
                    $this->getCategorySettings(false);
                    $this->getTemplateLayoutSettings($row['pid']);
                    break;
                case 'news_taglist':
                    $this->getStartingPoint();
                    $this->getListPidSetting();
                    $this->getOrderSettings();
                    $this->getTemplateLayoutSettings($row['pid']);
                    break;
                default:
                    $this->getTemplateLayoutSettings($row['pid']);
            }

            if ($hooks = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['GeorgRinger\\News\\Hooks\\PluginPreviewRenderer']['extensionSummary'] ?? []) {
                $params['action'] = $actionTranslationKey;
                $params['item'] = $item;
                foreach ($hooks as $reference) {
                    GeneralUtility::callUserFunction($reference, $params, $this);
                }
            }

            // for all views
            $this->getOverrideDemandSettings();

            $result = $this->renderSettingsAsTable($header, $row['uid'] ?? 0);
        }

        return $result;
    }

    public function getArchiveSettings(): void
    {
        $archive = $this->getFieldFromFlexform('settings.archiveRestriction');

        if (!empty($archive)) {
            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.archiveRestriction'),
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.archiveRestriction.' . $archive),
            ];
        }
    }

    public function getSingleNewsSettings(): void
    {
        $singleNewsRecord = (int)$this->getFieldFromFlexform('settings.singleNews');

        if ($singleNewsRecord > 0) {
            $newsRecord = BackendUtilityCore::getRecord('tx_news_domain_model_news', $singleNewsRecord);

            if (is_array($newsRecord)) {
                $pageRecord = BackendUtilityCore::getRecord('pages', $newsRecord['pid']);

                if (is_array($pageRecord)) {
                    $content = $this->getRecordData($newsRecord['uid'], 'tx_news_domain_model_news');
                } else {
                    $text = sprintf(
                        $this->getLanguageService()->sL(self::LLPATH . 'pagemodule.pageNotAvailable'),
                        $newsRecord['pid']
                    );
                    $content = $this->generateCallout($text);
                }
            } else {
                $text = sprintf(
                    $this->getLanguageService()->sL(self::LLPATH . 'pagemodule.newsNotAvailable'),
                    $singleNewsRecord
                );
                $content = $this->generateCallout($text);
            }

            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.singleNews'),
                $content,
            ];
        }
    }

    public function getDetailPidSetting(): void
    {
        $detailPid = (int)$this->getFieldFromFlexform('settings.detailPid', 'additional');

        if ($detailPid > 0) {
            $content = $this->getRecordData($detailPid);

            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.detailPid'),
                $content,
            ];
        }
    }

    public function getListPidSetting(): void
    {
        $listPid = (int)$this->getFieldFromFlexform('settings.listPid', 'additional');

        if ($listPid > 0) {
            $content = $this->getRecordData($listPid);

            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.listPid'),
                $content,
            ];
        }
    }

    public function getRecordData(int $id, string $table = 'pages'): string
    {
        $record = BackendUtilityCore::getRecord($table, $id);

        if (is_array($record)) {
            $data = '<span data-toggle="tooltip" data-placement="top" data-title="id=' . $record['uid'] . '">'
                . $this->iconFactory->getIconForRecord($table, $record, Icon::SIZE_SMALL)->render()
                . '</span> ';
            $content = BackendUtilityCore::wrapClickMenuOnIcon(
                $data,
                $table,
                $record['uid'],
                true,
                $record
            );

            $linkTitle = htmlspecialchars(BackendUtilityCore::getRecordTitle($table, $record));

            if ($table === 'pages') {
                $id = $record['uid'];
                $currentPageId = (int)GeneralUtility::_GET('id');
                $link = htmlspecialchars($this->getEditLink($record, $currentPageId));
                $switchLabel = $this->getLanguageService()->sL(self::LLPATH . 'pagemodule.switchToPage');
                $content .= ' <a href="#" data-toggle="tooltip" data-placement="top" data-title="' . $switchLabel . '" onclick=\'top.jump("' . $link . '", "web_layout", "web", ' . $id . ');return false\'>' . $linkTitle . '</a>';
            } else {
                $content .= $linkTitle;
            }
        } else {
            $text = sprintf(
                $this->getLanguageService()->sL(self::LLPATH . 'pagemodule.recordNotAvailable'),
                $id
            );
            $content = $this->generateCallout($text);
        }

        return $content;
    }

    public function getOrderSettings(): void
    {
        $orderField = $this->getFieldFromFlexform('settings.orderBy');
        if (!empty($orderField)) {
            $text = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.orderBy.' . $orderField);

            // Order direction (asc, desc)
            $orderDirection = $this->getOrderDirectionSetting();
            if ($orderDirection) {
                $text .= ', ' . strtolower($orderDirection);
            }

            // Top news first
            $topNews = $this->getTopNewsFirstSetting();
            if ($topNews) {
                $text .= '<br />' . $topNews;
            }

            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.orderBy'),
                $text,
            ];
        }
    }

    public function getOrderDirectionSetting(): string
    {
        $text = '';

        $orderDirection = $this->getFieldFromFlexform('settings.orderDirection');
        if (!empty($orderDirection)) {
            $text = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.orderDirection.' . $orderDirection);
        }

        return $text;
    }

    public function getTopNewsFirstSetting(): string
    {
        $text = '';
        $topNewsSetting = (int)$this->getFieldFromFlexform('settings.topNewsFirst', 'additional');
        if ($topNewsSetting === 1) {
            $text = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.topNewsFirst');
        }

        return $text;
    }

    public function getCategorySettings(bool $showCategoryMode = true): void
    {
        $categories = GeneralUtility::intExplode(',', $this->getFieldFromFlexform('settings.categories') ?? '', true);
        if (count($categories) > 0) {
            $categoriesOut = [];
            foreach ($categories as $id) {
                $categoriesOut[] = $this->getRecordData($id, 'sys_category');
            }

            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.categories'),
                implode(', ', $categoriesOut),
            ];

            // Category mode
            if ($showCategoryMode) {
                $categoryModeSelection = $this->getFieldFromFlexform('settings.categoryConjunction');
                if (empty($categoryModeSelection)) {
                    $categoryMode = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.categoryConjunction.all');
                } else {
                    $categoryMode = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.categoryConjunction.' . $categoryModeSelection);
                }

                if (count($categories) > 0 && empty($categoryModeSelection)) {
                    $categoryMode = $this->generateCallout($categoryMode);
                } else {
                    $categoryMode = htmlspecialchars($categoryMode);
                }

                $this->tableData[] = [
                    $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.categoryConjunction'),
                    $categoryMode,
                ];
            }

            $includeSubcategories = $this->getFieldFromFlexform('settings.includeSubCategories');
            if ($includeSubcategories) {
                $this->tableData[] = [
                    $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.includeSubCategories'),
                    '<i class="fa fa-check"></i>',
                ];
            }
        }
    }

    public function getTagRestrictionSetting()
    {
        $tags = GeneralUtility::intExplode(',', $this->getFieldFromFlexform('settings.tags', 'additional') ?? '', true);
        if (count($tags) === 0) {
            return;
        }

        $categoryTitles = [];
        foreach ($tags as $id) {
            $categoryTitles[] = $this->getRecordData($id, 'tx_news_domain_model_tag');
        }

        $this->tableData[] = [
            $this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.tags'),
            implode(', ', $categoryTitles),
        ];
    }

    public function getOffsetLimitSettings(): void
    {
        $offset = $this->getFieldFromFlexform('settings.offset', 'additional');
        $limit = $this->getFieldFromFlexform('settings.limit', 'additional');
        $hidePagination = $this->getFieldFromFlexform('settings.hidePagination', 'additional');

        if ($offset) {
            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.offset'),
                $offset,
            ];
        }
        if ($limit) {
            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.limit'),
                $limit,
            ];
        }
        if ($hidePagination) {
            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_additional.hidePagination'),
                '<i class="fa fa-check"></i>',
            ];
        }
    }

    public function getDateMenuSettings(): void
    {
        $dateMenuField = $this->getFieldFromFlexform('settings.dateField');

        $this->tableData[] = [
            $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.dateField'),
            $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.dateField.' . $dateMenuField),
        ];
    }

    public function getTimeRestrictionSetting(): void
    {
        $timeRestriction = $this->getFieldFromFlexform('settings.timeRestriction');

        if (!empty($timeRestriction)) {
            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.timeRestriction'),
                htmlspecialchars($timeRestriction),
            ];
        }

        $timeRestrictionHigh = $this->getFieldFromFlexform('settings.timeRestrictionHigh');
        if (!empty($timeRestrictionHigh)) {
            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.timeRestrictionHigh'),
                htmlspecialchars($timeRestrictionHigh),
            ];
        }
    }

    public function getTopNewsRestrictionSetting(): void
    {
        $topNewsRestriction = (int)$this->getFieldFromFlexform('settings.topNewsRestriction');
        if ($topNewsRestriction > 0) {
            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.topNewsRestriction'),
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.topNewsRestriction.' . $topNewsRestriction),
            ];
        }
    }

    public function getTemplateLayoutSettings(int $pageUid): void
    {
        $title = '';
        $field = $this->getFieldFromFlexform('settings.templateLayout', 'template');

        // Find correct title by looping over all options
        if (!empty($field)) {
            $layouts = $this->templateLayoutsUtility->getAvailableTemplateLayouts($pageUid);
            foreach ($layouts as $layout) {
                if ((string)$layout[1] === (string)$field) {
                    $title = $layout[0];
                }
            }
        }

        if (!empty($title)) {
            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_template.templateLayout'),
                $this->getLanguageService()->sL($title),
            ];
        }
    }

    public function getOverrideDemandSettings(): void
    {
        $field = (bool)$this->getFieldFromFlexform('settings.disableOverrideDemand', 'additional');

        if ($field) {
            $this->tableData[] = [
                $this->getLanguageService()->sL(
                    self::LLPATH . 'flexforms_additional.disableOverrideDemand'
                ),
                '<i class="fa fa-check"></i>',
            ];
        }
    }

    public function getStartingPoint(): void
    {
        $value = $this->getFieldFromFlexform('settings.startingpoint');

        if (!empty($value)) {
            $pageIds = GeneralUtility::intExplode(',', $value, true);
            $pagesOut = [];

            foreach ($pageIds as $id) {
                $pagesOut[] = $this->getRecordData($id, 'pages');
            }

            $recursiveLevel = (int)$this->getFieldFromFlexform('settings.recursive');
            $recursiveLevelText = '';
            if ($recursiveLevel === 250) {
                $recursiveLevelText = $this->getLanguageService()->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.5');
            } elseif ($recursiveLevel > 0) {
                $recursiveLevelText = $this->getLanguageService()->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.' . $recursiveLevel);
            }

            if (!empty($recursiveLevelText)) {
                $recursiveLevelText = '<br />' .
                    htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.recursive')) . ' ' .
                    $recursiveLevelText;
            }

            $this->tableData[] = [
                $this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.startingpoint'),
                implode(', ', $pagesOut) . $recursiveLevelText,
            ];
        }
    }

    protected function getSelectedListSetting(): void
    {
        $value = $this->getFieldFromFlexform('settings.selectedList');

        if (!empty($value)) {
            $idList = GeneralUtility::intExplode(',', $value, true);
            $out = [];

            foreach ($idList as $id) {
                $out[] = $this->getRecordData($id, 'tx_news_domain_model_news');
            }

            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.selectedList'),
                implode('<br>', $out),
            ];
        }
    }

    protected function generateCallout(string $text): string
    {
        return '<div class="alert alert-warning">' . htmlspecialchars($text) . '</div>';
    }

    protected function renderSettingsAsTable(string $header = '', int $recordUid = 0): string
    {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        if ((new Typo3Version())->getMajorVersion() >= 12) {
            $pageRenderer->loadJavaScriptModule('@georgringer/news/page-layout.js');
        } else {
            $pageRenderer->loadRequireJsModule('TYPO3/CMS/News/PageLayout');
        }
        $pageRenderer->addCssFile('EXT:news/Resources/Public/Css/Backend/PageLayoutView.css');

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:news/Resources/Private/Backend/PageLayoutView.html'));
        $view->assignMultiple([
            'header' => $header,
            'rows' => [
                'above' => array_slice($this->tableData, 0, self::SETTINGS_IN_PREVIEW),
                'below' => array_slice($this->tableData, self::SETTINGS_IN_PREVIEW),
            ],
            'id' => $recordUid,
        ]);

        return $view->render();
    }

    public function getFieldFromFlexform(string $key, string $sheet = 'sDEF'): ?string
    {
        $flexform = $this->flexformData;
        if (isset($flexform['data'])) {
            $flexform = $flexform['data'];
            if (isset($flexform[$sheet]['lDEF'][$key]['vDEF'])
            ) {
                return $flexform[$sheet]['lDEF'][$key]['vDEF'];
            }
        }

        return null;
    }

    protected function getEditLink(array $row, int $currentPageUid): string
    {
        $editLink = '';
        $localCalcPerms = $GLOBALS['BE_USER']->calcPerms(BackendUtilityCore::getRecord('pages', $row['uid']));
        $permsEdit = $localCalcPerms & Permission::PAGE_EDIT;
        if ($permsEdit) {
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $returnUrl = $uriBuilder->buildUriFromRoute('web_layout', ['id' => $currentPageUid]);
            $editLink = $uriBuilder->buildUriFromRoute('web_layout', [
                'id' => $row['uid'],
                'returnUrl' => $returnUrl,
            ]);
        }
        return (string)$editLink;
    }
}
