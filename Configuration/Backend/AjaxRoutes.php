<?php

/**
 * Definitions for routes provided by EXT:news
 */
return [
    'news_tag' => [
        'path' => '/news/tag',
        'target' => \GeorgRinger\News\Backend\TagEndPoint::class . '::create'
    ]
];
