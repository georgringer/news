<?php
namespace GeorgRinger\News\Backend\FormDataProvider;

/*
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

use GeorgRinger\News\Utility\EmConfiguration;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Fill the news records with default values
 */
class NewsFlexFormManipulation implements FormDataProviderInterface
{
    /**
     * Fields which are removed in detail view
     *
     * @var array
     */
    protected $removedFieldsInDetailView = [
        'sDEF' => [
            'orderBy', 'orderDirection', 'categories', 'categoryConjunction', 'includeSubCategories',
            'archiveRestriction', 'timeRestriction', 'timeRestrictionHigh', 'topNewsRestriction',
            'dateField'
        ],
        'additional' => [
            'limit', 'offset', 'hidePagination', 'topNewsFirst', 'listPid', 'list.paginate.itemsPerPage'
        ],
        'template' => [ 'cropMaxCharacters' ],
    ];

    /**
     * Fields which are removed in list view
     *
     * @var array
     */
    protected $removedFieldsInListView = [
        'sDEF' => [
            'dateField', 'singleNews', 'previewHiddenRecords'
        ],
        'additional' => [],
        'template' => [],
    ];

    /**
     * Fields which are removed in dateMenu view
     *
     * @var array
     */
    protected $removedFieldsInDateMenuView = [
        'sDEF' => [
            'orderBy', 'singleNews'
        ],
        'additional' => [
            'limit', 'offset', 'hidePagination', 'topNewsFirst' ,'backPid', 'previewHiddenRecords', 'excludeAlreadyDisplayedNews',
            'list.paginate.itemsPerPage'
        ],
        'template' => [
            'cropMaxCharacters', 'media.maxWidth', 'media.maxHeight'
        ],
    ];

    /**
     * Fields which are removed in search form view
     *
     * @var array
     */
    protected $removedFieldsInSearchFormView = [
        'sDEF' => [
            'orderBy', 'orderDirection', 'categories', 'categoryConjunction', 'includeSubCategories',
            'archiveRestriction', 'timeRestriction', 'timeRestrictionHigh', 'topNewsRestriction',
            'startingpoint', 'recursive', 'dateField', 'singleNews', 'previewHiddenRecords'
        ],
        'additional' => [
            'limit', 'offset', 'hidePagination', 'topNewsFirst', 'detailPid', 'backPid', 'excludeAlreadyDisplayedNews',
            'list.paginate.itemsPerPage'
        ],
        'template' => [
            'cropMaxCharacters', 'media.maxWidth', 'media.maxHeight'
        ],
    ];

    /**
     * Fields which are removed in category list view
     *
     * @var array
     */
    protected $removedFieldsInCategoryListView = [
        'sDEF' => [
            'orderBy', 'orderDirection', 'categoryConjunction', 'includeSubCategories',
            'archiveRestriction', 'timeRestriction', 'timeRestrictionHigh', 'topNewsRestriction',
            'recursive', 'dateField', 'singleNews', 'previewHiddenRecords',
        ],
        'additional' => [
            'limit', 'offset', 'hidePagination', 'topNewsFirst', 'detailPid', 'backPid', 'excludeAlreadyDisplayedNews',
            'list.paginate.itemsPerPage'
        ],
        'template' => [
            'cropMaxCharacters', 'media.maxWidth', 'media.maxHeight'
        ],
    ];

    /**
     * Fields which are removed in tag list view
     *
     * @var array
     */
    protected $removedFieldsInTagListView = [
        'sDEF' => [
            'categories', 'categoryConjunction', 'includeSubCategories',
            'archiveRestriction', 'timeRestriction', 'timeRestrictionHigh', 'topNewsRestriction',
            'dateField', 'singleNews', 'previewHiddenRecords'
        ],
        'additional' => [
            'limit', 'offset', 'hidePagination', 'topNewsFirst', 'detailPid', 'backPid', 'excludeAlreadyDisplayedNews',
            'list.paginate.itemsPerPage'
        ],
        'template' => [
            'cropMaxCharacters', 'media.maxWidth', 'media.maxHeight'
        ]
    ];

    /**
     * @var EmConfiguration
     */
    protected $configuration;

    /**
     * NewsFlexFormManipulation constructor.
     */
    public function __construct()
    {
        $this->configuration = EmConfiguration::getSettings();
    }

