<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Test fixture: extend NewsDefault',
    'description' => 'Registers a partial for Domain/Model/NewsDefault',
    'category' => 'example',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'news' => '',
        ],
    ],
];
