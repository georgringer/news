<?php

return [
    'frontend' => [
        'news/reset-already-displayed' => [
            'target' => \GeorgRinger\News\Middleware\ResetAlreadyDisplayedMiddleware::class,
            'description' => 'Reset $GLOBALS[\'EXT\'][\'news\'][\'alreadyDisplayed\'] per request to avoid unbounded growth under PHP-FPM worker reuse',
            'after' => [
                'typo3/cms-frontend/prepare-tsfe-rendering',
            ],
        ],
    ],
];
