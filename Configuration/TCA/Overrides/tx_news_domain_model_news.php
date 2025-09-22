<?php

use GeorgRinger\News\Hooks\NewsContentElementPreview;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die;

$boot = static function (): void {
    // Add seo sitemap fields
    if (ExtensionManagementUtility::isLoaded('seo')) {
        ExtensionManagementUtility::addTCAcolumns(
            'tx_news_domain_model_news',
            [
                'sitemap_changefreq' => [
                    'config' => [
                        'items' => [
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
                        'items' => [
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

        ExtensionManagementUtility::addToAllTCAtypes(
            'tx_news_domain_model_news',
            '--palette--;;sitemap',
            '',
            'after:alternative_title'
        );
    }

    if (!ExtensionManagementUtility::isLoaded('news_content_elements')) {
        $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['content_elements']['config']['customControls']['ce'] = [
            'userFunc' => NewsContentElementPreview::class . '->run',
        ];
    }
};

$boot();
unset($boot);
