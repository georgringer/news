<?php

namespace GeorgRinger\News\Hooks;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into \TYPO3\CMS\Backend\Utility\BackendUtility to change flexform behaviour
 * depending on action selection
 *
 */
class BackendUtility
{

    /**
     * Fields which are removed in detail view
     *
     * @var array
     */
    public $removedFieldsInDetailView = [
        'sDEF' => 'orderBy,orderDirection,categories,categoryConjunction,includeSubCategories,
						archiveRestriction,timeRestriction,timeRestrictionHigh,topNewsRestriction,
						dateField,selectedList',
        'additional' => 'limit,offset,hidePagination,topNewsFirst,listPid,list.paginate.itemsPerPage',
        'template' => 'cropMaxCharacters'
    ];

    /**
     * Fields which are removed in list view
     *
     * @var array
     */
    public $removedFieldsInListView = [
        'sDEF' => 'dateField,singleNews,previewHiddenRecords,selectedList',
        'additional' => '',
        'template' => ''
    ];

    /**
     * Fields which are removed in selected list view
     *
     * @var array
     */
    public $removedFieldsInSelectedListView = [
        'sDEF' => 'orderBy,orderDirection,categories,categoryConjunction,includeSubCategories,
						archiveRestriction,timeRestriction,timeRestrictionHigh,topNewsRestriction,
						startingpoint,recursive,dateField,singleNews,previewHiddenRecords,
                        previewHiddenRecords,startingpoint,recursive',
        'additional' => 'tags,limit,offset,hidePagination,topNewsFirst,detailPid,backPid,excludeAlreadyDisplayedNews,
								list.paginate.itemsPerPage,disableOverrideDemand',
        'template' => ''
    ];

    /**
     * Fields which are removed in dateMenu view
     *
     * @var array
     */
    public $removedFieldsInDateMenuView = [
        'sDEF' => 'orderBy,singleNews,selectedList',
        'additional' => 'limit,offset,hidePagination,topNewsFirst,backPid,previewHiddenRecords,excludeAlreadyDisplayedNews,
								list.paginate.itemsPerPage',
        'template' => 'cropMaxCharacters,media.maxWidth,media.maxHeight'
    ];

    /**
     * Fields which are removed in search form view
     *
     * @var array
     */
    public $removedFieldsInSearchFormView = [
        'sDEF' => 'orderBy,orderDirection,categories,categoryConjunction,includeSubCategories,
						archiveRestriction,timeRestriction,timeRestrictionHigh,topNewsRestriction,
						startingpoint,recursive,dateField,singleNews,previewHiddenRecords,selectedList',
        'additional' => 'limit,offset,hidePagination,topNewsFirst,detailPid,backPid,excludeAlreadyDisplayedNews,
								list.paginate.itemsPerPage',
        'template' => 'cropMaxCharacters,media.maxWidth,media.maxHeight'
    ];

    /**
     * Fields which are removed in category list view
     *
     * @var array
     */
    public $removedFieldsInCategoryListView = [
        'sDEF' => 'orderBy,orderDirection,categoryConjunction,includeSubCategories,
						archiveRestriction,timeRestriction,timeRestrictionHigh,topNewsRestriction,
						recursive,dateField,singleNews,previewHiddenRecords,selectedList',
        'additional' => 'limit,offset,hidePagination,topNewsFirst,detailPid,backPid,excludeAlreadyDisplayedNews,
								list.paginate.itemsPerPage',
        'template' => 'cropMaxCharacters,media.maxWidth,media.maxHeight'
    ];

    /**
     * Fields which are removed in tag list view
     *
     * @var array
     */
    public $removedFieldsInTagListView = [
        'sDEF' => 'categories,categoryConjunction,includeSubCategories,
						archiveRestriction,timeRestriction,timeRestrictionHigh,topNewsRestriction,
						dateField,singleNews,previewHiddenRecords,selectedList',
        'additional' => 'limit,offset,hidePagination,topNewsFirst,detailPid,backPid,excludeAlreadyDisplayedNews,
								list.paginate.itemsPerPage',
        'template' => 'cropMaxCharacters,media.maxWidth,media.maxHeight'
    ];

    /** @var EmConfiguration */
    protected $configuration;

    public function __construct()
    {
        $this->configuration = \GeorgRinger\News\Utility\EmConfiguration::getSettings();
    }

    /**
     * Hook function of \TYPO3\CMS\Backend\Utility\BackendUtility
     * It is used to change the flexform if it is about news
     *
     * @param array &$dataStructure Flexform structure
     * @param array $conf some strange configuration
     * @param array $row row of current record
     * @param string $table table name
     */
    public function getFlexFormDS_postProcessDS(&$dataStructure, $conf, $row, $table)
    {
        if ($table === 'tt_content' && $row['CType'] === 'list' && $row['list_type'] === 'news_pi1' && is_array($dataStructure)) {
            $this->updateFlexforms($dataStructure, $row);

            if ($this->enabledInTsConfig($row['pid'])) {
                $this->addCategoryConstraints($dataStructure);
            }
        }
    }

