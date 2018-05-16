<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'News system',
    'description' => 'Versatile news extension, based on extbase & fluid. Editor friendly, default integration of social sharing and many other features',
    'category' => 'fe',
    'author' => 'Georg Ringer',
    'author_email' => 'mail@ringer.it',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '7.0.4',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.13-9.2.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'dd_googlesitemap' => '2.0.5-2.99.99',
            'realurl' => '2.3.0-2.99.99',
            'rx_shariff' => '11.0.0-11.99.99'
        ],
    ],
];
