<?php

declare(strict_types=1);

use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Model\NewsDefault;
use GeorgRinger\News\Domain\Model\NewsExternal;
use GeorgRinger\News\Domain\Model\NewsInternal;
use GeorgRinger\News\Domain\Model\TtContent;

return [
    News::class => [
        'subclasses' => [
            0 => NewsDefault::class,
            1 => NewsInternal::class,
            2 => NewsExternal::class,
        ],
    ],
    NewsDefault::class => [
        'tableName' => 'tx_news_domain_model_news',
        'recordType' => 0,
    ],
    NewsInternal::class => [
        'tableName' => 'tx_news_domain_model_news',
        'recordType' => 1,
    ],
    NewsExternal::class => [
        'tableName' => 'tx_news_domain_model_news',
        'recordType' => 2,
    ],
    FileReference::class => [
        'tableName' => 'sys_file_reference',
    ],
    TtContent::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'altText' => [
                'fieldName' => 'altText',
            ],
            'titleText' => [
                'fieldName' => 'titleText',
            ],
            'colPos' => [
                'fieldName' => 'colPos',
            ],
            'CType' => [
                'fieldName' => 'CType',
            ],
        ],
    ],
    Category::class => [
        'tableName' => 'sys_category',
        'properties' => [
            'parentcategory' => [
                'fieldName' => 'parent',
            ],
        ],
    ],
];
