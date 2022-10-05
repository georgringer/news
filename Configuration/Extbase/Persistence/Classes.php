<?php

declare(strict_types=1);

return [
    \GeorgRinger\News\Domain\Model\News::class => [
        'subclasses' => [
            0 => \GeorgRinger\News\Domain\Model\NewsDefault::class,
            1 => \GeorgRinger\News\Domain\Model\NewsInternal::class,
            2 => \GeorgRinger\News\Domain\Model\NewsExternal::class,
        ]
    ],
    \GeorgRinger\News\Domain\Model\NewsDefault::class => [
        'tableName' => 'tx_news_domain_model_news',
        'recordType' => 0,
    ],
    \GeorgRinger\News\Domain\Model\NewsInternal::class => [
        'tableName' => 'tx_news_domain_model_news',
        'recordType' => 1,
    ],
    \GeorgRinger\News\Domain\Model\NewsExternal::class => [
        'tableName' => 'tx_news_domain_model_news',
        'recordType' => 2,
    ],
    \GeorgRinger\News\Domain\Model\FileReference::class => [
        'tableName' => 'sys_file_reference',
    ],
    \GeorgRinger\News\Domain\Model\TtContent::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'altText' => [
                'fieldName' => 'altText'
            ],
            'titleText' => [
                'fieldName' => 'titleText'
            ],
            'colPos' => [
                'fieldName' => 'colPos'
            ],
            'CType' => [
                'fieldName' => 'CType'
            ],
        ],
    ],
    \GeorgRinger\News\Domain\Model\Category::class => [
        'tableName' => 'sys_category',
        'properties' => [
            'parentcategory' => [
                'fieldName' => 'parent'
            ],
        ],
    ],
];
