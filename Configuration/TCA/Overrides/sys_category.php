<?php

defined('TYPO3') or die;

$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';
$configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\GeorgRinger\News\Domain\Model\Dto\EmConfiguration::class);

$imageSettings = [
    'behaviour' => [
        'allowLanguageSynchronization' => true,
    ],
    'overrideChildTca' => [
        'types' => [
            '0' => [
                'showitem' => '--palette--;;imageoverlayPalette, --palette--;;filePalette',
            ],
            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                'showitem' => '--palette--;;imageoverlayPalette, --palette--;;filePalette',
            ],
        ],
    ],
    'appearance' => [
        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
        'showPossibleLocalizationRecords' => true,
        'showAllLocalizationLink' => true,
        'showSynchronizationLink' => true,
    ],
    'foreign_match_fields' => [
        'fieldname' => 'images',
        'tablenames' => 'sys_category',
        'table_local' => 'sys_file',
    ],
];

$versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
if ($versionInformation->getMajorVersion() > 11) {
    $imageConfiguration = [
        'type' => 'file',
        'appearance' => $imageSettings['appearance'],
        'behaviour' => $imageSettings['behaviour'],
        'allowed' => 'common-image-types',
    ];
} else {
    /** @noinspection PhpDeprecationInspection */
    // @extensionScannerIgnoreLine
    $imageConfiguration = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
        'image',
        $imageSettings,
        $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
    );
}

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
        'config' => $imageConfiguration,
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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category', $newSysCategoryColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.options, images',
    '',
    'before:description'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'single_pid',
    '',
    'after:description'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'shortcut',
    '',
    'after:single_pid'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    '--div--;' . $ll . 'tx_news_domain_model_category.tabs.seo, seo_title, seo_description, seo_headline, seo_text',
    '',
    'after:endtime'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'slug',
    '',
    'after:title'
);

$GLOBALS['TCA']['sys_category']['columns']['items']['config']['MM_oppositeUsage']['tx_news_domain_model_news']
    = [0 => 'categories'];

$GLOBALS['TCA']['sys_category']['ctrl']['label_userFunc'] =
    \GeorgRinger\News\Hooks\Labels::class . '->getUserLabelCategory';
