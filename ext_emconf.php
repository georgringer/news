<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'News system',
    'description' => 'Versatile news extension, based on extbase & fluid. Editor friendly, default integration of social sharing and many other features',
    'category' => 'fe',
    'author' => 'Georg Ringer',
    'author_email' => 'typo3@ringerge.org',
    'shy' => '',
    'dependencies' => '',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => 1,
    'modify_tables' => '',
    'clearCacheOnLoad' => 1,
    'lockType' => '',
    'author_company' => '',
    'version' => '6.1.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.13-8.7.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'dd_googlesitemap' => '2.0.5-2.99.99',
            'rx_shariff' => '7.0.0-10.99.99'
        ],
    ],
    'suggests' => [],
];
