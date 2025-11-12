<?php

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die;

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';
$configuration = GeneralUtility::makeInstance(EmConfiguration::class);

/**
 * Add extra fields to the sys_category record
 */
$newSysCategoryColumns = [
    'pid' => [
        'label' => 'pid',
        'config' => [
            'type' => 'passthrough',
        ],
    ],
    'sorting' => [
        'label' => 'sorting',
        'config' => [
            'type' => 'passthrough',
        ],
    ],
    'crdate' => [
        'label' => 'crdate',
        'config' => [
            'type' => 'passthrough',
        ],
    ],
    'tstamp' => [
        'label' => 'tstamp',
        'config' => [
            'type' => 'passthrough',
        ],
    ],
    'images' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.image',
        'config' => [
            'type' => 'file',
            'appearance' => [
                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                'showPossibleLocalizationRecords' => true,
                'showAllLocalizationLink' => true,
                'showSynchronizationLink' => true,
            ],
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'allowed' => 'common-image-types',
        ],
    ],
    'single_pid' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.single_pid',
        'config' => [
            'type' => 'group',
            'allowed' => 'pages',
            'size' => 1,
            'maxitems' => 1,
            'default' => 0,
            'suggestOptions' => [
                'default' => [
                    'searchWholePhrase' => true,
                ],
            ],
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ],
    ],
    'shortcut' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.shortcut',
        'config' => [
            'type' => 'group',
            'allowed' => 'pages',
            'size' => 1,
            'maxitems' => 1,
            'default' => 0,
            'suggestOptions' => [
                'default' => [
                    'searchWholePhrase' => true,
                ],
            ],
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ],
    ],
    'import_id' => [
        'label' => $ll . 'tx_news_domain_model_news.import_id',
        'config' => [
            'type' => 'passthrough',
        ],
    ],
    'import_source' => [
        'label' => $ll . 'tx_news_domain_model_news.import_source',
        'config' => [
            'type' => 'passthrough',
        ],
    ],
    'seo_headline' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.seo.seo_headline',
        'config' => [
            'type' => 'input',
        ],
    ],
    'seo_title' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.seo.seo_title',
        'config' => [
            'type' => 'input',
        ],
    ],
    'seo_description' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.seo.seo_description',
        'config' => [
            'type' => 'text',
        ],
    ],
    'seo_text' => [
        'exclude' => true,
        'label' => $ll . 'tx_news_domain_model_category.seo.seo_text',
        'config' => [
            'type' => 'text',
            'enableRichtext' => true,
        ],
    ],
    'slug' => [
        'exclude' => true,
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
        'displayCond' => 'VERSION:IS:false',
        'config' => [
            'type' => 'slug',
            'size' => 50,
            'generatorOptions' => [
                'fields' => ['title'],
                'replacements' => [
                    '/' => '-',
                ],
            ],
            'fallbackCharacter' => '-',
            'eval' => $configuration->getSlugBehaviour(),
            'default' => '',
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('sys_category', $newSysCategoryColumns);
ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.options, images',
    '',
    'before:description'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'single_pid',
    '',
    'after:description'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'shortcut',
    '',
    'after:single_pid'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    '--div--;' . $ll . 'tx_news_domain_model_category.tabs.seo, seo_title, seo_description, seo_headline, seo_text',
    '',
    'after:endtime'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'slug',
    '',
    'after:title'
);

$GLOBALS['TCA']['sys_category']['columns']['items']['config']['MM_oppositeUsage']['tx_news_domain_model_news']
    = [0 => 'categories'];
