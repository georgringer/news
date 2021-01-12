<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'News system',
    'description' => 'Versatile news extension, based on extbase & fluid. Editor friendly, default integration of social sharing and many other features',
    'category' => 'fe',
    'author' => 'Georg Ringer',
    'author_email' => 'mail@ringer.it',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '8.5.2',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.17-10.9.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'rx_shariff' => '12.0.0-13.99.99'
        ],
    ],
];
