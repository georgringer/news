<?php

defined('TYPO3') or die();

call_user_func(static function () {
    $versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
    if ($versionInformation->getMajorVersion() === 11) {
        $showRemovedLocalizationRecordsFields = [
            'sys_category' => ['images'],
            'tx_news_domain_model_news' => ['related_links', 'content_elements', 'fal_media', 'fal_related_files']
        ];

        foreach ($showRemovedLocalizationRecordsFields as $tableName => $fields) {
            foreach ($fields as $field) {
                unset($GLOBALS['TCA'][$tableName]['columns'][$field]['config']['appearance']['showRemovedLocalizationRecords']);
            }
        }
    }
});