    /**
     * @param array $dataStructure
     * @param array $identifier
     * @return array
     */
    public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier)
    {
        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === 'news_pi1,list') {
            $getVars = GeneralUtility::_GET('edit');
            if (is_array($getVars['tt_content'])) {
                $item = array_keys($getVars['tt_content']);
                $row = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tt_content', (int)$item[0]);
                if (is_array($row)) {
                    $this->updateFlexforms($dataStructure, $row);
                }
            }
        }
        return $dataStructure;
    }

    /**
     * Update flexform configuration if a action is selected
     *
     * @param array|string &$dataStructure flexform structure
     * @param array $row row of current record
     */
    protected function updateFlexforms(array &$dataStructure, array $row)
    {
        $selectedView = '';

        // get the first selected action
        if (is_string($row['pi_flexform'])) {
            $flexformSelection = GeneralUtility::xml2array($row['pi_flexform']);
        } else {
            $flexformSelection = $row['pi_flexform'];
        }
        if (is_array($flexformSelection) && is_array($flexformSelection['data'])) {
            $selectedView = $flexformSelection['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];
            if (!empty($selectedView)) {
                $actionParts = GeneralUtility::trimExplode(';', $selectedView, true);
                $selectedView = $actionParts[0];
            }

            // new plugin element
        } elseif (GeneralUtility::isFirstPartOfStr($row['uid'], 'NEW')) {
            // use List as starting view
            $selectedView = 'News->list';
        }

        if (!empty($selectedView)) {
            // Modify the flexform structure depending on the first found action
            switch ($selectedView) {
                case 'News->list':
                case 'News->searchResult':
                    $this->deleteFromStructure($dataStructure, $this->removedFieldsInListView);
                    break;
                case 'News->detail':
                    $this->deleteFromStructure($dataStructure, $this->removedFieldsInDetailView);
                    break;
                case 'News->selectedList':
                    $this->deleteFromStructure($dataStructure, $this->removedFieldsInSelectedListView);
                    break;
                case 'News->searchForm':
                    $this->deleteFromStructure($dataStructure, $this->removedFieldsInSearchFormView);
                    break;
                case 'News->dateMenu':
                    $this->deleteFromStructure($dataStructure, $this->removedFieldsInDateMenuView);
                    break;
                case 'Category->list':
                    $this->deleteFromStructure($dataStructure, $this->removedFieldsInCategoryListView);
                    break;
                case 'Tag->list':
                    $this->deleteFromStructure($dataStructure, $this->removedFieldsInTagListView);
                    break;
                default:
            }

            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Hooks/BackendUtility.php']['updateFlexforms'])) {
                $params = [
                    'selectedView' => $selectedView,
                    'dataStructure' => &$dataStructure,
                ];
                foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Hooks/BackendUtility.php']['updateFlexforms'] as $reference) {
                    GeneralUtility::callUserFunction($reference, $params, $this);
                }
            }
        }
    }

    /**
     * Add category restriction to flexforms
     *
     * @param array $structure
     */
    protected function addCategoryConstraints(&$structure)
    {
        $categoryRestrictionSetting = $this->configuration->getCategoryRestriction();
        $categoryRestriction = '';
        switch ($categoryRestrictionSetting) {
            case 'current_pid':
                $categoryRestriction = ' AND sys_category.pid=###CURRENT_PID### ';
                break;
            case 'siteroot':
                $categoryRestriction = ' AND sys_category.pid IN (###SITEROOT###) ';
                break;
            case 'page_tsconfig':
                $categoryRestriction = ' AND sys_category.pid IN (###PAGE_TSCONFIG_IDLIST###) ';
                break;
        }

        if (!empty($categoryRestriction) && isset($structure['sheets']['sDEF']['ROOT']['el']['settings.categories'])) {
            $structure['sheets']['sDEF']['ROOT']['el']['settings.categories']['TCEforms']['config']['foreign_table_where'] = $categoryRestriction . $structure['sheets']['sDEF']['ROOT']['el']['settings.categories']['TCEforms']['config']['foreign_table_where'];
        }
    }

    /**
     * Remove fields from flexform structure
     *
     * @param array &$dataStructure flexform structure
     * @param array $fieldsToBeRemoved fields which need to be removed
     */
    protected function deleteFromStructure(array &$dataStructure, array $fieldsToBeRemoved)
    {
        foreach ($fieldsToBeRemoved as $sheetName => $sheetFields) {
            $fieldsInSheet = GeneralUtility::trimExplode(',', $sheetFields, true);

            foreach ($fieldsInSheet as $fieldName) {
                unset($dataStructure['sheets'][$sheetName]['ROOT']['el']['settings.' . $fieldName]);
            }
        }
    }

    /**
     * @param int $pageId
     * @return bool
     */
    protected function enabledInTsConfig($pageId)
    {
        $tsConfig = BackendUtilityCore::getPagesTSconfig($pageId);
        if (isset($tsConfig['tx_news.']['categoryRestrictionForFlexForms'])) {
            return (bool)$tsConfig['tx_news.']['categoryRestrictionForFlexForms'];
        }
        return false;
    }
}
