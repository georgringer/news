<?php

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('reactions')) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'sys_reaction',
        'table_name',
        [
            'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news',
            'tx_news_domain_model_news',
            'ext-news-type-default',
        ]
    );
}
