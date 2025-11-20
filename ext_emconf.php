<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'News system',
    'description' => 'Versatile news system based on Extbase & Fluid and using the latest technologies provided by TYPO3 CMS.',
    'category' => 'fe',
    'author' => 'Georg Ringer',
    'author_email' => 'mail@ringer.it',
    'state' => 'stable',
    'version' => '13.0.1',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.4.99',
            'typo3' => '12.4.37-13.9.99',
            'backend' => '12.4.37-13.9.99',
            'extbase' => '12.4.37-13.9.99',
            'fluid' => '12.4.37-13.9.99',
            'frontend' => '12.4.37-13.9.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'rx_shariff' => '12.0.0-14.99.99',
            'news_tagsuggest' => '1.0.0-1.99.99',
            'numbered_pagination' => '1.0.1-1.99.99',
        ],
    ],
];
