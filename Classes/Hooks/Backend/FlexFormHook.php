<?php

namespace GeorgRinger\News\Hooks\Backend;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;


/**
 * Add category constraints to category settings
 */
class FlexFormHook
{
    /** @var EmConfiguration */
    protected $configuration;

    public function __construct()
    {
        $this->configuration = \GeorgRinger\News\Utility\EmConfiguration::getSettings();
    }

    public function getFlexFormDS_postProcessDS(&$structure, $conf, $row, $table, $fieldName)
    {
        if ($table === 'tt_content' && $fieldName === 'pi_flexform' && is_array($row) && $row['CType'] === 'list' && $row['list_type'] === 'news_pi1') {
            if ($this->enabledInTsConfig($row['pid'])) {
                $this->updateFlexForms($structure);
            }
        }
    }

    /**
     * Add category restriction to flexforms
     *
     * @param array $structure
     */
    protected function updateFlexForms(&$structure)
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

        if (!empty($categoryRestriction)) {
            $structure['sheets']['sDEF']['ROOT']['el']['settings.categories']['TCEforms']['config']['foreign_table_where'] = $categoryRestriction . $structure['sheets']['sDEF']['ROOT']['el']['settings.categories']['TCEforms']['config']['foreign_table_where'];
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