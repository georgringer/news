<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'News system',
    'description' => 'Versatile news system based on Extbase & Fluid and using the latest technologies provided by TYPO3 CMS.',
    'category' => 'fe',
    'author' => 'Georg Ringer',
    'author_email' => 'mail@ringer.it',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '10.0.3',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.19-12.9.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'rx_shariff' => '12.0.0-14.99.99',
            'news_tagsuggest' => '1.0.0-1.99.99',
            'numbered_pagination' => '1.0.1-1.99.99',
        ],
    ],
];
