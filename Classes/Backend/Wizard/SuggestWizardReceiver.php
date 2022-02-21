<?php

declare(strict_types=1);

namespace GeorgRinger\News\Backend\Wizard;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Backend\Form\Wizard\SuggestWizardDefaultReceiver;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class SuggestWizardReceiver extends SuggestWizardDefaultReceiver
{
    public function queryTable(&$params, $recursionCounter = 0)
    {
        $rows = parent::queryTable($params, $recursionCounter);

        $searchString = strtolower($params['value']);
        $matchRow = array_filter($rows, function ($value) use ($searchString) {
            return strtolower($value['label']) === $searchString;
        });

        if (empty($matchRow)) {
            $newUid = StringUtility::getUniqueId('NEW');
            $rows[$this->table . '_' . $newUid] = [
                'class' => '',
                'label' => $params['value'],
                'path' => '',
                'sprite' => $this->iconFactory->getIconForRecord($this->table, [], Icon::SIZE_SMALL)->render(),
                'style' => '',
                'table' => $this->table,
                'text' => sprintf($this->getLanguageService()->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:tag_suggest'), $params['value']),
                'uid' => $newUid,
            ];
            $configuration = GeneralUtility::makeInstance(EmConfiguration::class);
            $pid = $configuration->getTagPid();
            if ($pid === 0) {
                $pid = $this->getTagPidFromTsConfig($params['uid']);
            }

            if ($pid !== 0) {
                $rows[$this->table . '_' . $newUid]['pid'] = $pid;
            }
        }

        return $rows;
    }

    /**
     * Get pid for tags from TsConfig
     *
     * @param int $newsUid uid of current news record
     * @return int
     */
    protected function getTagPidFromTsConfig($newsUid): int
    {
        $pid = 0;

        $newsRecord = BackendUtilityCore::getRecord('tx_news_domain_model_news', (int)$newsUid);

        $pagesTsConfig = BackendUtilityCore::getPagesTSconfig($newsRecord['pid']);
        if (isset($pagesTsConfig['tx_news.']) && isset($pagesTsConfig['tx_news.']['tagPid'])) {
            $pid = (int)$pagesTsConfig['tx_news.']['tagPid'];
        }

        return $pid;
    }
}
