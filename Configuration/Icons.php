<?php

$iconList = [];
foreach (['apps-pagetree-folder-contains-news' => 'ext-news-folder-tree.svg',
    'apps-pagetree-page-contains-news' => 'ext-news-page-tree.svg',
    'ext-news-wizard-icon' => 'plugin-list-with-detail.svg',
    'ext-news-plugin-news-list' => 'plugin-list-with-detail.svg',
    'ext-news-plugin-news-list-sticky' => 'plugin-list.svg',
    'ext-news-plugin-detail' => 'plugin-detail.svg',
    'ext-news-plugin-news-date-menu' => 'plugin-date-menu.svg',
    'ext-news-plugin-news-search-form' => 'plugin-search-form.svg',
    'ext-news-plugin-news-search-result' => 'plugin-search-results.svg',
    'ext-news-plugin-news-selected-list' => 'plugin-selected-list.svg',
    'ext-news-plugin-category-list' => 'plugin-category-menu.svg',
    'ext-news-plugin-tag-list' => 'plugin-tag-menu.svg',
    'ext-news-type-default' => 'news_domain_model_news.svg',
    'ext-news-type-internal' => 'news_domain_model_news_internal.svg',
    'ext-news-type-external' => 'news_domain_model_news_external.svg',
    'ext-news-tag' => 'news_domain_model_tag.svg',
    'ext-news-link' => 'news_domain_model_link.svg',
    'ext-news-donation' => 'donation.svg',
    'ext-news-paypal' => 'donation_paypal.svg',
    'ext-news-patreon' => 'donation_patreon.svg',
    'ext-news-amazon' => 'donation_amazon.svg',
    'ext-news-doublecheck' => 'double_check.svg',
] as $identifier => $path) {
    $iconList[$identifier] = [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:news/Resources/Public/Icons/' . $path,
    ];
}

return $iconList;
