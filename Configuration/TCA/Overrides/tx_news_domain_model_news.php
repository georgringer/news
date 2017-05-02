<?php
defined('TYPO3_MODE') or die();

if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 8007000) {
    $config = $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['fal_media']['config']['foreign_types'];
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['fal_media']['config']['overrideChildTca']['types'] = $config;

    unset($GLOBALS['TCA']['tx_news_domain_model_news']['columns']['fal_media']['config']['foreign_types']);
}
