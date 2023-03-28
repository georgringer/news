<?php

defined('TYPO3') or die;

$boot = static function (): void {
    $versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);

    // Add seo sitemap fields
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('seo')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
            'tx_news_domain_model_news',
            [
                'sitemap_changefreq' => [
                    'config' => [
                        'items' => $versionInformation->getMajorVersion() < 12 ? [
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.none', ''],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.always', 'always'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.hourly', 'hourly'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.daily', 'daily'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.weekly', 'weekly'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.monthly', 'monthly'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.yearly', 'yearly'],
                            ['LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.never', 'never'],
                        ] : [
                            ['label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.none', 'value' => ''],
                            ['label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.always', 'value' => 'always'],
                            ['label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.hourly', 'value' => 'hourly'],
                            ['label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.daily', 'value' => 'daily'],
                            ['label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.weekly', 'value' => 'weekly'],
                            ['label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.monthly', 'value' => 'monthly'],
                            ['label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.yearly', 'value' => 'yearly'],
                            ['label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq.never', 'value' => 'never'],
                        ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                    'exclude' => true,
                    'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_changefreq',
                ],
                'sitemap_priority' => [
                    'config' => [
                        'items' => $versionInformation->getMajorVersion() < 12 ? [
                            ['0.0', '0.0'],
                            ['0.1', '0.1'],
                            ['0.2', '0.2'],
                            ['0.3', '0.3'],
                            ['0.4', '0.4'],
                            ['0.5', '0.5'],
                            ['0.6', '0.6'],
                            ['0.7', '0.7'],
                            ['0.8', '0.8'],
                            ['0.9', '0.9'],
                            ['1.0', '1.0'],
                        ] : [
                            ['label' => '0.0', 'value' => '0.0'],
                            ['label' => '0.1', 'value' => '0.1'],
                            ['label' => '0.2', 'value' => '0.2'],
                            ['label' => '0.3', 'value' => '0.3'],
                            ['label' => '0.4', 'value' => '0.4'],
                            ['label' => '0.5', 'value' => '0.5'],
                            ['label' => '0.6', 'value' => '0.6'],
                            ['label' => '0.7', 'value' => '0.7'],
                            ['label' => '0.8', 'value' => '0.8'],
                            ['label' => '0.9', 'value' => '0.9'],
                            ['label' => '1.0', 'value' => '1.0'],
                        ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                        'default' => '0.5',
                    ],
                    'exclude' => true,
                    'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.sitemap_priority',
                ],
            ]
        );

        $GLOBALS['TCA']['tx_news_domain_model_news']['palettes']['sitemap'] = [
            'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.sitemap',
            'showitem' => 'sitemap_changefreq,sitemap_priority',
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tx_news_domain_model_news',
            '--palette--;;sitemap',
            '',
            'after:alternative_title'
        );
    }
};

$boot();
unset($boot);