    /**
     * Remove fields depending on switchable controller action in tt_content
     * Restrict category selection based on configuration in tt_content
     *
     * @param array $result
     * @return array
     */
    public function addData(array $result)
    {
        if ($result['tableName'] === 'tt_content'
            && $result['databaseRow']['CType'] === 'list'
            && $result['databaseRow']['list_type'] === 'news_pi1'
            && is_array($result['processedTca']['columns']['pi_flexform']['config']['ds'])
        ) {
            $result = $this->updateFlexForms($result);
            if ($this->enabledInTsConfig($result)) {
                $result = $this->addCategoryConstraints($result);
            }
        }

        return $result;
    }

    /**
     * Update flexform configuration if a action is selected
     *
     * @param array $result Full data
     * @return array Modified data
     */
    protected function updateFlexForms(array $result)
    {
        $selectedView = '';
        $row = $result['databaseRow'];
        $dataStructure = $result['processedTca']['columns']['pi_flexform']['config']['ds'];

        // get the first selected action
        $flexformSelection = $row['pi_flexform'];
        if (is_array($flexformSelection)
            && is_array($flexformSelection['data'])
            && !empty($flexformSelection['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'])
        ) {
            $selectedView = $flexformSelection['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];
            $actionParts = GeneralUtility::trimExplode(';', $selectedView, true);
            $selectedView = $actionParts[0];
        } elseif ($result['command'] === 'new') {
            // new plugin element, use List as starting view
            $selectedView = 'News->list';
        }

        if (!empty($selectedView)) {
            // Modify the flexform structure depending on the first found action
            switch ($selectedView) {
                case 'News->list':
                case 'News->searchResult':
                    $dataStructure = $this->deleteFromStructure($dataStructure, $this->removedFieldsInListView);
                    break;
                case 'News->detail':
                    $dataStructure = $this->deleteFromStructure($dataStructure, $this->removedFieldsInDetailView);
                    break;
                case 'News->searchForm':
                    $dataStructure = $this->deleteFromStructure($dataStructure, $this->removedFieldsInSearchFormView);
                    break;
                case 'News->dateMenu':
                    $dataStructure = $this->deleteFromStructure($dataStructure, $this->removedFieldsInDateMenuView);
                    break;
                case 'Category->list':
                    $dataStructure = $this->deleteFromStructure($dataStructure, $this->removedFieldsInCategoryListView);
                    break;
                case 'Tag->list':
                    $dataStructure = $this->deleteFromStructure($dataStructure, $this->removedFieldsInTagListView);
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

        $result['processedTca']['columns']['pi_flexform']['config']['ds'] = $dataStructure;

        return $result;
    }

    /**
     * Add category restriction to flexforms
     *
     * @param array $result
     * @return array Modified result
     */
    protected function addCategoryConstraints($result)
    {
        $structure = $result['processedTca']['columns']['pi_flexform']['config']['ds'];
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

        if (!empty($categoryRestriction)) {
            $structure['sheets']['sDEF']['ROOT']['el']['settings.categories']['TCEforms']['config']['foreign_table_where'] = $categoryRestriction . $structure['sheets']['sDEF']['ROOT']['el']['settings.categories']['TCEforms']['config']['foreign_table_where'];
        }
        $result['processedTca']['columns']['pi_flexform']['config']['ds'] = $structure;
    }

    /**
     * Remove fields from flexform structure
     *
     * @param array &$dataStructure flexform structure
     * @param array $fieldsToBeRemoved fields which need to be removed
     * @return array Modified structure
     */
    protected function deleteFromStructure(array $dataStructure, array $fieldsToBeRemoved)
    {
        foreach ($fieldsToBeRemoved as $sheetName => $fieldsInSheet) {
            foreach ($fieldsInSheet as $fieldName) {
                unset($dataStructure['sheets'][$sheetName]['ROOT']['el']['settings.' . $fieldName]);
            }
        }
        return $dataStructure;
    }

    /**
     * @param array $result Incoming array
     * @return bool
     */
    protected function enabledInTsConfig(array $result)
    {
        if (isset($result['pageTsConfig']['tx_news.']['categoryRestrictionForFlexForms'])) {
            return (bool)$result['pageTsConfig']['tx_news.']['categoryRestrictionForFlexForms'];
        }
        return false;
    }
}
