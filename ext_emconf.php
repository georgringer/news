<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'News system',
    'description' => 'Versatile news system based on Extbase & Fluid and using the latest technologies provided by TYPO3 CMS.',
    'category' => 'fe',
    'author' => 'Georg Ringer',
    'author_email' => 'mail@ringer.it',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '11.4.3',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-8.3.99',
            'typo3' => '11.5.19-12.9.99',
            'backend' => '11.5.19-12.9.99',
            'extbase' => '11.5.19-12.9.99',
            'fluid' => '11.5.19-12.9.99',
            'frontend' => '11.5.19-12.9.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'rx_shariff' => '12.0.0-14.99.99',
            'news_tagsuggest' => '1.0.0-1.99.99',
            'numbered_pagination' => '1.0.1-1.99.99',
        ],
    ],
];
