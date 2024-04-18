<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (ExtensionManagementUtility::isLoaded('reactions')) {
    ExtensionManagementUtility::addTcaSelectItem(
        'sys_reaction',
        'table_name',
        [
            'label' => 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news',
            'value' => 'tx_news_domain_model_news',
            'icon' => 'ext-news-type-default',
        ]
    );
}
