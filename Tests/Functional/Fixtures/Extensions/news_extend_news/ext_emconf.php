<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Test fixture: extend News',
    'description' => 'Registers a partial for Domain/Model/News',
    'category' => 'example',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'news' => '',
        ],
    ],
];
